<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * Users
 *
 * @AccessType("public_method")
 * @ORM\Table(name="Users")
 * @ORM\Entity
 */
class Users
{
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $username;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    private $password;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="tries", type="integer", nullable=false)
     */
    private $tries;

    function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function getTries() {
        return $this->tries;
    }

    function setUsername($val) {
        $this->username = $val;
    }

    function setPassword($val) {
        $this->password = $val;
    }

    function setTries($val) {
        $this->tries = $val;
    }
}
