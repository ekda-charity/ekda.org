<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Qurbani;
    
    interface IGeneralMailingService {
        public function qurbaniConfrimationAlert($qurbaniKey);
        public function qurbaniCompleteAlert(Qurbani $qurbani);
    }
}
