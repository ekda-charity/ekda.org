<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Zend\Db\Adapter\Adapter;
    
    class ZendDbAdapterFactory implements FactoryInterface {
        
        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config = $serviceLocator->get('Config');
            return new Adapter($config['ZendDbAdapterParams']);
        }
    }
}
