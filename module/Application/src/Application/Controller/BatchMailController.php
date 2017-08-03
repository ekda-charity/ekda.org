<?php

namespace Application\Controller {
    
    use Zend\Mvc\Controller\AbstractActionController;
    use Application\API\Repositories\Interfaces\IEMailService;
    
    class BatchMailController extends AbstractActionController  {
        
        /**
         * @var IEMailService
         */
        private $emailService;
        
        public function __construct(IEMailService $emailService) {
            $this->emailService = $emailService;
        }
        
        public function sendAction() {
            try {
                $this->emailService->sendMailFromDatabase();
            } catch (\Exception $ex) {
                error_log($ex->getMessage());
            }
            exit();
        }
    }
}

