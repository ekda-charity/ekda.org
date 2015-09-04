<?php

namespace Application\API\Repositories\Implementations {

    use Zend\Authentication\Storage\Session,
        Application\API\Repositories\Interfaces\IAuthStorage;

    class AuthStorage extends Session implements IAuthStorage {

        public function setRememberMe($rememberMe = 0, $time = 1209600) {
            if ($rememberMe == 1) {
                $this->session->getManager()->rememberMe($time);
            }
        }

        public function forgetMe() {
            $this->session->getManager()->forgetMe();
        }
    }
}