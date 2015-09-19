<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Dto\EmailRequest;
    use Application\API\Canonicals\Entity\Qurbani;
    
    interface IGeneralMailingService {
        public function sendEmailRequest(EmailRequest $request);
        public function qurbaniConfrimationAlert($qurbaniKey);
        public function qurbaniCompleteAlert(Qurbani $qurbani);
    }
}
