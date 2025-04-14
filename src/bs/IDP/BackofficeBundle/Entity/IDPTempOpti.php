<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPTempOpti
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPTempOptiRepository")
 */
class IDPTempOpti
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
     * @var integer
     *
     * @ORM\Column( name="user_id", type="integer", nullable=true )
     */
    private $user_id;

    /**
     * @var integer
     *
     * @ORM\Column( name="percent", type="integer", nullable=true )
     */
    private $percent;

    /**
     * @var string
     *
     * @ORM\Column( name="message", type="string", nullable=true )
     */
    private $message;

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
     * Set user_id
     *
     * @param integer $user_id
     * @return IDPTempOpti
     */
    public function setUserId( $user_id ){
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer
     */
    public function getUserId( ){
        return $this->user_id;
    }

    /**
     * Set percent
     *
     * @param integer $percent
     * @return IDPTempOpti
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return integer
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return IDPTempOpti
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

}
