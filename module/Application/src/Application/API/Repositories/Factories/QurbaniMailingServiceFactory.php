<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\QurbaniMailingService;

    class QurbaniMailingServiceFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config    = $serviceLocator->get('Config');
            $em        = $serviceLocator->get('doctrine.entitymanager.orm_default');
            $emailRepo = $serviceLocator->get('EMailSvc');
            
            return new QurbaniMailingService($em, $emailRepo, $config['QurbaniDetails'], $config['DomainName'], $config['ENV']);
        }
    }
}
