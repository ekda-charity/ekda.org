<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\EMailService;

    class EMailServiceFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config               = $serviceLocator->get('Config');
            $em                   = $serviceLocator->get('doctrine.entitymanager.orm_default');
            $queueEmails          = $config['QueueEmails'];
            $useThirdPartyEmailer = $config['UseThirdPartyEmailer'];
            $privateKey           = $config['PrivateKey'];
            $getMailApi           = $config['GetMailApi'];
            $clearMailApi         = $config['ClearMailApi'];
            $mailApiKey           = $config['MailApiKey'];
            
            return new EMailService($em, $config['SMTPDetails'], $queueEmails, $useThirdPartyEmailer, $privateKey, $getMailApi, $clearMailApi, $mailApiKey);
        }
    }
}
