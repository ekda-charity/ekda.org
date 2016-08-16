<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Dto\Email;
    
    interface IEMailService {
        public function sendMail(Email $sender, $recipients, $subject, $textBody, $htmlBody=null);
        public function sendBccMail(Email $sender, $recipients, $subject, $textBody, $htmlBody=null);
        public function sendMailFromDatabase();
    }
    
}
