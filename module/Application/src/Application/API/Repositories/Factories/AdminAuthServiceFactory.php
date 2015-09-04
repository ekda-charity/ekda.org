<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Zend\Authentication\AuthenticationService,
        Zend\Authentication\Adapter\DbTable;
    
    class AdminAuthServiceFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            
            $dbAdapter   = $serviceLocator->get('ZendDbAdapter');
            $authStorage = $serviceLocator->get('AdminAuthStorage');
            
            $authAdapter = new DbTable($dbAdapter, 'Users','username','password', 'MD5(?)');
            
            $authService = new AuthenticationService();
            $authService->setAdapter($authAdapter);
            $authService->setStorage($authStorage);
              
            return $authService;
        }
    }
}
