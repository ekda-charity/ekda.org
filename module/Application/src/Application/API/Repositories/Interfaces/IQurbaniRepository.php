<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Qurbani;
    
    interface IQurbaniRepository {
        public function checkStockAndAddQurbani(Qurbani $qurbani);
        public function confirmDonation($qurbanikey, $donationId);
        public function getQurbaniDetails();
        public function getPurchasedSheep();
        public function getPurchasedCows();
        public function getPurchasedCamels();
    }
}
