<?php

namespace Application\ControllerFactory {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\IndexController,
        JMS\Serializer\SerializerBuilder;
    
    class IndexControllerFactory implements FactoryInterface {
    
        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $navRepo = $serviceLocator->get('Navigation');
            $adminAuthService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            $wpRepo = $serviceLocator->get('WordPrRepo');
            $qurbaniRepo = $serviceLocator->get('QurbaniRepo');
            
            return new IndexController($navRepo, $adminAuthService, $serializer, $wpRepo, $qurbaniRepo);
        }        
    }
}
