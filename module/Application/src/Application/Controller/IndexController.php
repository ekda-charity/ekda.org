<?php

namespace Application\Controller {
    
    use Application\API\Canonicals\WordPress\SearchArgs,
        Application\API\Canonicals\WordPress\Slugs;

    class IndexController extends BaseController  {
        
        public function indexAction() {
            $wpRepo = $this->getServiceLocator()->get('WordPrRepo');
            
            $args = new SearchArgs();
            $args->posts_per_page = 1;
            $args->slug = Slugs::HOME;
            $home = $wpRepo->fetchCategoryPosts($args);
            
            $args->slug = Slugs::NEWS;
            $args->posts_per_page = "";
            $news = $wpRepo->fetchCategoryPosts($args);
            
            return array('model' => $this->serializer->serialize(array(
                'home' => $home,
                'news' => $news
            ), 'json'));
        }
        
        public function aboutAction() {
            $wpRepo = $this->getServiceLocator()->get('WordPrRepo');
            
            $args = new SearchArgs();
            $args->slug = Slugs::ABOUT_US;
            $about = $wpRepo->fetchCategoryPosts($args);
            
            return array('model' => $this->serializer->serialize(array(
                'about' => $about,
            ), 'json'));
        }
        
        public function projectsAction() {
            $wpRepo = $this->getServiceLocator()->get('WordPrRepo');
            
            $args = new SearchArgs();
            $args->slug = Slugs::PROJECTS;
            $projects = $wpRepo->fetchCategoryPosts($args);
            
            return array('model' => $this->serializer->serialize(array(
                'about' => $projects,
            ), 'json'));
        }
        
        public function sponsorsAction() {
            $wpRepo = $this->getServiceLocator()->get('WordPrRepo');
            
            $args = new SearchArgs();
            $args->slug = Slugs::SPONSORS;
            $sponsors = $wpRepo->fetchCategoryPosts($args);
            
            return array('model' => $this->serializer->serialize(array(
                'about' => $sponsors,
            ), 'json'));
        }
    }
}

