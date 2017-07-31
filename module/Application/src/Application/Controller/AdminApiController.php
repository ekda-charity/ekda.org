<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Repositories\Interfaces\IUsersRepository;
    use Application\API\Canonicals\Response\ResponseUtils;

    class AdminApiController extends BaseController {
        
        /**
         * @var IUsersRepository
         */
        private $usersRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IUsersRepository $usersRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->usersRepo = $usersRepo;
        }
        
        public function loginAction(){
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Users", "json");
                
                $this->authService->getAdapter()->setIdentity($data->getUsername())->setCredential($data->getPassword());
                $result = $this->authService->authenticate();
                
                $user = $this->usersRepo->find($data->getUsername());

                if (!$result->isValid() && $user == null) {
                    throw new \Exception("Could not find a matching Record");
                } else if (!$result->isValid() && $user != null) {
                    $user->setTries($user->getTries() + 1);
                    $this->usersRepo->updateUser($user, $user->getPassword());
                    throw new \Exception("Could not find a matching Record");
                } else if ($user->getTries() >= 3) {
                    throw new \Exception("Sorry this account has been locked.");
                } else {
                    $user->setTries(0);
                    $this->usersRepo->updateUser($user, $user->getPassword());
                    
                    $this->authService->getStorage()->write($data->getUsername());
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

                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Users", "json");
                
                $data->setPassword(md5($data->getPassword()));
                $this->usersRepo->addUser($data);
                
                $response = ResponseUtils::createWriteResponse(array(
                    'users' => $this->usersRepo->findAll()
                ));
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
            
        }
        
        public function updateuserAction() {
            try {
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                }
                
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Dto\UserUpdate", "json");
                
                $data->user->setPassword(md5($data->user->getPassword()));
                $this->usersRepo->updateUser($data->user, $data->oldpassword);
                
                $response = ResponseUtils::createWriteResponse(array(
                    'users' => $this->usersRepo->findAll()
                ));
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
            
        }
        
        public function deleteuserAction() {
            try {
                $jsonData = $this->getRequest()->getContent();
                $data = $this->serializer->deserialize($jsonData, "Application\API\Canonicals\Entity\Users", "json");
                
                if (!$this->authService->hasIdentity()) {
                    throw new \Exception("Unauthorized Access");
                } else if ($this->authService->getIdentity() == $data->getUsername()) {
                    throw new \Exception("Cannot Delete Current User");
                }
                
                $this->usersRepo->deleteUser($data->getUsername(), $data->getPassword());
                
                $response = ResponseUtils::createWriteResponse(array(
                    'users' => $this->usersRepo->findAll()
                ));
                return $this->jsonResponse($response);
                
            } catch (\Exception $ex) {
                $response = ResponseUtils::createExceptionResponse($ex);
                return $this->jsonResponse($response);
            }
        }
    }
}