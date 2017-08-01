<?php

namespace Application\Controller {
    
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\Mvc\Controller\AbstractController;
    use Zend\EventManager\EventManagerInterface;
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    
    use JMS\Serializer\SerializationContext;
    use JMS\Serializer\SerializerInterface;
    
    use Application\API\Canonicals\General\Constants;

    class BaseController extends AbstractActionController {

        /**
         * @var SerializerInterface
         */
        protected $serializer;
        
        /**
         * @var string
         */
        protected $controller;
        
        /**
         * @var string
         */
        protected $action;
        
        /**
         * @var string
         */
        protected $p1;
        
        /**
         * @var string
         */
        protected $p2;
        
        /**
         * @var string
         */
        protected $p3;
        
        /**
         * @var string
         */
        protected $p4;

        /**
         * @var AuthenticationServiceInterface 
         */
        protected $authService;
        
        /**
         * @var AbstractContainer
         */
        protected $navService;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer) {
            $this->serializer = $serializer;
            $this->navService = $navService;
            $this->authService = $authService;
        }
        
        protected function addFlashErrorMsgs($messages) {
            foreach($messages as $message) {
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        
        protected function addFlashSuccessMsgs($messages) {
            foreach($messages as $message) {
                $this->flashMessenger()->addSuccessMessage($message);
            }
        }
        
        protected function addFlashInfoMsgs($messages) {
            foreach($messages as $message) {
                $this->flashMessenger()->addInfoMessage($message);
            }
        }
        
        protected function jsonResponse($data) {
            $context = new SerializationContext();
            $context->setSerializeNull(true);
            $content = $this->serializer->serialize($data, 'json', $context);
            
            $this->response->setContent($content);
            return $this->response;
        }
        
        public function setEventManager(EventManagerInterface $events) {
            parent::setEventManager($events);
            
            $self = $this;
            $events->attach('dispatch', function ($e) use ($self) {
                $self->controller = $self->params()->fromRoute('controller');
                $self->action     = $self->params()->fromRoute('action');
                $self->p1         = $self->params()->fromRoute('p1');
                $self->p2         = $self->params()->fromRoute('p2');
                $self->p3         = $self->params()->fromRoute('p3');
                $self->p4         = $self->params()->fromRoute('p4');
                
                $self->setPermissions($self);
            }, 100);
            
            return $this;
        }
        
        private function setPermissions(AbstractController $self) {

            $baseNode          = $self->navService->findOneById(Constants::ADMIN_ID);
            $restrictedNodes   = $self->navService->findAllBy("requireslogin", true);
            $hasAccess         = $self->authService->hasIdentity();

            $baseNode->setVisible($hasAccess);

            foreach ($restrictedNodes as $rec) {
                $self->navService->findOneById($rec->get("id"))->setVisible($hasAccess);
            }

            if ($hasAccess) { 
                $baseNode->setLabel("Logout");
                $baseNode->setAction("logout");                
                return;
            }

            $controller = strtolower($self->controller);
            $action = strtolower($self->action);

            $unauthorizedAttempt = array_filter($restrictedNodes, function ($item) use ($controller, $action) {
                return strtolower($item->get("controller")) == $controller && strtolower($item->get("action")) == $action;
            });

            if (count($unauthorizedAttempt) > 0) {
                return $self->redirect()->toUrl("/Index/index");
            }
        }
    }
}

