<?php

namespace Application\ControllerFactory {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\Controller\BatchMailController;
    
    class BatchMailControllerFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $sli) {
            $serviceLocator = $sli->getServiceLocator();
            $emailSvc = $serviceLocator->get('EMailSvc');
            return new BatchMailController($emailSvc);
        }
    }
}

