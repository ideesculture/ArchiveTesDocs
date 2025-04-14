<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPServices
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPServicesRepository")
 */
class IDPServices
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
     * @ORM\Column(name="longname", type="string", length=1000)
     */
    private $longname;

	/**
	 * @ORM\ManyToMany( targetEntity="IDPLegalEntities", mappedBy="services" )
	 **/
	private $legalEntities;

	/**
	 * @ORM\ManyToMany( targetEntity="IDPDocumentNatures", mappedBy="services" )
	 **/
	private $documentNatures;

	/**
	 * @ORM\ManyToMany( targetEntity="IDPDescriptions1", mappedBy="services" )
	 **/
	private $descriptions1;

	/**
	 * @ORM\ManyToMany( targetEntity="IDPDescriptions2", mappedBy="services" )
	 **/
	private $descriptions2;

	/**
	 * @ORM\ManyToMany( targetEntity="IDPBudgetCodes", mappedBy="services" )
	 **/
	private $budgetCodes;

	/**
	 * @ORM\ManyToMany( targetEntity="IDPProviders", mappedBy="services" )
	 **/
	private $providers;

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
     * @return IDPServices
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
        $this->descriptions1 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->descriptions2 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->budgetCodes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->providers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add legalEntities
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPLegalEntities $legalEntities
     * @return IDPServices
     */
    public function addLegalEntity(\bs\IDP\BackofficeBundle\Entity\IDPLegalEntities $legalEntities)
    {
        $this->legalEntities[] = $legalEntities;

        return $this;
    }

    /**
     * Remove legalEntities
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPLegalEntities $legalEntities
     */
    public function removeLegalEntity(\bs\IDP\BackofficeBundle\Entity\IDPLegalEntities $legalEntities)
    {
        $this->legalEntities->removeElement($legalEntities);
    }

    /**
     * Get legalEntities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLegalEntities()
    {
        return $this->legalEntities;
    }

	/**
	 * Add documentNatures
	 *
	 * @param \bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures $documentNatures
	 * @return IDPServices
	 */
	public function addDocumentNatures(\bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures $documentNatures)
	{
		$this->documentNatures[] = $documentNatures;

		return $this;
	}

	/**
	 * Remove documentNatures
	 *
	 * @param \bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures $documentNatures
	 */
	public function removeDocumentNatures(\bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures $documentNatures)
	{
		$this->documentNatures->removeElement($documentNatures);
	}

	/**
	 * Get documentNatures
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDocumentNatures()
	{
		return $this->documentNatures;
	}

	/**
     * Add descriptions1
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDescriptions1 $descriptions1
     * @return IDPServices
     */
    public function addDescriptions1(\bs\IDP\BackofficeBundle\Entity\IDPDescriptions1 $descriptions1)
    {
        $this->descriptions1[] = $descriptions1;

        return $this;
    }

    /**
     * Remove descriptions1
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDescriptions1 $descriptions1
     */
    public function removeDescriptions1(\bs\IDP\BackofficeBundle\Entity\IDPDescriptions1 $descriptions1)
    {
        $this->descriptions1->removeElement($descriptions1);
    }

    /**
     * Get descriptions1
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDescriptions1()
    {
        return $this->descriptions1;
    }

    /**
     * Add descriptions2
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDescriptions2 $descriptions2
     * @return IDPServices
     */
    public function addDescriptions2(\bs\IDP\BackofficeBundle\Entity\IDPDescriptions2 $descriptions2)
    {
        $this->descriptions2[] = $descriptions2;

        return $this;
    }

    /**
     * Remove descriptions2
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDescriptions2 $descriptions2
     */
    public function removeDescriptions2(\bs\IDP\BackofficeBundle\Entity\IDPDescriptions2 $descriptions2)
    {
        $this->descriptions2->removeElement($descriptions2);
    }

    /**
     * Get descriptions2
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDescriptions2()
    {
        return $this->descriptions2;
    }

    /**
     * Add budgetCodes
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPBudgetCodes $budgetCodes
     * @return IDPServices
     */
    public function addBudgetCode(\bs\IDP\BackofficeBundle\Entity\IDPBudgetCodes $budgetCodes)
    {
        $this->budgetCodes[] = $budgetCodes;

        return $this;
    }

    /**
     * Remove budgetCodes
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPBudgetCodes $budgetCodes
     */
    public function removeBudgetCode(\bs\IDP\BackofficeBundle\Entity\IDPBudgetCodes $budgetCodes)
    {
        $this->budgetCodes->removeElement($budgetCodes);
    }

    /**
     * Get budgetCodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBudgetCodes()
    {
        return $this->budgetCodes;
    }

    /**
     * Add providers
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPProviders $providers
     * @return IDPServices
     */
    public function addProvider(\bs\IDP\BackofficeBundle\Entity\IDPProviders $providers)
    {
        $this->providers[] = $providers;

        return $this;
    }

    /**
     * Remove providers
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPProviders $providers
     */
    public function removeProvider(\bs\IDP\BackofficeBundle\Entity\IDPProviders $providers)
    {
        $this->providers->removeElement($providers);
    }

    /**
     * Get providers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProviders()
    {
        return $this->providers;
    }



    /**
     * Add providers
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPProviders $providers
     * @return IDPServices
     */
}
