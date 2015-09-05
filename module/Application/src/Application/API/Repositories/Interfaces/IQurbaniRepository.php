<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Qurbani;
    
    interface IQurbaniRepository {
        public function toggleQurbaniVoid($qurbanikey);
        public function updateQurbani(Qurbani $qurbani);
        public function validateRequest(Qurbani $qurbani);
        public function checkStockAndAddQurbani(Qurbani $qurbani, $confirmDonation = false);
        public function confirmDonation($qurbanikey, $donationId);
        public function getQurbaniDetails();
        public function getStock();
        public function getPurchasedSheep();
        public function getPurchasedCows();
        public function getPurchasedCamels();
        public function search($page = 0, $pageSize = 25, $purchasedOnly = true, $includeVoid = false);
    }
}
