<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\QurbaniRepository;

    class QurbaniRepositoryFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config    = $serviceLocator->get('Config');
            $em        = $serviceLocator->get('doctrine.entitymanager.orm_default');
            
            return new QurbaniRepository($em, $config['QurbaniDetails']);
        }
    }
}
