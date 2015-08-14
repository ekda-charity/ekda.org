<?php

namespace Application\Controller {
    
    use Zend\Captcha;
    use Zend\Mvc\Controller\AbstractActionController;
    use JMS\Serializer\SerializerBuilder;
    use JMS\Serializer\SerializationContext;
    use Application\API\Canonicals\Dto\Captcha as LocalCaptcha;
    use Application\API\Canonicals\Entity\Ads;
    use Zend\EventManager\EventManagerInterface;
    use Application\API\Canonicals\Entity\Respondents;

    class BaseController extends AbstractActionController {

        protected $serializer;
        
        public function __construct() {
            $this->serializer = SerializerBuilder::create()->build();;
        }
        
        protected function addFlashErrorMsgs($messages) {
            foreach($messages as $message) {
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        
        protected function addFlashSuccessMsgs($messages) {
            foreach($messages as $message) {
                $this->flashMessenger()->addSuccessMessage($message);
            }
        }
        
        protected function addFlashInfoMsgs($messages) {
            foreach($messages as $message) {
                $this->flashMessenger()->addInfoMessage($message);
            }
        }
        
        protected function jsonResponse($data) {
            $context = new SerializationContext();
            $context->setSerializeNull(true);
            $content = $this->serializer->serialize($data, 'json', $context);
            
            $this->response->setContent($content);
            return $this->response;
        }
        
        protected function isValid(LocalCaptcha $captchaRequest) {
            $captcha= new Captcha\Dumb();
            $captcha->setName($captchaRequest->captchaname);

            $captchaData['id'] = $captchaRequest->captchatoken;
            $captchaData['input'] = $captchaRequest->captcharesponse;
            
            return $captcha->isValid($captchaData);
        }
        
        protected function createCaptcha() {
            $captcha = new Captcha\Dumb();
            $captchaname = uniqid();
            $captcha->setName($captchaname);
            $captcha->setWordlen(3);

            $token = $captcha->generate();
            $word  = $captcha->getWord();

            $returnObj = new LocalCaptcha();
            $returnObj->captchaname = $captchaname;
            $returnObj->captchatoken = $token;
            $returnObj->captchaword = strrev($word);
            
            return $returnObj;
        }
        
        protected function createAdsHash(Ads $ad) {
            return md5($ad->getAdkey() * sqrt(30 + 1));
        }
        
        protected function createRespondentsHash(Respondents $ad) {
            return md5($ad->getRespondentkey() * sqrt(30 + 2));
        }
        
        protected function privatizeAd(Ads $ad) {
            if (!$ad->getExposemycontacts()) {
                $ad->setEmail(null);
                $ad->setPhone(null);
            }
            
            $ad->setPassword(null);
        }
        
        protected function privatizeRespondent(Respondents $respondent) {
            $respondent->setPassword(null);
            $respondent->setEmail(null);
        }
        
        public function setEventManager(EventManagerInterface $events) {
            parent::setEventManager($events);
            
            $thisPtr = $this;
            $events->attach('dispatch', function ($e) use ($thisPtr) {
                
            }, 100);
            
            return $this;
        }        
        
        
    }
}

