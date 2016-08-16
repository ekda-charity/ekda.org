<?php

namespace Application\API\Repositories\Interfaces {

    interface IAuthStorage {
        public function setRememberMe($rememberMe = 0, $time = 1209600);
        public function forgetMe();
    }
}
