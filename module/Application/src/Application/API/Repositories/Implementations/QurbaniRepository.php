<?php

namespace Application\API\Repositories\Implementations {
    
    use Doctrine\ORM\EntityManager,
        Application\API\Repositories\Interfaces\IQurbaniRepository,
        Application\API\Canonicals\Entity\Qurbani,
        Application\API\Canonicals\Dto\QurbaniDetails,
        Application\API\Canonicals\Response\ResponseUtils;
    
    class QurbaniRepository extends BaseRepository implements IQurbaniRepository {

        const CNT = "1";
        const RST = "2";
        const DONATION_ID = "MANUAL";
        
        private $details;

        public function __construct(EntityManager $em, $qurbaniDetails) {
            parent::__construct($em);
            $this->details = new QurbaniDetails();
            $this->details->qurbaniseason = $qurbaniDetails["qurbaniseason"];
            $this->details->sheepcost = $qurbaniDetails["sheepcost"];
            $this->details->cowcost = $qurbaniDetails["cowcost"];
            $this->details->camelcost = $qurbaniDetails["camelcost"];
            $this->details->totalsheep = $qurbaniDetails["totalsheep"];
            $this->details->totalcows = $qurbaniDetails["totalcows"];
            $this->details->totalcamels = $qurbaniDetails["totalcamels"];
            $this->details->shorturl = $qurbaniDetails["shorturl"];
            $this->details->qurbanimonth = $qurbaniDetails["qurbanimonth"];
            $this->details->disableinstructionsdate = $qurbaniDetails["disableinstructionsdate"];
            $this->details->closingdate = $qurbaniDetails["closingdate"];
        }

        public function getQurbani($qurbanikey) {
            return $this->qurbaniRepo->fetch($qurbanikey);
        }
        
        public function toggleQurbaniVoid($qurbanikey) {
            $repo = $this->qurbaniRepo->repository;
            $qurbani = $repo->find($qurbanikey);
            
            if ($qurbani == null) {
                throw new \Exception("Donation Could not be found");
            }

            $dummy = new Qurbani($qurbani);
            $dummy->setIsvoid($dummy->getIsvoid() == 1 ? 0 : 1);
            $errors = $this->validateRequest($dummy);
            
            if (count($errors) > 0) {
                throw new \Exception(implode(", ", $errors));
            }
            
            $this->em->transactional(function(EntityManager $em) use($qurbani) {
                $qurbani->setIsvoid($qurbani->getIsvoid() == 1 ? 0 : 1);
                $em->merge($qurbani);
            }); 
            
            return $repo->find($qurbanikey);
        }

        public function updateQurbani(Qurbani $qurbani) {
            $errors = $this->validateRequest($qurbani);

            if (count($errors) > 0) {
                throw new \Exception(implode(", ", $errors));
            }

            $repo = $this->qurbaniRepo->repository;
            $this->em->transactional(function(EntityManager $em) use($qurbani, $repo) {
                $oneRecord = $repo->find($qurbani->getQurbanikey());

                if ($oneRecord == null) {
                    throw new \Exception("Donation Could not be found");
                } else {
                    $em->merge($qurbani);
                }
            }); 
            
            return $repo->find($qurbani->getQurbanikey());
        }

        public function validateRequest(Qurbani $qurbani) {
            $errors = [];
       
            if ($qurbani->getSheep() < 0) {
                $errors[] = "Invalid Number of Sheep";
            }
            
            if ($qurbani->getCows() < 0) {
                $errors[] = "Invalid Number of Cows";
            }
            
            if ($qurbani->getCamels() < 0) {
                $errors[] = "Invalid Number of Camels";
            }
            
            if ($qurbani->getSheep() + $qurbani->getCows() + $qurbani->getCamels() <= 0) {
                $errors[] = "At least one animal is required";
            }
            
            if (($qurbani->getDonationid() != null || $qurbani->getQurbanikey() == null) && !$qurbani->getIsvoid()) {
                
                $sheep = $qurbani->getSheep();
                $cows = $qurbani->getCows();
                $camels = $qurbani->getCamels();

                if ($qurbani->getQurbanikey() != null) {
                    $current = $this->qurbaniRepo->fetch($qurbani->getQurbanikey());

                    if ($current == null) {
                        $errors[] = "Could not find Qurbani Donation";
                    } else {
                        if ($current->getQurbanimonth() != $this->details->qurbanimonth) {
                            $errors[] = "Invalid Qurbani Month";
                        }

                        if ($current->getDonationid() != null && !$current->getIsvoid()) {
                            $sheep -= $current->getSheep();
                            $cows -= $current->getCows();
                            $camels -= $current->getCamels();
                        }
                    }
                }

                $sheepLeft  = $this->details->totalsheep  - $this->getPurchasedSheep();
                $cowsLeft   = $this->details->totalcows   - $this->getPurchasedCows();
                $camelsLeft = $this->details->totalcamels - $this->getPurchasedCamels();

                if($sheep > $sheepLeft) {
                    $errors[] = "Only $sheepLeft Sheep left";
                }

                if($cows > $cowsLeft) {
                    $errors[] = "Only $cowsLeft Cows left";
                }

                if ($camels > $camelsLeft) {
                    $errors[] = "Only $camelsLeft Camels left";
                }
            }
            
            return $errors;
        }
        
        public function checkStockAndAddQurbani(Qurbani $qurbani, $confirmDonation = false) {
            $errors = $this->validateRequest($qurbani);
            
            if (count($errors) > 0) {
                throw new \Exception(implode(", ", $errors));
            }

            $qurbani->setDonationid($confirmDonation ? QurbaniRepository::DONATION_ID : null);
            $qurbani->setQurbanimonth($this->details->qurbanimonth);
            $qurbani->setIsvoid(0);
            $qurbani->setIscomplete(0);
            $qurbani->setCreateddate(new \DateTime("now", new \DateTimeZone("UTC")));
            
            $this->em->transactional(function(EntityManager $em) use($qurbani) {
                $em->persist($qurbani);
            });
            
            return $qurbani->getQurbanikey();
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
            
            return $repo->find($qurbanikey);
        }

        public function getQurbaniDetails() {
            return $this->details;
        }

        public function getStock() {
            $sheepLeft  = $this->details->totalsheep  - $this->getPurchasedSheep();
            $cowsLeft   = $this->details->totalcows   - $this->getPurchasedCows();
            $camelsLeft = $this->details->totalcamels - $this->getPurchasedCamels();
            
            return array(
                'sheep'  => $sheepLeft,
                'cows'   => $cowsLeft,
                'camels' => $camelsLeft
            );
        }
        
        public function getPurchasedCamels() {
            $dql = "SELECT SUM(q.camels) AS animals FROM Application\API\Canonicals\Entity\Qurbani q " .
                   "WHERE q.qurbanimonth = ?1 AND q.donationid IS NOT NULL AND q.isvoid = 0";
            
            return $this->em->createQuery($dql)
                    ->setParameter(1, $this->details->qurbanimonth)
                    ->getSingleScalarResult();            
        }

        public function getPurchasedCows() {
            $dql = "SELECT SUM(q.cows) AS animals FROM Application\API\Canonicals\Entity\Qurbani q " .
                   "WHERE q.qurbanimonth = ?1 AND q.donationid IS NOT NULL AND q.isvoid = 0";
            
            return $this->em->createQuery($dql)
                    ->setParameter(1, $this->details->qurbanimonth)
                    ->getSingleScalarResult();            
        }

        public function getPurchasedSheep() {
            $dql = "SELECT SUM(q.sheep) AS animals FROM Application\API\Canonicals\Entity\Qurbani q " .
                   "WHERE q.qurbanimonth = ?1 AND q.donationid IS NOT NULL AND q.isvoid = 0";
            
            return $this->em->createQuery($dql)
                    ->setParameter(1, $this->details->qurbanimonth)
                    ->getSingleScalarResult();            
        }

        public function search($page = 0, $pageSize = 25, $purchasedOnly = true, $includeVoid = false) {
            $errors = array();
            $total = 0;
            $items = null;
            
            try {
                
                $query = array();

                foreach(array(QurbaniRepository::CNT, QurbaniRepository::RST) as $index) {
                    $query[$index] = $this->qurbaniRepo->repository->createQueryBuilder("q")
                            ->where("q.qurbanimonth = :pQurbanimonth")->setParameter("pQurbanimonth", $this->details->qurbanimonth);
                    
                    if ($purchasedOnly) {
                        $query[$index] = $query[$index]->andWhere("q.donationid IS NOT NULL");
                    }
                    
                    if (!$includeVoid) {
                        $query[$index] = $query[$index]->andWhere("q.isvoid = 0");
                    }
                    
                    $query[$index] = $query[$index]->orderBy("q.createddate", "DESC");

                    if ($index == QurbaniRepository::CNT) {
                        $query[$index] = $query[$index]->select("COUNT(q.qurbanikey)");
                    }
                }
                
                $total = $query[QurbaniRepository::CNT]->getQuery()->getSingleScalarResult();
                $items = $query[QurbaniRepository::RST]->setFirstResult($page * $pageSize)->setMaxResults($pageSize)->getQuery()->getResult();
                
            } catch (\Exception $ex) {
                array_push($errors, $ex->getMessage());
            }
            
            return ResponseUtils::createSearchResponse($total, $items, $page, $pageSize, $errors);            
        }
        
        public function getQurbaniExcel($page = 0, $pageSize = 25, $purchasedOnly = true, $includeVoid = false) {
            $qurbani = $this->search($page, $pageSize, $purchasedOnly, $includeVoid);
            
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle($this->details->qurbanimonth);

            $row = 1;
            $objPHPExcel->getActiveSheet()->SetCellValue("A$row", 'Month');
            $objPHPExcel->getActiveSheet()->SetCellValue("B$row", 'Sheep');
            $objPHPExcel->getActiveSheet()->SetCellValue("C$row", 'Cows');
            $objPHPExcel->getActiveSheet()->SetCellValue("D$row", 'Camels');
            $objPHPExcel->getActiveSheet()->SetCellValue("E$row", 'Total');
            $objPHPExcel->getActiveSheet()->SetCellValue("F$row", 'Fullname');
            $objPHPExcel->getActiveSheet()->SetCellValue("G$row", 'Email');
            $objPHPExcel->getActiveSheet()->SetCellValue("H$row", 'Mobile');
            $objPHPExcel->getActiveSheet()->SetCellValue("I$row", 'Instructions');
            $objPHPExcel->getActiveSheet()->SetCellValue("J$row", 'DonationID');
            $objPHPExcel->getActiveSheet()->SetCellValue("K$row", 'Date');
            
            foreach($qurbani->items as $pr) {
                $row++;
                $objPHPExcel->getActiveSheet()->SetCellValue("A$row", $pr->getQurbanimonth());
                $objPHPExcel->getActiveSheet()->SetCellValue("B$row", $pr->getSheep());
                $objPHPExcel->getActiveSheet()->SetCellValue("C$row", $pr->getCows());
                $objPHPExcel->getActiveSheet()->SetCellValue("D$row", $pr->getCamels());
                $objPHPExcel->getActiveSheet()->SetCellValue("E$row", $pr->getTotal());
                $objPHPExcel->getActiveSheet()->SetCellValue("F$row", $pr->getFullname());
                $objPHPExcel->getActiveSheet()->SetCellValue("G$row", $pr->getEmail());
                $objPHPExcel->getActiveSheet()->SetCellValue("H$row", $pr->getMobile());
                $objPHPExcel->getActiveSheet()->SetCellValue("I$row", $pr->getInstructions());
                $objPHPExcel->getActiveSheet()->SetCellValue("J$row", $pr->getDonationid());
                $objPHPExcel->getActiveSheet()->SetCellValue("K$row", $pr->getCreateddate()->format("Y-m-d H:i:s"));
            }
            
            return $objPHPExcel;
        }        
    }
}
