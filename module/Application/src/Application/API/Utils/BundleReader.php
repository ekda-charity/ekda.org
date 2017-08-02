<?php

namespace Application\API\Utils {

    use Zend\Json\Json;
    
    class BundleReader { 
        
        private $bundle;
        
        /**
         * @var string
         */
        private $bundleJson;
        
        public function __construct($bundleFile) {
            $this->bundleJson = file_get_contents($bundleFile);
            $this->bundle = Json::decode($this->bundleJson, Json::TYPE_OBJECT);
        }
        
        private function getFiles($bundleItem) {
            $list = [];

            foreach($bundleItem->src as $item) {
                if (is_file($item)) {
                    $list[] = $item;
                    continue;
                }
                
                $queryIndex = strpos($item, "/**/*");
                $dir = substr($item, 0, $queryIndex);

                $extensionIndex = strpos($item, "/**/*") + strlen("/**/*");
                $extension = substr($item, $extensionIndex);
                $regex = "/\.*(?:$extension)$/";
                
                $flattened = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
                $files = new \RegexIterator($flattened, $regex);

                foreach($files as $filename) {
                    $list[] = $filename;
                }
            }
            
            return $list;
        }
        
        public function getCssfiles() {
            return $this->getFiles($this->bundle->customcss);
        }
        
        public function getJsfiles() {
            return $this->getFiles($this->bundle->customjs);
        }
    }
}
