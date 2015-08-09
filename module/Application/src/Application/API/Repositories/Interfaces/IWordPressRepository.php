<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\WordPress\SearchArgs;
    
    interface IWordPressRepository {
        public function fetchCategoryByCat($cat);
        public function fetchCategoryBySlug($slug);
        public function fetchPostCategories($id);
        public function fetchMonthnumOfLatestPost($slug);
        public function fetchCategoryPosts(SearchArgs $args);
        public function fetchMonthlySidebarInfo(SearchArgs $args);
        public function fetchChildCategories($slug);
        public function fetchPostsSidebarInfo(SearchArgs $args);
        public function fetchTheLoop($query_string);
    }
}
