<?php

namespace Application\Controller {
    
    use Zend\Mvc\Controller\AbstractActionController;
    use JMS\Serializer\SerializerBuilder;
    use JMS\Serializer\SerializationContext;
    
    class BatchMailController extends AbstractActionController  {
        
        /**
         * @var JMS\Serializer\SerializerInterface
         */
        private $serializer;
        
        public function __construct() {
            $this->serializer = SerializerBuilder::create()->build();
        }
        
        public function sendAction() {
            try {
                $emailRepo = $this->getServiceLocator()->get('EMailSvc');
                
                $emailKeys = $emailRepo->getMailFromServer();
                $emailRepo->clearMailFromServer($emailKeys);
                $emailRepo->sendMailFromDatabase();
                
            } catch (\Exception $ex) {
                error_log($ex->getMessage());
            }
            
            exit();
        }
        
        public function getmailAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\ThirdPartyEmailTransport", "json");

                $emailRepo = $this->getServiceLocator()->get('EMailSvc');
                $emails = $emailRepo->fetchMail($data->mailapikey);

                $context = new SerializationContext();
                $context->setSerializeNull(true);
                $content = $this->serializer->serialize($emails, 'json', $context);
                $this->response->setContent($content);
                
                return $this->response;
            } catch (\Exception $ex) {
                error_log($ex->getMessage());
            }
            
            exit();
        }
        
        public function clearmailAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\ThirdPartyEmailTransport", "json");

                $emailRepo = $this->getServiceLocator()->get('EMailSvc');
                $emailRepo->clearMail($data->mailapikey, $data->emailkeys);

            } catch (\Exception $ex) {
                error_log($ex->getMessage());
            }
            
            exit();
        }
    }
}

