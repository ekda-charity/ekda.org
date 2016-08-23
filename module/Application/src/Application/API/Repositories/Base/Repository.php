<?php

namespace Application\API\Repositories\Base {

    use Doctrine\Common\Collections\Criteria,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Query\Expr,
        Doctrine\ORM\EntityManager,
        Application\API\Canonicals\Response\SearchResponse;
    
    class Repository implements IRepository {
        
        /**
         * @var EntityManager 
         */
        protected $em;
        
        /**
         * @var EntityRepository
         */
        public $repository;
        
        public function __construct(EntityManager $em, EntityRepository $repository) {
            $this->em = $em;
            $this->repository = $repository;
        }
        
        public function fetch($id) {
            return $this->repository->find($id);
        }

        public function fetchAll() {
            return $this->repository->findAll();
        }
        
        public function searchBy(array $criteria, array $orderBy = null, $page = 0, $pageSize = PHP_INT_MAX) {
            if ($page < 0 || $pageSize < 1) {
                throw new \Exception("Invalid page or pageSize: page must be >= 0 and pageSize must be > 0");
            } else {
                $total = $this->count($criteria);
                $items = $this->repository->findBy($criteria, $orderBy, $pageSize, $page);
                return $this->createSearchResult($total, $items, $page, $pageSize);
            }
        }
        
        public function search($page = 0, $pageSize = 10, array $orderBy = null) {
            if ($page < 0 || $pageSize < 1) {
                throw new \Exception("Invalid page or pageSize: page must be >= 0 and pageSize must be > 0");
            } else {
                $metadata = $this->em->getClassMetadata($this->repository->getClassName());
                $identifiers = $metadata->getIdentifierFieldNames();
                $id = $identifiers[0];
                
                $total = $this->repository->createQueryBuilder("q")->select("COUNT(q.$id)")->getQuery()->getSingleScalarResult();
                $query = $this->repository->createQueryBuilder("q");
                
                if ($orderBy != null) {
                    foreach($orderBy as $field => $direction) {
                        $query->orderBy(new Expr\OrderBy("q.$field", $direction));
                    }
                }
                        
                $items = $query->setFirstResult($page * $pageSize)->setMaxResults($pageSize)->getQuery()->getResult();
                return $this->createSearchResult($total, $items, $page, $pageSize);
            }
        }

        private function createSearchResult($total, $items, $page, $pageSize) {
            $result = new SearchResponse();
            $result->total = $total;
            $result->totalPages = ceil($total / $pageSize);
            $result->items = $items;
            $result->page = $page;
            $result->pageSize = $pageSize;
            return $result;
        }

        public function findOneBy(array $criteria, array $orderBy = null) {
            return $this->repository->findOneBy($criteria, $orderBy);
        }
        
        public function findBy(array $criteria, array $orderBy = null) {
            return $this->repository->findBy($criteria, $orderBy);
        }
        
        public function matching(Criteria $criteria) {
            return $this->repository->matching($criteria);
        }
        
        public function count(array $criteria) {
            $criteriaObj = new Criteria();

            foreach($criteria as $field => $value) {
                $criteriaObj->where($criteriaObj->expr()->eq($field, $value));
            }
            
            return $this->repository->matching($criteriaObj)->count();
        }
        
        public function total() {
            $metadata = $this->em->getClassMetadata($this->repository->getClassName());
            $identifiers = $metadata->getIdentifierFieldNames();
            $id = $identifiers[0];
            
            return $this->repository->createQueryBuilder("q")->select("COUNT(q.$id)")->getQuery()->getSingleScalarResult();
        }
        
        public function add($entity) {
            $this->em->transactional(function(EntityManager $em) use($entity) {
                $em->persist($entity);
            });
        }
        
        public function addList(array $entities) {
            $this->em->transactional(function(EntityManager $em) use($entities) {
                foreach ($entities as $entity) {
                    $em->persist($entity);
                }
            });
        }

        public function addOrUpdate($entity) {
            $repo = $this->repository;
            
            $this->em->transactional(function(EntityManager $em) use($entity, $repo) {
                
                $metadata = $em->getClassMetadata($repo->getClassName());
                $id = $metadata->getIdentifierValues($entity);
                
                if (count($id) == 0) {
                    $oneRecord = null;
                } else {
                    $oneRecord = $repo->find($id);
                }
                
                if ($oneRecord == null) {
                    $em->persist($entity);
                } else {
                    $em->merge($entity);
                }
            });
        }

        public function update($entity) {
            $repo = $this->repository;
            
            $this->em->transactional(function(EntityManager $em) use($entity, $repo) {
                
                $metadata = $em->getClassMetadata($repo->getClassName());
                $id = $metadata->getIdentifierValues($entity);
                $oneRecord = $repo->find($id);
                
                if ($oneRecord != null) {
                    $em->merge($entity);
                } else {
                    throw new \Exception("Could not find matching record to update");
                }
            });
        }
        
        public function delete($entity) {
            $repo = $this->repository;
            
            $this->em->transactional(function(EntityManager $em) use($entity, $repo) {
                
                $metadata = $em->getClassMetadata($repo->getClassName());
                $id = $metadata->getIdentifierValues($entity);
                $oneRecord = $repo->find($id);
                
                if ($oneRecord != null) {
                    $em->remove($oneRecord);
                } else {
                    throw new \Exception("Could not find matching record to delete");
                }
            });
        }
        
        public function deleteList(array $entities) {
            $repo = $this->repository;
            
            $this->em->transactional(function(EntityManager $em) use($entities, $repo) {
                
                $metadata = $em->getClassMetadata($repo->getClassName());
                
                foreach ($entities as $entity) {
                    $id = $metadata->getIdentifierValues($entity);
                    $oneRecord = $repo->find($id);

                    if ($oneRecord != null) {
                        $em->remove($oneRecord);
                    } else {
                        throw new \Exception("Could not find matching record to delete");
                    }
                }
            });
        }
        
        public function deleteByKey($id) {
            $repo = $this->repository;
            
            $this->em->transactional(function(EntityManager $em) use($id, $repo) {
                
                $oneRecord = $repo->find($id);
                
                if ($oneRecord != null) {
                    $em->remove($oneRecord);
                } else {
                    throw new \Exception("Could not find matching record to delete");
                }
            });
        }
        
        public function deleteListByKeys(array $ids) {
            $repo = $this->repository;
            
            $this->em->transactional(function(EntityManager $em) use($ids, $repo) {
                
                foreach($ids as $id) {
                    $oneRecord = $repo->find($id);

                    if ($oneRecord != null) {
                        $em->remove($oneRecord);
                    } else {
                        throw new \Exception("Could not find matching record to delete");
                    }
                }
            });
        }
    }
}