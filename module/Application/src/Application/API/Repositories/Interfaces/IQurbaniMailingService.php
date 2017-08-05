<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Qurbani;
    
    interface IQurbaniMailingService {
        public function qurbaniConfrimationAlert(Qurbani $qurbani);
        public function qurbaniCompleteAlert(Qurbani $qurbani);
        public function qurbaniAlert(Qurbani $qurbani, $alertType);
    }
}
