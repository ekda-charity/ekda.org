<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Qurbani;
    
    interface IQurbaniRepository {
        public function checkStockAndAddQurbani(Qurbani $qurbani, $confirmDonation = false);
        public function confirmDonation($qurbanikey, $donationId);
        public function getQurbaniDetails();
        public function getPurchasedSheep();
        public function getPurchasedCows();
        public function getPurchasedCamels();
        public function search($page = 0, $pageSize = 25, $purchasedOnly = true);
    }
}
