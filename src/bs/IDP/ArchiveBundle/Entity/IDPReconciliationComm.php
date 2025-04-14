<?php

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IDPReconciliationComm.php
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\ArchiveBundle\Entity\IDPReconciliationCommRepository")
 */
class IDPReconciliationComm
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
     * @var boolean
     *
     * @ORM\Column(name="way_archimage_provider", type="boolean")
     */
    private $way_archimage_provider = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="provider_file_line_number", type="integer")
     */
    private $provider_file_line_number = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="provider", type="string", length=255, nullable=true)
     */
    private $provider = null;

    /**
     * @var string
     *
     * @ORM\Column(name="container_box_document", type="string", length=255, nullable=true)
     */
    private $container_box_document = null;

    /**
     * @var string
     *
     * @ORM\Column(name="global_status", type="string", length=255, nullable=true)
     */
    private $global_status = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ok_provider", type="boolean" )
     */
    private $ok_provider = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ok_triple", type="boolean" )
     */
    private $ok_triple = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ok_status", type="boolean" )
     */
    private $ok_status = false;

    /**
     * @var string
     *
     * @ORM\Column(name="ko_ua_num_order", type="string", length=255, nullable=true)
     */
    private $ko_ua_num_order = null;

    /**
     * @var string
     *
     * @ORM\Column(name="ko_status", type="string", length=255, nullable=true)
     */
    private $ko_status = null;

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
     * Get way_archimage_provider
     *
     * @return boolean
     */
    public function getWayArchimageProvider()
    {
        return $this->way_archimage_provider;
    }

    /**
     * Set way_archimage_provider
     *
     * @param boolean $way_archimage_provider
     * @return IDPReconciliationComm
     */
    public function setWayArchimageProvider( $way_archimage_provider )
    {
        $this->way_archimage_provider = $way_archimage_provider;

        return $this;
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
     * @param string $provider
     * @return IDPReconciliationComm
     */
    public function setProvider( $provider )
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider_file_line_number
     *
     * @return integer
     */
    public function getProviderFileLineNumber()
    {
        return $this->provider_file_line_number;
    }

    /**
     * Set provider_file_line_number
     *
     * @param integer $provider_file_line_number
     * @return IDPReconciliationComm
     */
    public function setProviderFileLineNumber( $provider_file_line_number )
    {
        $this->provider_file_line_number = $provider_file_line_number;

        return $this;
    }

    /**
     * Get container_box_document
     *
     * @return string
     */
    public function getContianerBoxDocument()
    {
        return $this->container_box_document;
    }

    /**
     * Set container_box_document
     *
     * @param string $container_box_document
     * @return IDPReconciliationComm
     */
    public function setContainerBoxDocument( $container_box_document )
    {
        $this->container_box_document = $container_box_document;

        return $this;
    }

    /**
     * Get global_status
     *
     * @return string
     */
    public function getGlobalStatus()
    {
        return $this->global_status;
    }

    /**
     * Set global_status
     *
     * @param string $global_status
     * @return IDPReconciliationComm
     */
    public function setGlobalStatus( $global_status )
    {
        $this->global_status = $global_status;

        return $this;
    }

    /**
     * Get ok_provider
     *
     * @return boolean
     */
    public function getOkProvider()
    {
        return $this->ok_provider;
    }

    /**
     * Set ok_provider
     *
     * @param boolean $ok_provider
     * @return IDPReconciliationComm
     */
    public function setOkProvider( $ok_provider )
    {
        $this->ok_provider = $ok_provider;

        return $this;
    }

    /**
     * Get ok_triple
     *
     * @return boolean
     */
    public function getOkTriple()
    {
        return $this->ok_triple;
    }

    /**
     * Set ok_triple
     *
     * @param boolean $ok_triple
     * @return IDPReconciliationComm
     */
    public function setOkTriple( $ok_triple )
    {
        $this->ok_triple = $ok_triple;

        return $this;
    }

    /**
     * Get ok_status
     *
     * @return boolean
     */
    public function getOkStatus()
    {
        return $this->ok_status;
    }

    /**
     * Set ok_status
     *
     * @param boolean $ok_status
     * @return IDPReconciliationComm
     */
    public function setOkStatus( $ok_status )
    {
        $this->ok_status = $ok_status;

        return $this;
    }

    /**
     * Get ko_ua_num_order
     *
     * @return string
     */
    public function getKoUaNumOrder()
    {
        return $this->ko_ua_num_order;
    }

    /**
     * Set ko_ua_num_order
     *
     * @param string $ko_ua_num_order
     * @return IDPReconciliationComm
     */
    public function setKoUaNumOrder( $ko_ua_num_order )
    {
        $this->ko_ua_num_order = $ko_ua_num_order;

        return $this;
    }

    /**
     * Get ko_status
     *
     * @return string
     */
    public function getKoStatus()
    {
        return $this->ko_status;
    }

    /**
     * Set ko_status
     *
     * @param string $ko_ua_num_order
     * @return IDPReconciliationComm
     */
    public function setKoStatus( $ko_status )
    {
        $this->ko_status = $ko_status;

        return $this;
    }

}

