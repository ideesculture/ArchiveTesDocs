<?php

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IDPReconciliationFile.php
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\ArchiveBundle\Entity\IDPReconciliationFileRepository")
 */
class IDPReconciliationFile
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
     * @ORM\Column(name="provider", type="string", length=255, nullable=true)
     */
    private $provider = null;

    /**
     * @var string
     *
     * @ORM\Column(name="cbd", type="string", length=255, nullable=true)
     */
    private $cbd = null;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=25, nullable=true)
     */
    private $status = null;

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
     * Get provider
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set provider
     *
     * @param string $wprovider
     * @return IDPReconciliationComm
     */
    public function setProvider( $provider )
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get cbd
     *
     * @return string
     */
    public function getCBD()
    {
        return $this->cbd;
    }

    /**
     * Set cbd
     *
     * @param string $cbd
     * @return IDPReconciliationComm
     */
    public function setCBD( $cbd )
    {
        $this->cbd = $cbd;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string status
     * @return IDPReconciliationComm
     */
    public function setStatus( $status )
    {
        $this->status = $status;

        return $this;
    }
}

