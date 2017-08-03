<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\GeneralMailingService;

    class GeneralMailingServiceFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config    = $serviceLocator->get('Config');
            $em        = $serviceLocator->get('doctrine.entitymanager.orm_default');
            $emailRepo = $serviceLocator->get('EMailSvc');
            
            return new GeneralMailingService($em, $emailRepo, $config['QurbaniDetails'], $config['DomainName']);
        }
    }
}
