<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\WordPressRepository;

    class WordPressRepositoryFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $serviceLocator) {
            return new WordPressRepository();
        }
    }
}
