<?php

namespace Application\API\Canonicals\WordPress {
    
    use Application\API\Canonicals\General\Constants,
        Application\API\Canonicals\WordPress\Slugs,
        JMS\Serializer\Annotation\AccessType;

    /**
     * @AccessType("public_method")
     */
    class Post { 
        
        private $post;
        private $date;
        private $modifeddate;
        private $title;
        private $content;
        private $filteredcontent;
        private $categories;
        private $monthnum;
        
        public function __construct($post) {
            $this->post = $post;
            $this->date = new \DateTime(trim($post->post_date));
            $this->modifeddate = isset($post->post_modified) ? new \DateTime(trim($post->post_modified)) : null;
            $this->title = $post->post_title;
            $this->content = $post->post_content;
            $this->filteredcontent = apply_filters('the_content', get_the_content());
            $this->categories = get_the_category($post->ID);
            
            $date = getdate(strtotime($post->post_date));
            $this->monthnum = $date['year'].str_pad($date['mon'], 2, "0", STR_PAD_LEFT);
        }
        
        public function getPost() {return $this->post;}
        public function getDate() {return $this->date;}
        public function getModifeddate() {return $this->modifeddate;}
        public function getTitle() {return $this->title;}
        public function getContent() {return $this->content;}
        public function getFilteredcontent() {return $this->filteredcontent;}
        public function getCategories() {return $this->categories;}
        public function getMonthnum() {return $this->monthnum;}
        public function getIsyoutubepost() {return $this->isyoutubepost;}
        public function getIsprayertimesfooter() {return $this->isprayertimesfooter;}

        public function setPost($val) { $this->post = $val; }
        public function setDate($val) { $this->date = $val; }
        public function setModifeddate($val) { $this->modifeddate = $val; }
        public function setTitle($val) { $this->title = $val; }
        public function setContent($val) { $this->content = $val; }
        public function setFilteredcontent($val) { $this->filteredcontent = $val; }
        public function setCategories($val) { $this->categories = $val; }
        public function setMonthnum($val) { $this->monthnum = $val; }
    }
}
