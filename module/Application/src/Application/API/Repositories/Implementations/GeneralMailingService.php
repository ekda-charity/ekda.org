<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Application\API\Repositories\Interfaces\IEMailService,
        Application\API\Repositories\Interfaces\IGeneralMailingService,
        Application\API\Canonicals\Dto\Email,
        Application\API\Canonicals\Dto\EmailRequest;

    class GeneralMailingService extends BaseRepository implements IGeneralMailingService {
        
        private $emailRepository;
        private $defaultSender;
        private $supportEmail;
        private $imamsEmail;
        private $domainName;
        private $isNotProduction;
        
        public function __construct(EntityManager $em, IEMailService $emailRepository, $defaultSender, $supportEmail, $imamsEmail, $domainName) {
            parent::__construct($em);
            $this->emailRepository = $emailRepository;
            $this->defaultSender = new Email($defaultSender['username'], $defaultSender['password']);
            $this->supportEmail = $supportEmail['username'];
            $this->imamsEmail = $imamsEmail['username'];
            $this->domainName = $domainName;
            
            $env = (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : (getenv('REDIRECT_APPLICATION_ENV') ? getenv('REDIRECT_APPLICATION_ENV') : 'development'));
            $this->isNotProduction = ($env !== "production");
        }
        
        public function sendEmailRequest(EmailRequest $request) {
            $email = $this->validEmailRecipientsRepo->repository->findOneBy(
                array("email" => $request->recipient)
            );
            
            if ($email == null) {
                throw new \Exception("Could not find a matching recipient");
            } else {
                $this->emailRepository->sendMail($this->defaultSender, $request->recipient, $request->subject, $request->textbody, $request->htmlbody);
            }
        }
    }
}
