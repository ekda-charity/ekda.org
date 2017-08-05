<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class ThirdPartyQurbaniEmailTransport { 
        
        /**
         * @Type("string")
         */
        public $mailapikey = null;
        
        /**
         * @Type("string")
         */
        public $emailtype = null;

        /**
         * @Type("Application\API\Canonicals\Entity\Qurbani")
         */
        public $qurbani;
    }
}
