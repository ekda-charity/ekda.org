<?php

namespace Application\API\Canonicals\Dto {
    
    class Email { 
        
        private $username;
        private $password;
        
        public function __construct($username, $password) {
            $this->username = $username;
            $this->password = $password;
        }
        
        public function getUsername() { return $this->username; }
        public function setUsername($val) { $this->username = $val; }
        
        public function getPassword() { return $this->password; }
        public function setPassword($val) { $this->password = $val; }
    }
}
