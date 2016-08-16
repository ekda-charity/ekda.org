<?php

namespace Application\Controller {
    
    use Application\API\Canonicals\Response\ResponseUtils;

    class QurbaniApiController extends BaseController {
        
        public function togglequrbanivoidAction() {
            try {
                $authService = $this->getServiceLocator()->get('AdminAuthService');

                if (!$authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $qurbaniKey = $this->params()->fromRoute('p1');
                $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');
                $donation = $qurbaniRepo->toggleQurbaniVoid($qurbaniKey);
                
                $response = ResponseUtils::createWriteResponse($donation);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function sendqurbanicompletealertAction() {
            try {
                $authService = $this->getServiceLocator()->get('AdminAuthService');

                if (!$authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $qurbanikey = $this->params()->fromRoute('p1');
                
                $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');
                $gMailSvc = $this->getServiceLocator()->get('GMailSvc');

                $qurbani = $qurbaniRepo->getQurbani($qurbanikey);
                
                if ($qurbani != null && !$qurbani->getIscomplete()) {
                    $gMailSvc->qurbaniCompleteAlert($qurbani);
                    $qurbani->setIscomplete(1);
                    $qurbaniRepo->updateQurbani($qurbani);
                }
                
                $response = ResponseUtils::createResponse();
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function updatequrbaniAction() {
            try {
                $authService = $this->getServiceLocator()->get('AdminAuthService');

                if (!$authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Qurbani", "json");
                
                $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');
                $donation = $qurbaniRepo->updateQurbani($data);
                
                $response = ResponseUtils::createWriteResponse($donation);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function searchqurbaniAction() {
            try {
                $authService = $this->getServiceLocator()->get('AdminAuthService');

                if (!$authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $purchasedonly = $this->params()->fromRoute('p1');
                $includevoid = $this->params()->fromRoute('p2');
                
                $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');
                
                $search = $qurbaniRepo->search(0, PHP_INT_MAX, $purchasedonly, $includevoid);
                $details = $qurbaniRepo->getQurbaniDetails();
                $stock = $qurbaniRepo->getStock();
                
                return $this->jsonResponse(array(
                    'search' => $search,
                    'details' => $details,
                    'stock' => $stock
                ));
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function getstockAction(){
            try {
                $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');
                $stock = $qurbaniRepo->getStock();
                
                $response = ResponseUtils::createSingleFetchResponse($stock);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function checkstockanddonateAction(){
            try {
                $authService = $this->getServiceLocator()->get('AdminAuthService');

                if (!$authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Qurbani", "json");

                $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');
                $qurbanikey = $qurbaniRepo->checkStockAndAddQurbani($data, true);
                
                if ($data->getEmail() != null) {
                    $gMailSvc = $this->getServiceLocator()->get('GMailSvc');
                    $gMailSvc->qurbaniConfrimationAlert($qurbanikey);
                }
                
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
                
                $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');
                $qurbani = $qurbaniRepo->confirmDonation($qurbanikey, $donationId);

                if ($qurbani->getEmail() != null) {
                    $gMailSvc = $this->getServiceLocator()->get('GMailSvc');
                    $gMailSvc->qurbaniConfrimationAlert($qurbani->getQurbanikey());
                }
                
                $donation = $qurbani->getSheep() . " sheep, " . $qurbani->getCows() . " cows, " . $qurbani->getCamels() . " camels";
                $this->flashMessenger()->addSuccessMessage("Your Donation of $donation completed Successfully. May Allah reward you generously. Amin.");
                return $this->redirect()->toUrl("/");
                
            } catch (\Exception $ex) {
                $this->flashMessenger()->addErrorMessage("There was a problem with your donation: " . $ex->getMessage());
                return $this->redirect()->toUrl("/");
            }
        }
    }
}