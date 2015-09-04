<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Users;
    
    interface IUsersRepository {
        public function findAll();
        public function find($username);
        
        public function addUser(Users $user);
        public function updateUser(Users $user, $oldPassword);
        public function deleteUser($username, $oldPassword);
    }
}

