<?php

namespace Application\API\Repositories\Implementations {
    
    use Doctrine\ORM\EntityManager,
        Application\API\Repositories\Interfaces\IEMailService,
        Application\API\Canonicals\Dto\Email,
        Application\API\Canonicals\Entity\Emails;
    
    class EMailService extends BaseRepository implements IEMailService {
        
        private $smtpDetails;
        private $queueEmails;
        
        public function __construct(EntityManager $em, $smtpDetails, $queueEmails) {
            parent::__construct($em);
            $this->smtpDetails = $smtpDetails;
            $this->queueEmails = $queueEmails;
        }
        
        public function sendMail(Email $sender, $recipients, $subject, $textBody, $htmlBody = null) {
            if (!$this->queueEmails) {
                $worker = new EMailServiceWorker($this->smtpDetails, $sender, $recipients, $subject, $textBody, $htmlBody, false);
                $worker->run();
            } else {
                $email = new Emails();
                $email->setHost($this->smtpDetails["SMTP_HOST"]);
                $email->setPort($this->smtpDetails["SMTP_PORT"]);
                $email->setAuth($this->smtpDetails["SMTP_AUTH"]);
                $email->setUsername($sender->getUsername());
                $email->setPassword($sender->getPassword());
                $email->setRecipients($recipients);
                $email->setSubject($subject);
                $email->setText($textBody);
                $email->setHtml($htmlBody);
                $email->setBcc(false);

                $this->emailsRepo->add($email);
            }
        }

        public function sendBccMail(Email $sender, $recipients, $subject, $textBody, $htmlBody = null) {
            if (!$this->queueEmails) {
                $worker = new EMailServiceWorker($this->smtpDetails, $sender, $recipients, $subject, $textBody, $htmlBody, true);
                $worker->run();
            } else {
                $email = new Emails();
                $email->setHost($this->smtpDetails["SMTP_HOST"]);
                $email->setPort($this->smtpDetails["SMTP_PORT"]);
                $email->setAuth($this->smtpDetails["SMTP_AUTH"]);
                $email->setUsername($sender->getUsername());
                $email->setPassword($sender->getPassword());
                $email->setRecipients($recipients);
                $email->setSubject($subject);
                $email->setText($textBody);
                $email->setHtml($htmlBody);
                $email->setBcc(true);

                $this->emailsRepo->add($email);
            }
        }
        
        public function sendMailFromDatabase() {
            if ($this->queueEmails) {
                $emails = $this->emailsRepo->fetchAll();
                $workers = [];

                foreach ($emails as $email) {
                    $smtpDetails = array(
                        "SMTP_HOST" => $email->getHost(),
                        "SMTP_PORT" => $email->getPort(),
                        "SMTP_AUTH" => $email->getAuth()
                    );
                    $sender = new Email($email->getUsername(), $email->getPassword());
                    $recipients = $email->getRecipients();
                    $subject = $email->getSubject();
                    $textBody = $email->getText();
                    $htmlBody = $email->getHtml();
                    $bcc = $email->getBcc();

                    $workers[] = new EMailServiceWorker($smtpDetails, $sender, $recipients, $subject, $textBody, $htmlBody, $bcc);
                    $this->emailsRepo->delete($email);
                }
                
                foreach($workers as $worker) {
                    $worker->run();
                }
            }
        }
    }
}
