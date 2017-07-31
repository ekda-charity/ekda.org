<?php

namespace Application\Controller {
    
    use Zend\Navigation\AbstractContainer;
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Application\API\Canonicals\WordPress\SearchArgs;
    use Application\API\Canonicals\General\Constants;
    use Application\API\Canonicals\WordPress\Slugs;
    use Application\API\Repositories\Interfaces\IWordPressRepository;
    use Application\API\Repositories\Interfaces\IQurbaniRepository;

    class IndexController extends BaseController  {
        
        /**
         * @var IWordPressRepository 
         */
        private $wpRepo;
        
        /**
         * @var IQurbaniRepository 
         */
        private $qurbaniRepo;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IWordPressRepository $wpRepo, IQurbaniRepository $qurbaniRepo) {
            parent::__construct($navService, $authService, $serializer);
            $this->wpRepo = $wpRepo;
            $this->qurbaniRepo = $qurbaniRepo;
        }
        
        public function indexAction() {
            $args = new SearchArgs();
            $args->slug = Slugs::HOME;
            $home = $this->wpRepo->fetchCategoryPosts($args);
            
            return array(
                'posts'          => $home,
                'model' => $this->serializer->serialize(array(
                    'qurbanidetails' => $this->qurbaniRepo->getQurbaniDetails()
            ), 'json'));
        }
        
        public function newsAction() {
            $args = new SearchArgs();
            $args->slug = Slugs::NEWS;
            
            $news = $this->wpRepo->fetchCategoryPosts($args);
            $sidebar = $this->wpRepo->fetchMonthlySidebarInfo($args);
            
            return array('model' => $this->serializer->serialize(array(
                'news'    => $news,
                'sidebar' => $sidebar,
            ), 'json'));
        }
        
        public function aboutAction() {
            $args = new SearchArgs();
            $args->slug = Slugs::ABOUT_US;
            $about = $this->wpRepo->fetchCategoryPosts($args);
            
            return array('model' => $this->serializer->serialize(array(
                'posts' => $about,
            ), 'json'));
        }
        
        public function projectsAction() {
            $args = new SearchArgs();
            $args->slug = Slugs::PROJECTS;
            $projects = $this->wpRepo->fetchCategoryPosts($args);
            $sidebar = $this->wpRepo->fetchPostsSidebarInfo($args);
            
            return array('model' => $this->serializer->serialize(array(
                'sidebar' => $sidebar,
                'posts' => $projects,
            ), 'json'));
        }
        
        public function sponsorsAction() {
            $args = new SearchArgs();
            $args->slug = Slugs::SPONSORS;
            $sponsors = $this->wpRepo->fetchCategoryPosts($args);
            
            return array('model' => $this->serializer->serialize(array(
                'posts' => $sponsors,
            ), 'json'));
        }
        
        public function donateAction() {
            $args = new SearchArgs();
            $args->slug = Slugs::DONATE;
            $sponsors = $this->wpRepo->fetchCategoryPosts($args);
            
            return array('model' => $this->serializer->serialize(array(
                'posts' => $sponsors,
            ), 'json'));
        }
        
        public function contactsAction() {
            $args = new SearchArgs();
            $args->slug = Slugs::CONTACTS;
            $sponsors = $this->wpRepo->fetchCategoryPosts($args);
            
            return array('model' => $this->serializer->serialize(array(
                'posts' => $sponsors,
            ), 'json'));
        }
        
        public function previewAction() {
            $this->navService->findOneById(Constants::PREVIEW_ID)->setVisible(true);
            
            $theLoop = $this->wpRepo->fetchTheLoop($this->getRequest()->getQuery()->toString());
            
            return array('model' => $this->serializer->serialize(array(
                "posts" => $theLoop
            ), 'json'));
        }        
    }
}

