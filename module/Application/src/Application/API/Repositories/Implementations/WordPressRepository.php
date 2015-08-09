<?php

namespace Application\API\Repositories\Implementations {
    
    use Application\API\Repositories\Interfaces\IWordPressRepository,
        Application\API\Canonicals\WordPress\SearchArgs,
        Application\API\Canonicals\WordPress\Post,
        Application\API\Canonicals\WordPress\Category;
    
    require_once 'public/wordpress/wp-load.php';

    class WordPressRepository implements IWordPressRepository {


        public function __construct() {
        }
        
        public function fetchCategoryByCat($cat) {
            $category = get_category($cat);

            if(!isset($category->term_id)) {
                return null;
            } else {
                return new Category($category);
            }
        }

        public function fetchCategoryBySlug($slug) {
            $category = get_category_by_slug($slug);

            if(!isset($category->term_id)) {
                return null;
            } else {
                return new Category($category);
            }
        }

        public function fetchPostCategories($id) {
            if ($id == null) {
                return null;
            } else {

                $categories = null;
                $i = 0;
                
                foreach(get_the_category($id) as $cat) {
                    $categories[$i++] = new Category($cat);
                }                
                
                return $categories;
            }
        }
        
        public function fetchMonthnumOfLatestPost($slug) {
            $result = null;
            $category = $this->fetchCategoryBySlug($slug);
            
            if($category->getTermid() != null) {

                $search_array = array(
                    'cat' => $category->getTermid(),
                    'posts_per_page'  => 1,
                );
                
                query_posts($search_array);
                
                while ( have_posts() ) : the_post();
                    $post = get_post();
                    $date = getdate(strtotime($post->post_date));
                    $monthnum = $date['year'].str_pad($date['mon'], 2, "0", STR_PAD_LEFT);
                    $result = $monthnum;
                endwhile;
                
                //wp_reset_query();                
            }
            
            return $result;
        }

        public function fetchCategoryPosts(SearchArgs $args) {
            $i = 0;
            $posts = null;
            $category = $this->fetchCategoryBySlug($args->slug);
            
            if($category->getTermid() != null) {

                $search_array = array(
                    'posts_per_page'  => $args->posts_per_page,
                    'offset'          => $args->offset,
                    'cat'             => $category->getTermid(),
                    'm'               => $args->monthnum,
                    'orderby'         => $args->orderby,
                    'order'           => $args->order,
                );
                
                query_posts($search_array);
                
                while ( have_posts() ) : the_post();
                    $post = get_post();
                    if (!$args->childrenOnly) {
                        $posts[$i++] = new Post($post);
                    } else {
                        foreach(get_the_category($post->ID) as $cat) {
                            if($cat->term_id == $category->getTermid()) {
                                $posts[$i++] = new Post($post);
                                break;
                            }
                        }
                    }
                endwhile;
                
                //wp_reset_query();
            }
            return $posts;
        }
        
        public function fetchMonthlySidebarInfo(SearchArgs $args) {
            $sidebarInfo = null;
            $category = $this->fetchCategoryBySlug($args->slug);
            
            if($category->getTermid() != null) {

                $search_array = array(
                    'posts_per_page'  => $args->posts_per_page,
                    'offset'          => $args->offset,
                    'cat'             => $category->getTermid(),
                    'monthnum'        => $args->monthnum
                );
                
                query_posts($search_array);
                
                while ( have_posts() ) : the_post();
                    $post = get_post();
                    $date = getdate(strtotime($post->post_date));
                    $y = $date['year'];
                    $monthnum = $date['year'].str_pad($date['mon'], 2, "0", STR_PAD_LEFT);

                    $sidebarInfo[$y][$monthnum]['month']  = $date['month'];
                    $sidebarInfo[$y][$monthnum]['date']  = new \DateTime($date['year'] . "-" . $date['mon'] . "-01");
                    $sidebarInfo[$y][$monthnum]['count']++;
                endwhile;
                
                //wp_reset_query();                
            }
            
            return $sidebarInfo;
        }

        public function fetchChildCategories($slug) {
            $returnVal = array();
            $i = 0;
            $category = $this->fetchCategoryBySlug($slug);
            
            foreach(get_term_children($category->getTermid(), $category->getTaxonomy()) as $term_id) {
                $child_category = $this->fetchCategoryByCat($term_id);
                
                if ($child_category->getCategoryparent() == $category->getTermid()) {
                    $returnVal [$i++] = $child_category;
                }
            }
            
            usort($returnVal, function($a, $b){
                return strcmp($a->getSlug(), $b->getSlug());
            });
            
            return $returnVal;
        }
        
        public function fetchPostsSidebarInfo(SearchArgs $args) {
            $sidebarInfo = null;
            $category = $this->fetchCategoryBySlug($args->slug);
            if($category->getTermid() != null) {
                
                $search_array = array(
                    'posts_per_page'    =>  $args->posts_per_page,
                    'offset'            =>  $args->offset,
                    'cat'               =>  $category->getTermid(),
                    'monthnum'          =>  $args->monthnum
                );
                
                query_posts($search_array);
                
                while(have_posts()): the_post();
                    $post = get_post();
                    $sidebarInfo[$post->ID] = new Post($post);
                endwhile;
                
                //wp_reset_query();
            }
            return $sidebarInfo;
        }
        
        public function fetchTheLoop($query_string) {
            $i = 0;
            $posts = null;
            
            query_posts($query_string);

            while ( have_posts() ) : the_post();
                $post = get_post();
                $posts[$i++] = new Post($post);
            endwhile;

            //wp_reset_query();
            return $posts;
        }
    }
}
