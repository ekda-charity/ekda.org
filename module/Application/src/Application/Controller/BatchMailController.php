<?php

namespace Application\Controller {
    
    use Zend\Mvc\Controller\AbstractActionController;
    use JMS\Serializer\SerializationContext;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\IEMailService;
    
    class BatchMailController extends AbstractActionController  {
        
        /**
         * @var IEMailService
         */
        private $emailRepo;
        
        /**
         * @var SerializerInterface
         */
        private $serializer;
        
        public function __construct(IEMailService $emailRepo, SerializerInterface $serializer) {
            $this->emailRepo = $emailRepo;
            $this->serializer = $serializer;
        }
        
        public function sendAction() {
            try {
                $emailKeys = $this->emailRepo->getMailFromServer();
                $this->emailRepo->clearMailFromServer($emailKeys);
                $this->emailRepo->sendMailFromDatabase();
                
            } catch (\Exception $ex) {
                error_log($ex->getMessage());
            }
            
            exit();
        }
        
        public function getmailAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\ThirdPartyEmailTransport", "json");

                $emails = $this->emailRepo->fetchMail($data->mailapikey);

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

                $this->emailRepo->clearMail($data->mailapikey, $data->emailkeys);

            } catch (\Exception $ex) {
                error_log($ex->getMessage());
            }
            
            exit();
        }
    }
}

