<?php

namespace Application\Controller {
    
    use Application\API\Canonicals\WordPress\SearchArgs;
    use Application\API\Canonicals\General\Constants;
    use Application\API\Canonicals\WordPress\Slugs;

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
            $sidebar = $wpRepo->fetchMonthlySidebarInfo($args);
            
            return array('model' => $this->serializer->serialize(array(
                'home'       => $home,
                'news'       => $news,
                'newsidebar' => $sidebar,
            ), 'json'));
        }
        
        public function aboutAction() {
            $wpRepo = $this->getServiceLocator()->get('WordPrRepo');
            
            $args = new SearchArgs();
            $args->slug = Slugs::ABOUT_US;
            $about = $wpRepo->fetchCategoryPosts($args);
            $sidebar = $wpRepo->fetchPostsSidebarInfo($args);
            
            return array('model' => $this->serializer->serialize(array(
                'sidebar' => $sidebar,
                'posts' => $about,
            ), 'json'));
        }
        
        public function projectsAction() {
            $wpRepo = $this->getServiceLocator()->get('WordPrRepo');
            $post_id = $this->params()->fromRoute('p1');
            
            $args = new SearchArgs();
            $args->slug = Slugs::PROJECTS;
            $projects = $wpRepo->fetchCategoryPosts($args);
            $sidebar = $wpRepo->fetchPostsSidebarInfo($args);
            
            return array('model' => $this->serializer->serialize(array(
                'sidebar' => $sidebar,
                'posts' => $projects,
            ), 'json'));
        }
        
        public function sponsorsAction() {
            $wpRepo = $this->getServiceLocator()->get('WordPrRepo');
            
            $args = new SearchArgs();
            $args->slug = Slugs::SPONSORS;
            $sponsors = $wpRepo->fetchCategoryPosts($args);
            $sidebar = $wpRepo->fetchPostsSidebarInfo($args);
            
            return array('model' => $this->serializer->serialize(array(
                'sidebar' => $sidebar,
                'posts' => $sponsors,
            ), 'json'));
        }
        
        public function donateAction() {
            $wpRepo = $this->getServiceLocator()->get('WordPrRepo');
            
            $args = new SearchArgs();
            $args->slug = Slugs::DONATE;
            $sponsors = $wpRepo->fetchCategoryPosts($args);
            $sidebar = $wpRepo->fetchPostsSidebarInfo($args);
            
            return array('model' => $this->serializer->serialize(array(
                'sidebar' => $sidebar,
                'posts' => $sponsors,
            ), 'json'));
        }
        
        public function contactsAction() {
            $wpRepo = $this->getServiceLocator()->get('WordPrRepo');
            
            $args = new SearchArgs();
            $args->slug = Slugs::CONTACTS;
            $sponsors = $wpRepo->fetchCategoryPosts($args);
            $sidebar = $wpRepo->fetchPostsSidebarInfo($args);
            
            return array('model' => $this->serializer->serialize(array(
                'sidebar' => $sidebar,
                'posts' => $sponsors,
            ), 'json'));
        }
        
        public function previewAction() {
            $this->getServiceLocator()->get('Navigation')->findOneById(Constants::PREVIEW_ID)->setVisible(true);
            
            $wpRepo  = $this->getServiceLocator()->get('WordPrRepo');
            $theLoop = $wpRepo->fetchTheLoop($this->getRequest()->getQuery()->toString());
            
            return array('model' => $this->serializer->serialize(array(
                "posts" => $theLoop
            ), 'json'));
        }        
    }
}

