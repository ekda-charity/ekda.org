<?php

namespace Application\Controller {
    
    use Application\API\Canonicals\Response\ResponseUtils;

    class GeneralApiController extends BaseController {
        
        public function resetcaptchaAction(){
            try {
                $captcha = $this->createCaptcha();
                $response = ResponseUtils::createSingleFetchResponse($captcha, array());
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function validatecaptchaAction(){
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\Captcha", "json");

                $isValid = $this->isValid($data);
                $response = ResponseUtils::createSingleFetchResponse($isValid, array());
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function sendemailrequestAction(){
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\EmailRequest", "json");

                $emailSvcRepo = $this->getServiceLocator()->get('GMailSvc');
                $emailSvcRepo->sendEmailRequest($data);
                
                $response = ResponseUtils::createResponse();
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        
    }
}