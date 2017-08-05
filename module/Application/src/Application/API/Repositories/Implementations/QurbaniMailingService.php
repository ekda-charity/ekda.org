<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Application\API\Repositories\Interfaces\IEMailService,
        Application\API\Repositories\Interfaces\IQurbaniMailingService,
        Application\API\Canonicals\Dto\EmailRequest,
        Application\API\Canonicals\Dto\QurbaniAlertTypes,
        Application\API\Canonicals\Entity\Qurbani;

    class QurbaniMailingService extends BaseRepository implements IQurbaniMailingService {
        
        private $emailRepository;
        private $qurbaniDetails;
        private $env;
        private $subjectSuffix;
        private $domainPath;
        
        public function __construct(EntityManager $em, IEMailService $emailRepository, $qurbaniDetails, $domainName, $env) {
            parent::__construct($em);
            $this->emailRepository = $emailRepository;
            $this->qurbaniDetails = $qurbaniDetails;
            $this->env = $env;
            $this->subjectSuffix = $this->env == "production" ? "" : strtoupper(" ($this->env ENVIRONMENT)");
            $this->domainPath = ($this->env == "development" ? "http" : "https") . "://" . $domainName;
        }
        
        public function qurbaniConfrimationAlert(Qurbani $qurbani) {
            
            $template = new TemplateEngine("data/templates/qurbani-confirmation.phtml", [
                'title' => "Confirmation",
                'domainPath' => $this->domainPath,
                'instructions' => $qurbani->getInstructions(),
                'noOfSheep' => $qurbani->getSheep(),
                'noOfCows' => $qurbani->getCows(),
                'noOfCamels' => $qurbani->getCamels(),
                'total' => $qurbani->getTotal(),
                'costOfSheep' => $qurbani->getSheep() * $this->qurbaniDetails["sheepcost"],
                'costOfCows' => $qurbani->getCows() * $this->qurbaniDetails["cowcost"],
                'costOfCamels' => $qurbani->getCamels() * $this->qurbaniDetails["camelcost"],
            ]);
                            
            $request = new EmailRequest();
            $request->recipient = $qurbani->getEmail();
            $request->subject = "Your Qurbani Donation" . $this->subjectSuffix;
            $request->htmlbody = $template->render();
            
            $this->emailRepository->sendMail($request);
        }

        public function qurbaniCompleteAlert(Qurbani $qurbani) {
            if (!$qurbani->getIscomplete()) {
                $template = new TemplateEngine("data/templates/qurbani-complete.phtml", [
                    'title' => "Confirmation",
                    'domainPath' => $this->domainPath,
                    'instructions' => $qurbani->getInstructions(),
                    'noOfSheep' => $qurbani->getSheep(),
                    'noOfCows' => $qurbani->getCows(),
                    'noOfCamels' => $qurbani->getCamels(),
                    'total' => $qurbani->getTotal(),
                    'costOfSheep' => $qurbani->getSheep() * $this->qurbaniDetails["sheepcost"],
                    'costOfCows' => $qurbani->getCows() * $this->qurbaniDetails["cowcost"],
                    'costOfCamels' => $qurbani->getCamels() * $this->qurbaniDetails["camelcost"],
                ]);

                $request = new EmailRequest();
                $request->recipient = $qurbani->getEmail();
                $request->subject = "Your Qurbani has been done" . $this->subjectSuffix;
                $request->htmlbody = $template->render();

                $this->emailRepository->sendMail($request);
            }
        }

        public function qurbaniAlert(Qurbani $qurbani, $alertType) {
            if ($alertType == QurbaniAlertTypes::Complete) {
                $this->qurbaniCompleteAlert($qurbani);
            } else if ($alertType == QurbaniAlertTypes::Received) {
                $this->qurbaniConfrimationAlert($qurbani);
            }
        }
    }
}
