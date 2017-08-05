<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="Emails")
 * @ORM\Entity
 */
class Email
{
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="emailKey", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $emailkey;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="recipients", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $recipients;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     */
    private $subject;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $text;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="html", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $html;

    /**
     * @Type("boolean")
     * @var integer
     *
     * @ORM\Column(name="bcc", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $bcc;

    /**
     * @Type("boolean")
     * @var integer
     *
     * @ORM\Column(name="sent", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $sent;

    /**
     * @Type("boolean")
     * @var integer
     *
     * @ORM\Column(name="sending", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $sending;


    /**
     * Get emailkey
     *
     * @return integer
     */
    public function getEmailkey()
    {
        return $this->emailkey;
    }

    /**
     * Set recipients
     *
     * @param string $recipients
     *
     * @return Email
     */
    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * Get recipients
     *
     * @return string
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Email
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Email
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set html
     *
     * @param string $html
     *
     * @return Email
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get html
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Set bcc
     *
     * @param integer $bcc
     *
     * @return Email
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * Get bcc
     *
     * @return integer
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * Set sent
     *
     * @param integer $sent
     *
     * @return Email
     */
    public function setSent($sent)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent
     *
     * @return integer
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Set sending
     *
     * @param integer $sending
     *
     * @return Email
     */
    public function setSending($sending)
    {
        $this->sending = $sending;

        return $this;
    }

    /**
     * Get sending
     *
     * @return integer
     */
    public function getSending()
    {
        return $this->sending;
    }
}

