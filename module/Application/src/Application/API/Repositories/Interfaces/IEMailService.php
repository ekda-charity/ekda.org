<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Dto\EmailRequest;
    
    interface IEMailService {
        public function sendMail(EmailRequest $emailRequest);
        public function sendBccMail(EmailRequest $emailRequest);
        public function sendMailFromDatabase();
    }
}
