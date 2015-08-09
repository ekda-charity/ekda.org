<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Dto\EmailRequest;
    
    interface IGeneralMailingService {
        public function sendEmailRequest(EmailRequest $request);
    }
}
