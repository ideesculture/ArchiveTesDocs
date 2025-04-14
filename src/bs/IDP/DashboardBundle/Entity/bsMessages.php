<?php

namespace bs\IDP\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * bsMessages
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\DashboardBundle\Entity\bsMessagesRepository")
 */
class bsMessages
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
     * @ORM\Column(name="bsFrom", type="integer", nullable=true)
     */
    private $bsFrom;

    /**
     * @var integer
     *
     * @ORM\Column(name="bsTo", type="integer", nullable=true)
     */
    private $bsTo;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="Text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sentDate", type="datetime", nullable=true)
     */
    private $sentDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="bsStatus", type="integer", nullable=true)
     */
    private $bsStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bsViewed", type="boolean", options={"default" = false})
     */
    private $bsViewed = false;


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
     * Set bsFrom
     *
     * @param integer $bsFrom
     * @return bsMessages
     */
    public function setBsFrom($bsFrom)
    {
        $this->bsFrom = $bsFrom;

        return $this;
    }

    /**
     * Get bsFrom
     *
     * @return integer
     */
    public function getBsFrom()
    {
        return $this->bsFrom;
    }

    /**
     * Set bsTo
     *
     * @param integer $bsTo
     * @return bsMessages
     */
    public function setBsTo($bsTo)
    {
        $this->bsTo = $bsTo;

        return $this;
    }

    /**
     * Get bsTo
     *
     * @return integer
     */
    public function getBsTo()
    {
        return $this->bsTo;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return bsMessages
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return bsMessages
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
     * Set sentDate
     *
     * @param \DateTime $sentDate
     * @return bsMessages
     */
    public function setSentDate($sentDate)
    {
        $this->sentDate = $sentDate;

        return $this;
    }

    /**
     * Get sentDate
     *
     * @return \DateTime
     */
    public function getSentDate()
    {
        return $this->sentDate;
    }

    /**
     * Set bsStatus
     *
     * @param integer $bsStatus
     * @return bsMessages
     */
    public function setBsStatus($bsStatus)
    {
        $this->bsStatus = $bsStatus;

        return $this;
    }

    /**
     * Get bsStatus
     *
     * @return integer
     */
    public function getBsStatus()
    {
        return $this->bsStatus;
    }

    /**
     * Set bsViewed
     *
     * @param boolean $bsViewed
     * @return bsMessages
     */
    public function setBsViewed($bsViewed)
    {
        $this->bsViewed = $bsViewed;

        return $this;
    }

    /**
     * Get bsViewed
     *
     * @return boolean
     */
    public function getBsViewed()
    {
        return $this->bsViewed;
    }
}
