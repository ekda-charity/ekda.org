<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Zend\Authentication\AuthenticationService,
        Zend\Db\Adapter\Adapter,
        Zend\Authentication\Adapter\DbTable,
        Zend\Authentication\Storage\Session;
    
    class AdminAuthServiceFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config      = $serviceLocator->get('Config');
            $dbAdapter   = new Adapter($config['doctrine']['connection']['orm_default']['params']);
            
            $authAdapter = new DbTable($dbAdapter, 'Users','username','password', '?');
            $authStorage = new Session('CC688E0F_0C12_4680_9E92_EEE499C4A4B1');
            
            return new AuthenticationService($authStorage, $authAdapter);
        }
    }
}
