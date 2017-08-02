<?php

namespace Application\API\Repositories\Implementations {
    
    use Application\API\Repositories\Interfaces\IWordPressRepository,
        Application\API\Canonicals\WordPress\SearchArgs,
        Application\API\Canonicals\WordPress\Post,
        Application\API\Canonicals\WordPress\Category,
        JMS\Serializer\SerializerInterface;
    
    require_once 'public/wordpress/wp-load.php';

    class WordPressRepository implements IWordPressRepository {

        /**
         * @var SerializerInterface
         */
        private $serializer;
        
        public function __construct(SerializerInterface $serializer) {
            $this->serializer = $serializer;
        }
        
        public function fetchCategoryByCat($cat) {
            if ($cat == null) {
                return null;
            }
            
            $category = get_category($cat);

            if(!isset($category->term_id)) {
                return null;
            } else {
                return new Category($category);
            }
        }

        public function fetchCategoryBySlug($slug) {
            if ($slug == null) {
                return null;
            }
            
            $category = get_category_by_slug($slug);

            if(!isset($category->term_id)) {
                return null;
            } else {
                return new Category($category);
            }
        }

        public function fetchPostCategories($id) {
            if ($id == null) {
                return [];
            } else {

                $categories = [];
                $i = 0;
                
                foreach(get_the_category($id) as $cat) {
                    $categories[$i++] = new Category($cat);
                }                
                
                return $categories;
            }
        }
        
        public function fetchMonthnumOfLatestPost($slug) {
            if ($slug == null) {
                return null;
            }
            
            $result = null;
            $category = $this->fetchCategoryBySlug($slug);
            
            if ($category == null) {
              return null;  
            } else if($category->getTermid() != null) {

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
            if ($args == null) {
                return [];
            }
            
            $i = 0;
            $posts = [];
            $category = $this->fetchCategoryBySlug($args->slug);
            
            if ($category == null) {
              return [];  
            } else if($category->getTermid() != null) {

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
            }
            return $posts;
        }
        
        public function fetchJsonPostBySlug($slug, $type) {
            $post = $this->fetchPostBySlug($slug);
            return $this->serializer->deserialize($post->getContent(), $type, "json");
        }
        
        public function fetchPostBySlug($slug) {
            if ($slug == null) {
                return null;
            }
            
            $post = get_page_by_path($slug, OBJECT, 'post');
            
            if ($post == null) {
                return null;
            } else {
                return new Post($post);
            }
        }

        public function fetchCategoryJsonPostsBySlug($slug, $type) {
            $posts = $this->fetchCategoryPostsBySlug($slug);
            $list = [];
            foreach ($posts as $post) {
                $list[] = $this->serializer->deserialize($post->getContent(), $type, "json");
            }
            return $list;
        }
        
        public function fetchCategoryPostsBySlug($slug) {
            if ($slug == null) {
                return [];
            }
            
            $i = 0;
            $posts = [];
            $args = new SearchArgs();
            $args->slug = $slug;
            $category = $this->fetchCategoryBySlug($args->slug);
            
            if ($category == null) {
              return [];  
            } else if($category->getTermid() != null) {

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
            if ($args == null) {
                return [];
            }
            
            $tempList = [];
            $category = $this->fetchCategoryBySlug($args->slug);
            
            if ($category == null) {
              return [];  
            } else if($category->getTermid() != null) {

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
                    $m = $date['mon'];
                    $month = new \DateTime("$y-$m-01");
                    $monthnum = $y.str_pad($m, 2, "0", STR_PAD_LEFT);

                    $tempList[$monthnum]['month']  = $month;
                    $tempList[$monthnum]['posts'][] = new Post($post);
                endwhile;
                
                //wp_reset_query();                
            }

            uksort($tempList, function($a, $b){
                if ($a > $b) {
                    return -1;
                } else if ($a < $b) {
                    return 1;
                } else {
                    return 0;
                }
            });
            
            $sideBarInfo = [];
            
            foreach ($tempList as $key => $val) {
                $sideBarInfo[] = $val;
            }
            
            return $sideBarInfo;
        }

        public function fetchChildCategories($slug) {
            if ($slug == null) {
                return [];
            }
            
            $returnVal = [];
            $i = 0;
            $category = $this->fetchCategoryBySlug($slug);
            
            if ($category == null) {
              return [];  
            }
            
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
            if ($args == null) {
                return [];
            }
            
            $sidebarInfo = [];
            $category = $this->fetchCategoryBySlug($args->slug);
            if ($category == null) {
              return [];  
            } else if($category->getTermid() != null) {
                
                $search_array = array(
                    'posts_per_page'    =>  $args->posts_per_page,
                    'offset'            =>  $args->offset,
                    'cat'               =>  $category->getTermid(),
                    'monthnum'          =>  $args->monthnum
                );
                
                query_posts($search_array);
                
                while(have_posts()): the_post();
                    $post = get_post();
                    $sidebarInfo[] = new Post($post);
                endwhile;
                
                //wp_reset_query();
            }
            return $sidebarInfo;
        }
        
        public function fetchTheLoop($query_string) {
            
            $i = 0;
            $posts = [];
            
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
