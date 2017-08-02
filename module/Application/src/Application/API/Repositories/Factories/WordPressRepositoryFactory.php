<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\WordPressRepository,
        JMS\Serializer\SerializerBuilder;

    class WordPressRepositoryFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $serviceLocator) {
            $serializer = SerializerBuilder::create()->build();
            return new WordPressRepository($serializer);
        }
    }
}
