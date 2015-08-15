<?php

namespace Application\Controller {
    
    use Application\API\Canonicals\Response\ResponseUtils;

    class QurbaniApiController extends BaseController {
        
        public function checkstockanddonateAction(){
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Qurbani", "json");

                $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');
                $qurbaniRepo->checkStockAndAddQurbani($data, true);
                
                $response = ResponseUtils::createResponse();
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function checkstockandinitiatedonationAction(){
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Qurbani", "json");

                $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');
                $config = $this->getServiceLocator()->get('Config');

                $domainname = $config["DomainName"];
                $qurbaniDetails = $qurbaniRepo->getQurbaniDetails();
                $qurbanikey = $qurbaniRepo->checkStockAndAddQurbani($data);
                
                $shortUrl = $qurbaniDetails->shorturl;
                $amount = $data->getTotal();
                $exitUrl = "http://$domainname/api/QurbaniApi/confirmdonation/JUSTGIVING-DONATION-ID/$qurbanikey";
                $redirectUrl = "http://www.justgiving.com/$shortUrl/4w350m3/donate?amount=$amount&exitUrl=$exitUrl";

                $response = ResponseUtils::createSingleFetchResponse($redirectUrl);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function confirmdonationAction() {
            try {
                $donationId = $this->params()->fromRoute('p1');
                $qurbanikey = $this->params()->fromRoute('p2');
                $exitDomainname = $this->params()->fromRoute('p3');
                
                $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');
                $qurbani = $qurbaniRepo->confirmDonation($qurbanikey, $donationId);
                
                if (isset($exitDomainname)) {
                    return $this->redirect()->toUrl("http://$exitDomainname");
                } else {
                    $donation = $qurbani->getSheep() . " sheep, " . $qurbani->getCows() . " cows, " . $qurbani->getCamels() . " camels";
                    $this->flashMessenger()->addSuccessMessage("Your Donation of $donation completed Successfully. May Allah reward you generously. Amin.");
                    return $this->redirect()->toUrl("/");
                }
                
            } catch (\Exception $ex) {
                $this->flashMessenger()->addErrorMessage("There was a problem with your donation: " . $ex->getMessage());
                return $this->redirect()->toUrl("/");
            }
        }
    }
}