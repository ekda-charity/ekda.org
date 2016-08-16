<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Application\API\Canonicals\Entity,
        Application\API\Repositories\Base\Repository;

    class BaseRepository {
        
        /**
         * @var EntityManager 
         */
        protected $em;
        
        /**
         * @var Repository
         */
        protected $qurbaniRepo;
        /**
         * @var Repository
         */
        protected $emailsRepo;
        /**
         * @var Repository
         */
        protected $usersRepo;
        
        public function __construct(EntityManager $em) {
            $this->em = $em;
            
            $this->qurbaniRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Entity\Qurbani()))));
            $this->emailsRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Entity\Emails()))));
            $this->usersRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Entity\Users()))));
        }
    }
}