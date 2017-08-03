<?php

namespace Application\API\Repositories\Implementations {
    
    class TemplateEngine {
        
        private $file;
        private $data = array();
        
        public function __construct($file, array $data) {
            $this->file = $file;
            $this->data = $data;
        }
        
        public function render() {
            extract($this->data);
            ob_start();
            include($this->file);
            return ob_get_clean();            
        }
    }
}
