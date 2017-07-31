<?php

namespace Application\ControllerFactory {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\QurbaniApiController,
        JMS\Serializer\SerializerBuilder;
    
    class QurbaniApiControllerFactory implements FactoryInterface {
    
        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $navRepo = $serviceLocator->get('Navigation');
            $adminAuthService = $serviceLocator->get('AdminAuthService');
            $serializer = SerializerBuilder::create()->build();
            $gMailSvc = $serviceLocator->get('GMailSvc');
            $qurbaniRepo = $serviceLocator->get('QurbaniRepo');
            $config = $serviceLocator->get('Config');
            $domainName = $config["DomainName"];
            
            return new QurbaniApiController($navRepo, $adminAuthService, $serializer, $qurbaniRepo, $gMailSvc, $domainName);
        }        
    }
}
