<?php

namespace Application\API\Repositories\Implementations {
    
    use Doctrine\ORM\EntityManager,
        Application\API\Repositories\Interfaces\IEMailService,
        Application\API\Canonicals\Dto\EmailRequest,
        Application\API\Canonicals\Entity\Email;
    
    class EMailService extends BaseRepository implements IEMailService {
        
        private $smtpDetails;
        private $smtpSender;
        private $supportEmail;
        private $queueEmails;
        private $sendToRecipient;
        
        public function __construct(EntityManager $em, $smtpDetails, $smtpSender, $supportEmail, $queueEmails, $sendToRecipient) {
            parent::__construct($em);
            $this->smtpDetails = $smtpDetails;
            $this->smtpSender = $smtpSender;
            $this->supportEmail = $supportEmail;
            $this->queueEmails = $queueEmails;
            $this->sendToRecipient = $sendToRecipient;
        }
        
        public function sendMail(EmailRequest $emailRequest) {
            
            if (!$this->sendToRecipient) {
                $emailRequest->recipient = $this->supportEmail;
            }
            
            if (!$this->queueEmails) {
                $worker = new EMailServiceWorker($this->smtpDetails, $this->smtpSender, $this->supportEmail, $emailRequest, false);
                $worker->run();
            } else {
                $email = new Email();
                $email->setRecipients($emailRequest->recipient);
                $email->setSubject($emailRequest->subject);
                $email->setText($emailRequest->textbody);
                $email->setHtml($emailRequest->htmlbody);
                $email->setBcc(0);
                $email->setSent(0);
                $email->setSending(0);

                $this->emailsRepo->add($email);
            }
        }

        public function sendBccMail(EmailRequest $emailRequest) {
            
            if (!$this->sendToRecipient) {
                $emailRequest->recipient = $this->supportEmail;
            }
            
            if (!$this->queueEmails) {
                $worker = new EMailServiceWorker($this->smtpDetails, $this->smtpSender, $this->supportEmail, $emailRequest, true);
                $worker->run();
            } else {
                $email = new Email();
                $email->setRecipients($emailRequest->recipient);
                $email->setSubject($emailRequest->subject);
                $email->setText($emailRequest->textbody);
                $email->setHtml($emailRequest->htmlbody);
                $email->setBcc(1);
                $email->setSent(0);
                $email->setSending(0);

                $this->emailsRepo->add($email);
            }
        }
        
        public function sendMailFromDatabase() {
            if (!$this->queueEmails) {
                return;
            }

            $this->em->transactional(function(EntityManager $em) {
                $emails = $this->emailsRepo->findBy([ 'sending' => 0 ]);
                $workers = [];

                foreach ($emails as $email) {
                    $emailRequest = new EmailRequest();
                    $emailRequest->recipient = $this->sendToRecipient ? $email->getRecipients() : $this->supportEmail;
                    $emailRequest->subject = $email->getSubject();
                    $emailRequest->textbody = $email->getText();
                    $emailRequest->htmlbody = $email->getHtml();

                    $workers[$email->getEmailkey()] = new EMailServiceWorker($this->smtpDetails, $this->smtpSender, $this->supportEmail, $emailRequest, $email->getBcc());
                    $email->setSending(1);
                    $this->emailsRepo->update($email);
                }

                foreach($workers as $emailKey => $worker) {
                    $worker->run();
                    $email = $this->emailsRepo->fetch($emailKey);
                    $email->setSent(1);
                    $this->emailsRepo->update($email);
                }
            });
        }
    }
}
