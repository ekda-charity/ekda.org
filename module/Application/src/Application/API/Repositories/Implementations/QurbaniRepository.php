<?php

namespace Application\API\Repositories\Implementations {
    
    use Doctrine\ORM\EntityManager,
        Application\API\Repositories\Interfaces\IQurbaniRepository,
        Application\API\Canonicals\Entity\Qurbani,
        Application\API\Canonicals\Dto\QurbaniDetails;
    
    class QurbaniRepository extends BaseRepository implements IQurbaniRepository {

        private $details;

        public function __construct(EntityManager $em, $qurbaniDetails) {
            parent::__construct($em);
            $this->details = new QurbaniDetails();
            $this->details->sheepcost = $qurbaniDetails["sheepcost"];
            $this->details->cowcost = $qurbaniDetails["cowcost"];
            $this->details->camelcost = $qurbaniDetails["camelcost"];
            $this->details->totalsheep = $qurbaniDetails["totalsheep"];
            $this->details->totalcows = $qurbaniDetails["totalcows"];
            $this->details->totalcamels = $qurbaniDetails["totalcamels"];
            $this->details->shorturl = $qurbaniDetails["shorturl"];
            $this->details->qurbaniyear = $qurbaniDetails["qurbaniyear"];
        }

        public function checkStockAndAddQurbani(Qurbani $qurbani) {
            $sheepLeft  = $this->details->totalsheep  - $this->getPurchasedSheep();
            $cowsLeft   = $this->details->totalcows   - $this->getPurchasedCows();
            $camelsLeft = $this->details->totalcamels - $this->getPurchasedCamels();
            $errors = [];
       
            if($qurbani->getSheep() > $sheepLeft) {
                $errors[] = "Only $sheepLeft Sheep left";
            }
            
            if($qurbani->getCows() > $cowsLeft) {
                $errors[] = "Only $cowsLeft Cows left";
            }
            
            if ($qurbani->getCamels() > $camelsLeft) {
                $errors[] = "Only $camelsLeft Camels left";
            }
            
            if (count($errors) > 0) {
                throw new \Exception(implode(", ", $errors));
            }
            
            $this->em->transactional(function(EntityManager $em) use($qurbani) {
                $qurbani->setDonationid(null);
                $qurbani->setQurbaniyear($this->details->qurbaniyear);
                $em->persist($qurbani);
                return $qurbani->getQurbanikey();
            });            
        }

        public function confirmDonation($qurbanikey, $donationId) {
            $repo = $this->qurbaniRepo->repository;
            $this->em->transactional(function(EntityManager $em) use($qurbanikey, $donationId, $repo) {

                $oneRecord = $repo->find($qurbanikey);

                if ($oneRecord == null) {
                    throw new \Exception("Donation Could not be found");
                } else if ($oneRecord->getDonationid() != null) {
                    throw new \Exception("Donation has already been confirmed");
                } else {
                    $oneRecord->setDonationid($donationId);
                    $em->merge($oneRecord);
                }
            });            
        }

        public function getQurbaniDetails() {
            return $this->details;
        }

        public function getPurchasedCamels() {
            $dql = "SELECT SUM(q.camels) AS animals FROM Application\API\Canonicals\Entity\Qurbani q " .
                   "WHERE q.qurbaniyear = ?1 AND q.donationid IS NOT NULL";
            
            return $this->em->createQuery($dql)
                    ->setParameter(1, $this->details->qurbaniyear)
                    ->getSingleScalarResult();            
        }

        public function getPurchasedCows() {
            $dql = "SELECT SUM(q.cows) AS animals FROM Application\API\Canonicals\Entity\Qurbani q " .
                   "WHERE q.qurbaniyear = ?1 AND q.donationid IS NOT NULL";
            
            return $this->em->createQuery($dql)
                    ->setParameter(1, $this->details->qurbaniyear)
                    ->getSingleScalarResult();            
        }

        public function getPurchasedSheep() {
            $dql = "SELECT SUM(q.sheep) AS animals FROM Application\API\Canonicals\Entity\Qurbani q " .
                   "WHERE q.qurbaniyear = ?1 AND q.donationid IS NOT NULL";
            
            return $this->em->createQuery($dql)
                    ->setParameter(1, $this->details->qurbaniyear)
                    ->getSingleScalarResult();            
        }

    }
}
