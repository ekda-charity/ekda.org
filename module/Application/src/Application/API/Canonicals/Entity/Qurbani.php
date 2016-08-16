<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * Ads
 *
 * @AccessType("public_method")
 * @ORM\Table(name="Qurbanis")
 * @ORM\Entity
 */
class Qurbani
{
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="qurbaniKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $qurbanikey;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="qurbanimonth", type="string", length=10, nullable=false)
     */
    private $qurbanimonth;
    
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="sheep", type="integer", nullable=false)
     */
    private $sheep;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="cows", type="integer", nullable=false)
     */
    private $cows;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="camels", type="integer", nullable=false)
     */
    private $camels;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="total", type="integer", nullable=false)
     */
    private $total;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=100, nullable=true)
     */
    private $fullname;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=100, nullable=true)
     */
    private $mobile;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="instructions", type="text", nullable=true)
     */
    private $instructions;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="donationid", type="string", length=100, nullable=true)
     */
    private $donationid;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="isvoid", type="integer", nullable=false)
     */
    private $isvoid;
    
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="iscomplete", type="integer", nullable=false)
     */
    private $iscomplete;
    
    /**
     * @Type("DateTime<'Y-m-d\TH:i:sO'>")
     * @var \DateTime
     *
     * @ORM\Column(name="createdDate", type="datetime", nullable=false)
     */
    private $createddate;
    
    public function __construct(Qurbani $qurbani = null) {
        if ($qurbani != null) {
            $this->qurbanikey = $qurbani->getQurbanikey();
            $this->qurbanimonth = $qurbani->getQurbanimonth();
            $this->sheep = $qurbani->getSheep();
            $this->cows = $qurbani->getCows();
            $this->camels = $qurbani->getCamels();
            $this->total = $qurbani->getTotal();
            $this->fullname = $qurbani->getFullname();
            $this->email = $qurbani->getEmail();
            $this->mobile = $qurbani->getMobile();
            $this->instructions = $qurbani->getInstructions();
            $this->donationid = $qurbani->getDonationid();
            $this->isvoid = $qurbani->getIsvoid();
            $this->iscomplete = $qurbani->getIscomplete();
            $this->createddate = $qurbani->getCreateddate();
        }
    }
    
    function getQurbanikey() { return $this->qurbanikey; }
    function getQurbanimonth() { return $this->qurbanimonth; }
    function getSheep() { return $this->sheep; }
    function getCows() { return $this->cows; }
    function getCamels() { return $this->camels; }
    function getTotal() { return $this->total; }
    function getFullname() { return $this->fullname; }
    function getEmail() { return $this->email; }
    function getMobile() { return $this->mobile; }
    function getInstructions() { return $this->instructions; }
    function getDonationid() { return $this->donationid; }    
    function getIsvoid() { return $this->isvoid; }    
    function getIscomplete() { return $this->iscomplete; }
    function getCreateddate() { return $this->createddate; }    

    function setQurbanikey($val) { $this->qurbanikey = $val; }
    function setQurbanimonth($val) { $this->qurbanimonth = $val; }    
    function setSheep($val) { $this->sheep = $val; }
    function setCows($val) { $this->cows = $val; }
    function setCamels($val) { $this->camels = $val; }
    function setTotal($val) { $this->total = $val; }
    function setFullname($val) { $this->fullname = $val; }
    function setEmail($val) { $this->email = $val; }
    function setMobile($val) { $this->mobile = $val; }
    function setInstructions($val) { $this->instructions = $val; }
    function setDonationid($val) { $this->donationid = $val; }
    function setIsvoid($val) { $this->isvoid = $val; }
    function setIscomplete($val) { $this->iscomplete = $val; }
    function setCreateddate($val) { $this->createddate = $val; }
}
