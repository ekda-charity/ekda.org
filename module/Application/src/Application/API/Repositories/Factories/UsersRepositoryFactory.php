<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\UsersRepository;
    
    class UsersRepositoryFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            return new UsersRepository($serviceLocator->get('doctrine.entitymanager.orm_default'));
        }
    }
}
