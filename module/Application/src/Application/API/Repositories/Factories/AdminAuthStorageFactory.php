<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\AuthStorage;
    
    class AdminAuthStorageFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            return new AuthStorage('CC688E0F_0C12_4680_9E92_EEE499C4A4B1');
        }
    }
}
