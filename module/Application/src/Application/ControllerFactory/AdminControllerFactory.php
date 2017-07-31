<?php

namespace Application\ControllerFactory {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\AdminController,
        JMS\Serializer\SerializerBuilder;
    
    class AdminControllerFactory implements FactoryInterface {
    
        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $navRepo = $serviceLocator->get('Navigation');
            $adminAuthService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            
            return new AdminController($navRepo, $adminAuthService, $serializer);
        }        
    }
}
