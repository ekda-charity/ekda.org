<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\EMailService;

    class EMailServiceFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config      = $serviceLocator->get('Config');
            $em          = $serviceLocator->get('doctrine.entitymanager.orm_default');
            
            return new EMailService($em, $config['SMTPDetails'], $config['SMTPSender'], $config['SupportEmail'], $config['QueueEmails'], $config['SendToRecipient']);
        }
    }
}
