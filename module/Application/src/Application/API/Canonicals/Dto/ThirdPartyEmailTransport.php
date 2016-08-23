<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class ThirdPartyEmailTransport { 
        
        /**
         * @Type("string")
         */
        public $mailapikey = null;

        /**
         * @Type("array<integer>")
         */
        public $emailkeys = [];
    }
}
