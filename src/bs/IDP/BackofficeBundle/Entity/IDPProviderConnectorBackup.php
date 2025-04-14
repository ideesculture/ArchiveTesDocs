<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPProviderConnectorBackup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPProviderConnectorBackupRepository")
 */
class IDPProviderConnectorBackup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255, nullable=true)
     */
    private $contact;
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;
    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;
    /**
     * @var integer
     *
     * @ORM\Column(name="deliver", type="integer", nullable=true )
     */
    private $deliver;
    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=true )
     */
    private $type;
    /**
     * @var integer
     *
     * @ORM\Column(name="type2", type="integer", nullable=true )
     */
    private $type2;
    /**
     * @var integer
     *
     * @ORM\Column(name="disposal", type="integer", nullable=true )
     */
    private $disposal;
    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="string", length=1024, nullable=true)
     */
    private $remark;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;
    /**
     * @var string
     *
     * @ORM\Column(name="function", type="string", length=255, nullable=true)
     */
    private $function;
    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer" )
     */
    private $userid;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return IDPProviderConnectorBackup
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }
    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return IDPProviderConnectorBackup
     */
    public function setPhone( $phone )
    {
        $this->phone = $phone;

        return $this;
    }
    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return IDPProviderConnectorBackup
     */
    public function setAddress( $address )
    {
        $this->address = $address;

        return $this;
    }
    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set deliver
     *
     * @param integer $deliver
     * @return IDPProviderConnectorBackup
     */
    public function setDeliver( $deliver )
    {
        $this->deliver = $deliver;

        return $this;
    }
    /**
     * Get deliver
     *
     * @return integer
     */
    public function getDeliver()
    {
        return $this->deliver;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return IDPProviderConnectorBackup
     */
    public function setType( $type )
    {
        $this->type = $type;

        return $this;
    }
    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type2
     *
     * @param integer $type2
     * @return IDPProviderConnectorBackup
     */
    public function setType2( $type2 )
    {
        $this->type2 = $type2;

        return $this;
    }
    /**
     * Get type2
     *
     * @return integer
     */
    public function getType2()
    {
        return $this->type2;
    }

    /**
     * Set disposal
     *
     * @param integer $disposal
     * @return IDPProviderConnectorBackup
     */
    public function setDisposal( $disposal )
    {
        $this->disposal = $disposal;

        return $this;
    }
    /**
     * Get disposal
     *
     * @return integer
     */
    public function getDisposal()
    {
        return $this->disposal;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return IDPProviderConnectorBackup
     */
    public function setRemark( $remark )
    {
        $this->remark = $remark;

        return $this;
    }
    /**
     * Get remark
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return IDPProviderConnectorBackup
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return IDPProviderConnectorBackup
     */
    public function setFirstname( $firstname )
    {
        $this->firstname = $firstname;

        return $this;
    }
    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set function
     *
     * @param string $function
     * @return IDPProviderConnectorBackup
     */
    public function setFunction( $function )
    {
        $this->function = $function;

        return $this;
    }
    /**
     * Get function
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return IDPProviderConnectorBackup
     */
    public function setUserid( $userid )
    {
        $this->userid = $userid;

        return $this;
    }
    /**
     * Get userid
     *
     * @return integer
     */
    public function getUserid()
    {
        return $this->userid;
    }

}
