<?php

namespace Application\API\Repositories\Implementations {
    
    use Application\API\Canonicals\Dto\EmailRequest,
        Zend\Mail\Transport\Smtp as SmtpTransport,
        Zend\Mail\Transport\SmtpOptions,
        Zend\Mail\Message,
        Zend\Mime\Message as MimeMessage,
        Zend\Mime\Part as MimePart;
    
    class EMailServiceWorker {
        
        private $host;
        private $port;
        private $auth;
        private $user;
        private $pass;
        private $sender;
        private $recipients;
        private $subject;
        private $textBody;
        private $htmlBody;
        private $bcc;
        
        
        public function __construct($smtpDetails, $smtpSender, $supportEmail, EmailRequest $request, $bcc = false) {
            $this->host = $smtpDetails['SMTP_HOST'];
            $this->port = $smtpDetails['SMTP_PORT'];
            $this->auth = $smtpDetails['SMTP_AUTH'];
            $this->user = $smtpSender['username'];
            $this->pass = $smtpSender['password'];
            
            $this->sender = $supportEmail;
            $this->recipients = explode(",", $request->recipient);
            $this->subject = $request->subject;
            $this->textBody = $request->textbody;
            $this->htmlBody = $request->htmlbody;
            $this->bcc = $bcc;
        }
        
        public function run() {
            
            $transport = new SmtpTransport(new SmtpOptions(array(
                'name'              => $this->host,
                'host'              => $this->host,
                'port'              => $this->port,
                'connection_config' => array(
                    'username' => $this->user,
                    'ssl'      => 'tls',
                    'password' => $this->pass,
                ),
            )));
            
            if ($this->auth !== null) {
                $transport->getOptions()->setConnectionClass($this->auth);
            }
            
            $message = new Message();
            $message->addFrom($this->sender)
                    ->setSubject($this->subject);

            if ($this->bcc) {
                $message->addBcc($this->recipients);
            } else {
                $message->addTo($this->recipients);
            }
            
            $body = new MimeMessage();
            
            if ($this->htmlBody == null) {
                $text = new MimePart($this->textBody);
                $text->type = "text/plain";
                $body->setParts(array($text));
            } else {
                $html = new MimePart($this->htmlBody);
                $html->type = "text/html";
                $body->setParts(array($html));
            }
            
            $message->setBody($body);
            $transport->send($message);
        }
    }
}
