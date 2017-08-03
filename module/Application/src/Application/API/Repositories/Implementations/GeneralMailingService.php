<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Application\API\Repositories\Interfaces\IEMailService,
        Application\API\Repositories\Interfaces\IGeneralMailingService,
        Application\API\Canonicals\Dto\EmailRequest,
        Application\API\Canonicals\Entity\Qurbani;

    class GeneralMailingService extends BaseRepository implements IGeneralMailingService {
        
        private $emailRepository;
        private $qurbaniDetails;
        private $domainPath;
        
        public function __construct(EntityManager $em, IEMailService $emailRepository, $qurbaniDetails, $domainPath) {
            parent::__construct($em);
            $this->emailRepository = $emailRepository;
            $this->qurbaniDetails = $qurbaniDetails;
            $this->domainPath = $domainPath;
        }
        
        public function qurbaniConfrimationAlert($qurbaniKey) {
            $qurbani = $this->qurbaniRepo->fetch($qurbaniKey);
            
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
            $request->subject = "Your Qurbani Donation";
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
                $request->subject = "Your Qurbani has been done";
                $request->htmlbody = $template->render();

                $this->emailRepository->sendMail($request);
            }
        }
    }
}
