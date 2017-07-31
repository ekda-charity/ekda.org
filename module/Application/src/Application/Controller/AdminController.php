<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Canonicals\General\Constants;
    
    class AdminController extends BaseController  {
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer) {
            parent::__construct($navService, $authService, $serializer);
        }
        
        public function indexAction() {
            $this->navService->findOneById(Constants::ADMIN_ID)->setVisible(true);
            $this->navService->findOneById(Constants::ADMIN_ID)->setActive(true);
            
            if ($this->authService->hasIdentity()) {
                return $this->redirect()->toUrl("/Admin/qurbani");
            } else {
                return [];
            }
        }
        
        public function logoutAction() {
            $this->authService->clearIdentity();
            return $this->redirect()->toUrl("/Admin/index");
        }
        
        public function qurbaniAction() {
            return [];
        }
    }
}

