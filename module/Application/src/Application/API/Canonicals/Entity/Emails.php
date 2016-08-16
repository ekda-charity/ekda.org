<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * Ads
 *
 * @AccessType("public_method")
 * @ORM\Table(name="Emails")
 * @ORM\Entity
 */
class Emails
{
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="emailKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $emailkey;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="host", type="string", length=100, nullable=false)
     */
    private $host;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="port", type="string", length=100, nullable=false)
     */
    private $port;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="auth", type="string", length=100, nullable=true)
     */
    private $auth;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=false)
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
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="recipients", type="text", nullable=false)
     */
    private $recipients;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=200, nullable=false)
     */
    private $subject;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="html", type="text", nullable=true)
     */
    private $html;

    /**
     * @Type("boolean")
     * @var integer
     *
     * @ORM\Column(name="bcc", type="integer", nullable=false)
     */
    private $bcc;

    function getEmailkey() {
        return $this->emailkey;
    }
    
    function getHost() {
        return $this->host;
    }
    
    function getPort() {
        return $this->port;
    }
    
    function getAuth() {
        return $this->auth;
    }
    
    function getUsername() {
        return $this->username;     
    }
    
    function getPassword() {
        return $this->password;
    }
    
    function getRecipients() {
        return $this->recipients;
    }
    
    function getSubject() {
        return $this->subject;
    }
    
    function getText() {         
        return $this->text;     
    }
    
    function getHtml() {         
        return $this->html;     
    }
    
    function getBcc() {         
        return $this->bcc;     
    }
    
    function setEmailkey($val) {         
        $this->emailkey = $val;     
    }

    function setHost($val) {         
        $this->host = $val;     
    }
    
    function setPort($val) {         
        $this->port = $val;     
    }
    
    function setAuth($val) {         
        $this->auth = $val;     
    }
    
    function setUsername($val) {         
        $this->username = $val;    
    }
    
    function setPassword($val) {         
        $this->password = $val;     
    }
    
    function setRecipients($val) {         
        $this->recipients = $val;     
    }
    
    function setSubject($val) {         
        $this->subject = $val;     
    }
    
    function setText($val) {         
        $this->text = $val;     
    }
    
    function setHtml($val) {         
        $this->html = $val;     
    }
    
    function setBcc($val) {         
        $this->bcc = $val;     
    }
}
