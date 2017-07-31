<?php

namespace Application\ControllerFactory {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\BatchMailController,
        JMS\Serializer\SerializerBuilder;
    
    class BatchMailControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $serializer = SerializerBuilder::create()->build();
            $emailSvc = $serviceLocator->get('EMailSvc');
            return new BatchMailController($serializer, $emailSvc);
        }
    }
}

