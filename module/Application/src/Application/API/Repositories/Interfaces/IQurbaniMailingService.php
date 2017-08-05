<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Qurbani;
    
    interface IQurbaniMailingService {
        public function qurbaniConfrimationAlert($qurbaniKey);
        public function qurbaniCompleteAlert(Qurbani $qurbani);
    }
}
