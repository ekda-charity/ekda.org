<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Canonicals\Response\ResponseUtils;
    use Application\API\Repositories\Interfaces\IQurbaniMailingService;
    use Application\API\Repositories\Interfaces\IQurbaniRepository;

    class QurbaniApiController extends BaseController {
        
        /**
         * @var IQurbaniMailingService 
         */
        private $gMailSvc;
        
        /**
         * @var IQurbaniRepository 
         */
        private $qurbaniRepo;

        /**
         * @var string
         */
        private $domainName;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IQurbaniRepository $qurbaniRepo, IQurbaniMailingService $gMailSvc, $domainName) {
            parent::__construct($navService, $authService, $serializer);
            $this->gMailSvc = $gMailSvc;
            $this->qurbaniRepo = $qurbaniRepo;
            $this->domainName = $domainName;
        }
        
        public function togglequrbanivoidAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $qurbaniKey = $this->params()->fromRoute('p1');
                $donation = $this->qurbaniRepo->toggleQurbaniVoid($qurbaniKey);
                
                $response = ResponseUtils::createWriteResponse($donation);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function sendqurbanicompletealertAction() {
            try {
                
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $qurbanikey = $this->params()->fromRoute('p1');
                $qurbani = $this->qurbaniRepo->getQurbani($qurbanikey);
                
                if ($qurbani != null && !$qurbani->getIscomplete()) {
                    $this->gMailSvc->qurbaniCompleteAlert($qurbani);
                    $qurbani->setIscomplete(1);
                    $this->qurbaniRepo->updateQurbani($qurbani);
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

                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Qurbani", "json");
                
                $donation = $this->qurbaniRepo->updateQurbani($data);
                
                $response = ResponseUtils::createWriteResponse($donation);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function searchqurbaniAction() {
            try {

                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $purchasedonly = $this->params()->fromRoute('p1');
                $includevoid = $this->params()->fromRoute('p2');
                
                $search = $this->qurbaniRepo->search(0, PHP_INT_MAX, $purchasedonly, $includevoid);
                $details = $this->qurbaniRepo->getQurbaniDetails();
                $stock = $this->qurbaniRepo->getStock();
                
                if (!$search->success) {
                    $response = ResponseUtils::createResponse($search->errors);
                } else {
                    $response = ResponseUtils::createSingleFetchResponse([
                        'search' => $search,
                        'details' => $details,
                        'stock' => $stock
                    ]);
                }
                
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function downloadqurbaniAction() {
            try {

                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $purchasedonly = $this->params()->fromRoute('p1');
                $includevoid = $this->params()->fromRoute('p2');
                
                $objPHPExcel = $this->qurbaniRepo->getQurbaniExcel(0, PHP_INT_MAX, $purchasedonly, $includevoid);
                $details = $this->qurbaniRepo->getQurbaniDetails();
                $name = $details->qurbanimonth . ".xlsx";
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $name . '"');
                header('Cache-Control: max-age=0');
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                exit;
                
            } catch (\Exception $ex) {
                $this->addFlashErrorMsgs([$ex->getMessage()]);
                return $this->redirect("/Admin/qurbani");
            }
        }
        
        public function getstockAction(){
            try {
                $stock = $this->qurbaniRepo->getStock();
                
                $response = ResponseUtils::createSingleFetchResponse($stock);
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function checkstockanddonateAction(){
            try {

                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }

                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Qurbani", "json");

                $qurbanikey = $this->qurbaniRepo->checkStockAndAddQurbani($data, true);
                
                if ($data->getEmail() != null) {
                    $qurbani = $this->qurbaniRepo->getQurbani($qurbanikey);
                    $this->gMailSvc->qurbaniConfrimationAlert($qurbani);
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

                $qurbaniDetails = $this->qurbaniRepo->getQurbaniDetails();
                $qurbanikey = $this->qurbaniRepo->checkStockAndAddQurbani($data);
                
                $shortUrl = $qurbaniDetails->shorturl;
                $amount = $data->getTotal();
                $exitUrl = "http://$this->domainName/api/QurbaniApi/confirmdonation/JUSTGIVING-DONATION-ID/$qurbanikey";
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
                
                $qurbani = $this->qurbaniRepo->confirmDonation($qurbanikey, $donationId);

                if ($qurbani->getEmail() != null) {
                    $this->gMailSvc->qurbaniConfrimationAlert($qurbani);
                }
                
                $donation = $qurbani->getSheep() . " sheep, " . $qurbani->getCows() . " cows, " . $qurbani->getCamels() . " camels";
                $this->flashMessenger()->addSuccessMessage("Your Donation of $donation completed Successfully. Thank you for your Generosity.");
                return $this->redirect()->toUrl("/");
                
            } catch (\Exception $ex) {
                $this->flashMessenger()->addErrorMessage("There was a problem with your donation: " . $ex->getMessage());
                return $this->redirect()->toUrl("/");
            }
        }
    }
}