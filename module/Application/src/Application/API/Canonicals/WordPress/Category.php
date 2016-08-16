<?php

namespace Application\API\Canonicals\WordPress {
    
    use JMS\Serializer\Annotation\AccessType;

    /**
     * @AccessType("public_method")
     */
    class Category { 
        
        private $category;
        private $termid;
        private $name;
        private $slug;
        private $termgroup;
        private $termorder;
        private $termtaxonomyid;
        private $taxonomy;
        private $description;
        private $parent;
        private $count;
        private $filter;
        private $catid;
        private $categorycount;
        private $categorydescription;
        private $catname;
        private $categorynicename;
        private $categoryparent;
        
        public function __construct($category) {
            $this->category = $category;
            $this->termid = $category->term_id;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->termgroup = $category->term_group;
            $this->termorder = $category->term_order;
            $this->termtaxonomyid = $category->term_taxonomy_id;
            $this->taxonomy = $category->taxonomy;
            $this->description = $category->description;
            $this->parent = $category->parent;
            $this->count = $category->count;
            $this->filter = $category->filter;
            $this->catid = $category->cat_ID;
            $this->categorycount = $category->category_count;
            $this->categorydescription = $category->category_description;
            $this->catname = $category->cat_name;
            $this->categorynicename = $category->category_nicename;
            $this->categoryparent = $category->category_parent;
        }
        
        public function getCategory() {return $this->category;}
        public function getTermid() {return $this->termid;}
        public function getName() {return $this->name;}
        public function getSlug() {return $this->slug;}
        public function getTermgroup() {return $this->termgroup;}
        public function getTermorder() {return $this->termorder;}
        public function getTermtaxonomyid() {return $this->termtaxonomyid;}
        public function getTaxonomy() {return $this->taxonomy;}
        public function getDescription() {return $this->description;}
        public function getParent() {return $this->parent;}
        public function getCount() {return $this->count;}
        public function getFilter() {return $this->filter;}
        public function getCatid() {return $this->catid;}
        public function getCategorycount() {return $this->categorycount;}
        public function getCategorydescription() {return $this->categorydescription;}
        public function getCatname() {return $this->catname;}
        public function getCategorynicename() {return $this->categorynicename;}
        public function getCategoryparent() {return $this->categoryparent;}

        public function setCategory($val) { $this->category = $val; }
        public function setTermid($val) { $this->termid = $val; }
        public function setName($val) { $this->name = $val; }
        public function setSlug($val) { $this->slug = $val; }
        public function setTermgroup($val) { $this->termgroup = $val; }
        public function setTermorder($val) { $this->termorder = $val; }
        public function setTermtaxonomyid($val) { $this->termtaxonomyid = $val; }
        public function setTaxonomy($val) { $this->taxonomy = $val; }
        public function setDescription($val) { $this->description = $val; }
        public function setParent($val) { $this->parent = $val; }
        public function setCount($val) { $this->count = $val; }
        public function setFilter($val) { $this->filter = $val; }
        public function setCatid($val) { $this->catid = $val; }
        public function setCategorycount($val) { $this->categorycount = $val; }
        public function setCategorydescription($val) { $this->categorydescription = $val; }
        public function setCatname($val) { $this->catname = $val; }
        public function setCategorynicename($val) { $this->categorynicename = $val; }
        public function setCategoryparent($val) { $this->categoryparent = $val; }

    }
}
