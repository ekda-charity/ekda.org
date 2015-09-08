<?php

namespace Application\Controller {
    
    use Application\API\Canonicals\General\Constants;
    use JMS\Serializer\SerializationContext;
    
    class AdminController extends BaseController  {
        
        public function indexAction() {
            $this->getServiceLocator()->get('Navigation')->findOneById(Constants::ADMIN_ID)->setVisible(true);
            $this->getServiceLocator()->get('Navigation')->findOneById(Constants::ADMIN_ID)->setActive(true);
            $authService = $this->getServiceLocator()->get('AdminAuthService');
            
            if ($authService->hasIdentity()) {
                return $this->redirect()->toUrl("/Admin/qurbani");
            }
            
            return array();
        }
        
        public function logoutAction() {
            $authService = $this->getServiceLocator()->get('AdminAuthService');
            $authStorage = $this->getServiceLocator()->get('AdminAuthStorage');
            
            $authStorage->forgetMe();
            $authService->clearIdentity();
            return $this->redirect()->toUrl("/Admin/index");
        }
        
        public function qurbaniAction() {
            return array();
        }
    }
}

