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
        private $domainName;
        private $isNotProduction;
        
        public function __construct(EntityManager $em, IEMailService $emailRepository, $defaultSender, $supportEmail, $domainName) {
            parent::__construct($em);
            $this->emailRepository = $emailRepository;
            $this->defaultSender = new Email($defaultSender['username'], $defaultSender['password']);
            $this->supportEmail = $supportEmail['username'];
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
        
        public function qurbaniConfrimationAlert($qurbaniKey) {
            $qurbani = $this->qurbaniRepo->fetch($qurbaniKey);

            $subject = "Your Qurbani Donation to East Africa";
            $htmlBody = "
                <html>
                <head></head>
                <body>
                <p>
                Salam Aleikum " . $qurbani->getFullname() . ",<br/>
                This is to confirm that we have received your Qurbani Donation to East Africa as follows:
                <ul>
                <li><strong>Sheep:</strong> " . $qurbani->getSheep() . "</li>
                <li><strong>Cows:</strong> " . $qurbani->getCows() . "</li>
                <li><strong>Camels:</strong> " . $qurbani->getCamels() . "</li>
                <li><strong>Instructions:</strong> " . $qurbani->getInstructions() . "</li>
                </ul>
                Inshaa Allah we will send you a Confirmation once the Sacrifice has been performed.<br/>
                <br/>
                Shukran<br/>
                <br/>
                Rahman Mukras<br/>
                Secretary, EKDA<br/>
                11B Sunnybank Road<br/>
                Aberdeen, United Kingdom<br/>
                AB15 3NJ<br/>
                www.ekda.org<br/>
                Scottish Charity Reg No: SC041294<br/>
                </p>
                </body>
                </html>
            ";

            $recipients = $this->isNotProduction ? $this->supportEmail : $qurbani->getEmail();
            $this->emailRepository->sendMail($this->defaultSender, $recipients, $subject, null, $htmlBody);
        }
    }
}
