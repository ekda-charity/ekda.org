<?php

namespace Application\Controller {
    
    use Zend\Mvc\Controller\AbstractActionController;
    
    class BatchMailController extends AbstractActionController  {
        
        public function sendAction() {
            $emailRepo = $this->getServiceLocator()->get('EMailSvc');
            $emailRepo->sendMailFromDatabase();
            return $this->getResponse();
        }
    }
}

