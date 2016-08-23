<?php

namespace Application\API\Repositories\Implementations {
    
    use Doctrine\ORM\EntityManager,
        Application\API\Repositories\Interfaces\IEMailService,
        Application\API\Canonicals\Dto\Email,
        Application\API\Canonicals\Entity\Emails,
        Zend\Http\Client,
        Zend\Http\Request,
        JMS\Serializer\SerializerBuilder,
        JMS\Serializer\SerializationContext,
        Application\API\Canonicals\Dto\ThirdPartyEmailTransport;
    
    class EMailService extends BaseRepository implements IEMailService {
        
        private $smtpDetails;
        private $queueEmails;
        private $useThirdPartyEmailer;
        private $privateKey;
        private $getMailApi;
        private $clearMailApi;
        private $mailApiKey;
        
        /**
         *
         * @var JMS\Serializer\SerializerInterface
         */
        private $serializer;
        
        public function __construct(EntityManager $em, $smtpDetails, $queueEmails, $useThirdPartyEmailer, $privateKey, $getMailApi, $clearMailApi, $mailApiKey) {
            parent::__construct($em);
            $this->smtpDetails = $smtpDetails;
            $this->queueEmails = $queueEmails;
            $this->useThirdPartyEmailer = $useThirdPartyEmailer;
            $this->privateKey = $privateKey;
            $this->getMailApi = $getMailApi;
            $this->clearMailApi = $clearMailApi;
            $this->mailApiKey = $mailApiKey;
            $this->serializer = SerializerBuilder::create()->build();
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
            if ($this->queueEmails && !$this->useThirdPartyEmailer) {
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

        public function getMailFromServer() {
            if ($this->getMailApi == null || $this->mailApiKey == null) {
                return;
            }

            $data = new ThirdPartyEmailTransport();
            $data->mailapikey = $this->mailApiKey;

            $url = $this->getMailApi;
            $postString = $this->getJson($data);
            $content = $this->sendCurlRequest($url, $postString);

            $emails = $this->serializer->deserialize($content, "array<Application\API\Canonicals\Entity\Emails>", "json");
            $emailKeys = [];

            foreach ($emails as $email) {
                $emailKeys[] = $email->getEmailkey(); // Has to be taken before the save, as it will update with new value
                $email->setHost($this->smtpDetails["SMTP_HOST"]);
                $email->setPort($this->smtpDetails["SMTP_PORT"]);
                $email->setAuth($this->smtpDetails["SMTP_AUTH"]);
                $this->emailsRepo->add($email);
            }

            return $emailKeys;
        }

        public function clearMailFromServer($emailKeys) {
            if ($this->clearMailApi == null || $this->mailApiKey == null || count($emailKeys) <= 0) {
                return;
            }
            
            $data = new ThirdPartyEmailTransport();
            $data->mailapikey = $this->mailApiKey;
            $data->emailkeys = $emailKeys;

            $url = $this->clearMailApi;
            $postString = $this->getJson($data);
            $this->sendCurlRequest($url, $postString);

        }

        public function fetchMail($mailApiKey) {
            if ($this->useThirdPartyEmailer && $this->privateKey == $mailApiKey) {
                $emails = $this->emailsRepo->fetchAll();
                return $emails;
            }
        }
        
        public function clearMail($mailApiKey, $emailKeys) {
            if ($this->useThirdPartyEmailer && $this->privateKey == $mailApiKey && count($emailKeys) > 0) {
                $this->emailsRepo->deleteListByKeys($emailKeys);
            }
        }
        
        private function getJson($data) {
            $context = new SerializationContext();
            $context->setSerializeNull(true);
            return $this->serializer->serialize($data, 'json', $context);
        }
        
        private function sendCurlRequest($url, $postString, $method = Request::METHOD_POST) {
            $request = new Request();
            $request->setUri($url);
            $request->setMethod($method);
            $request->setContent($postString);            

            $client = new Client();
            $client->setAdapter('Zend\Http\Client\Adapter\Curl');

            $response = $client->dispatch($request);
            return $response->getContent();
        }
    }
}
