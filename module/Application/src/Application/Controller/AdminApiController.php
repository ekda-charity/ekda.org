<?php

namespace Application\Controller {
    
    use Application\API\Canonicals\Response\ResponseUtils;

    class AdminApiController extends BaseController {
        
        public function loginAction(){
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Users", "json");
                
                $authService = $this->getServiceLocator()->get('AdminAuthService');
                
                $authService->getAdapter()->setIdentity($data->getUsername())->setCredential($data->getPassword());
                $result = $authService->authenticate();
                
                $usersRepo = $this->getServiceLocator()->get('UsersRepo');
                $user = $usersRepo->find($data->getUsername());

                if (!$result->isValid() && $user == null) {
                    throw new \Exception("Could not find a matching Record");
                } else if (!$result->isValid() && $user != null) {
                    $user->setTries($user->getTries() + 1);
                    $usersRepo->updateUser($user, $user->getPassword());
                    throw new \Exception("Could not find a matching Record");
                } else if ($user->getTries() >= 3) {
                    throw new \Exception("Sorry this account has been locked.");
                } else {
                    $user->setTries(0);
                    $usersRepo->updateUser($user, $user->getPassword());
                    
                    $authService->getStorage()->write($data->getUsername());
                    $response = ResponseUtils::createResponse();
                    return $this->jsonResponse($response);
                }
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
        
        public function adduserAction() {
            try {
                $authService = $this->getServiceLocator()->get('AdminAuthService');

                if (!$authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Users", "json");
                
                $usersRepo = $this->getServiceLocator()->get('UsersRepo');
                $data->setPassword(md5($data->getPassword()));
                $usersRepo->addUser($data);
                
                $response = ResponseUtils::createWriteResponse(array(
                    'users' => $usersRepo->findAll()
                ));
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
            
        }
        
        public function updateuserAction() {
            try {
                $authService = $this->getServiceLocator()->get('AdminAuthService');

                if (!$authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\UserUpdate", "json");
                
                $usersRepo = $this->getServiceLocator()->get('UsersRepo');
                $data->user->setPassword(md5($data->user->getPassword()));
                $usersRepo->updateUser($data->user, $data->oldpassword);
                
                $response = ResponseUtils::createWriteResponse(array(
                    'users' => $usersRepo->findAll()
                ));
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
            
        }
        
        public function deleteuserAction() {
            try {
                $authService = $this->getServiceLocator()->get('AdminAuthService');

                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Users", "json");
                
                if (!$authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                } else if ($authService->getIdentity() == $data->getUsername()) {
                    throw new \Exception("Cannot Delete Current User");
                }
                
                $usersRepo = $this->getServiceLocator()->get('UsersRepo');
                $usersRepo->deleteUser($data->getUsername(), $data->getPassword());
                
                $response = ResponseUtils::createWriteResponse(array(
                    'users' => $usersRepo->findAll()
                ));
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}