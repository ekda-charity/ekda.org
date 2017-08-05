<?php

namespace Application\Controller {
    
    use Zend\Mvc\Controller\AbstractActionController;
    use JMS\Serializer\SerializerInterface;
    use JMS\Serializer\SerializationContext;
    use Application\API\Canonicals\Response\ResponseUtils;
    use Application\API\Repositories\Interfaces\IQurbaniMailingService;

    class QurbaniEmailsApiController extends AbstractActionController {
        
        /**
         * @var IQurbaniMailingService 
         */
        private $gMailSvc;
        
        /**
         * @var string
         */
        private $emailingApiKey;
        
        /**
         * @var SerializerInterface
         */
        private $serializer;
        
        public function __construct(IQurbaniMailingService $gMailSvc, SerializerInterface $serializer, $emailingApiKey) {
            $this->gMailSvc = $gMailSvc;
            $this->serializer = $serializer;
            $this->emailingApiKey = $emailingApiKey;
        }
        
        public function sendmailAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\ThirdPartyQurbaniEmailTransport", "json");
                
                if ($data->mailapikey != $this->emailingApiKey) {
                    throw new \Exception("Invalid Api Key");
                }
                
                $this->gMailSvc->qurbaniAlert($data->qurbani, $data->emailtype);

                $response = ResponseUtils::createResponse();
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
            }
            
            $context = new SerializationContext();
            $context->setSerializeNull(true);
            $content = $this->serializer->serialize($response, 'json', $context);
            $this->response->setContent($content);
            return $this->response;
        }
    }
}