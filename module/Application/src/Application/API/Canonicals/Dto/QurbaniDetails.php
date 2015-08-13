<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class QurbaniDetails { 
        
        /**
         * @Type("integer")
         */
        public $sheepcost;
        
        /**
         * @Type("integer")
         */
        public $cowcost;
        
        /**
         * @Type("integer")
         */
        public $camelcost;
        
        /**
         * @Type("integer")
         */
        public $totalsheep;
        
        /**
         * @Type("integer")
         */
        public $totalcows;
        
        /**
         * @Type("integer")
         */
        public $totalcamels;
        
        /**
         * @Type("string")
         */
        public $shorturl;
        
        /**
         * @Type("string")
         */
        public $qurbaniyear;
    }
}
