<?php

namespace Application\ControllerFactory {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        JMS\Serializer\SerializerBuilder,
        Application\Controller\QurbaniEmailsApiController;
    
    class QurbaniEmailsApiControllerFactory implements FactoryInterface {
    
        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $gMailSvc = $serviceLocator->get('GMailSvc');
            $config = $serviceLocator->get('Config');
            $serializer = SerializerBuilder::create()->build();
            
            return new QurbaniEmailsApiController($gMailSvc, $serializer, $config['QurbaniDetails']['emailingApiKey']);
        }        
    }
}
