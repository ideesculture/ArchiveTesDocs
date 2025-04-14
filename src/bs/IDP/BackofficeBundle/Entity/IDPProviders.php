<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPProviders
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPProvidersRepository")
 */
class IDPProviders
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
	 * @ORM\ManyToMany( targetEntity="IDPServices", inversedBy="providers" )
	 **/
	private $services;

    /**
     * @ORM\ManyToOne(targetEntity="IDPLocalizations")
     * @ORM\JoinColumn(name="localization_id", referencedColumnName="id", nullable=true)
     **/
    private $localization;

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
     * @return IDPProviders
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


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add services
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPServices $services
     * @return IDPProviders
     */
    public function addService(\bs\IDP\BackofficeBundle\Entity\IDPServices $services)
    {
        $this->services[] = $services;

        return $this;
    }

    /**
     * Remove services
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPServices $services
     */
    public function removeService(\bs\IDP\BackofficeBundle\Entity\IDPServices $services)
    {
        $this->services->removeElement($services);
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
     * Set localization
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPLocalizations $localization
     * @return IDPArchive
     */
    public function setLocalization(\bs\IDP\BackofficeBundle\Entity\IDPLocalizations $localization = null)
    {
        $this->localization = $localization;

        return $this;
    }

    /**
     * Get localization
     *
     * @return \bs\IDP\ArchiveBundle\Entity\IDPLocalizations
     */
    public function getLocalization()
    {
        return $this->localization;
    }

}
