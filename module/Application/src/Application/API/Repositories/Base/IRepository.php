<?php

namespace Application\API\Repositories\Base {
    
    use Doctrine\Common\Collections\Criteria;
    
    interface IRepository {
        public function fetch($id);
        public function fetchAll();
        public function searchBy(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10);
        public function search($page = 0, $pageSize = 10, array $orderBy = null);
        public function findOneBy(array $criteria, array $orderBy = null);
        public function findBy(array $criteria, array $orderBy = null);
        public function matching(Criteria $criteria);
        public function count(array $criteria);
        public function total();
        public function add($entity);
        public function addList(array $entities);
        public function addOrUpdate($entity);
        public function update($entity);
        public function delete($entity);
        public function deleteList(array $entities);
        public function deleteByKey($id);
        public function deleteListByKeys(array $ids);
    }
}
