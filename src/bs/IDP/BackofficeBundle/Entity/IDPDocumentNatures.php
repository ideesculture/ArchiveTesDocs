<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPDocumentNatures
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPDocumentNaturesRepository")
 */
class IDPDocumentNatures
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
     * @ORM\Column(name="longname", type="string", length=255)
     */
    private $longname;

	/**
	 * @ORM\ManyToMany( targetEntity="IDPServices", inversedBy="documentNatures" )
	 **/
	private $services;

	/**
	 * @ORM\ManyToMany( targetEntity="IDPDocumentTypes", mappedBy="documentNatures" )
	 **/
	private $documentTypes;

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
     * Set longname
     *
     * @param string $longname
     * @return IDPDocumentNatures
     */
    public function setLongname($longname)
    {
        $this->longname = $longname;

        return $this;
    }

    /**
     * Get longname
     *
     * @return string
     */
    public function getLongname()
    {
        return $this->longname;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->legalEntities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documentTypes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add Service
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPServices $service
     * @return IDPDocumentNatures
     */
    public function addService(\bs\IDP\BackofficeBundle\Entity\IDPServices $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPServices $service
     */
    public function removeService(\bs\IDP\BackofficeBundle\Entity\IDPServices $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Add documentTypes
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDocumentTypes $documentTypes
     * @return IDPDocumentNatures
     */
    public function addDocumentType(\bs\IDP\BackofficeBundle\Entity\IDPDocumentTypes $documentTypes)
    {
        $this->documentTypes[] = $documentTypes;

        return $this;
    }

    /**
     * Remove documentTypes
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDocumentTypes $documentTypes
     */
    public function removeDocumentType(\bs\IDP\BackofficeBundle\Entity\IDPDocumentTypes $documentTypes)
    {
        $this->documentTypes->removeElement($documentTypes);
    }

    /**
     * Get documentTypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentTypes()
    {
        return $this->documentTypes;
    }
}
