<?php

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IDPDeletedArchive
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\ArchiveBundle\Entity\IDPDeletedArchiveRepository")
 */
class IDPDeletedArchive
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
	 * @ORM\Column(name="name", type="string", length=1000, nullable=true)
	 */
	private $name;

	/**
	 * @var string
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
	 **/
	private $status;

    /**
     * @var string
     * @ORM\Column(name="ordernumber", type="string", length=255, nullable=true)
     **/
    private $ordernumber;

    /**
     * @var string
     * @ORM\Column(name="budgetcode", type="string", length=255, nullable=true)
     **/
    private $budgetcode;

    /**
     * @var string
     * @ORM\Column(name="localization", type="string", length=255, nullable=true)
     **/
    private $localization;

    /**
     * @var string
     * @ORM\Column(name="localizationfree", type="string", length=255, nullable=true)
     **/
    private $localizationfree;

    /**
     * @var integer
     * @ORM\Column(name="closureyear", type="integer", nullable=true)
     **/
    private $closureyear;

    /**
     * @var integer
     * @ORM\Column(name="destructionyear", type="integer", nullable=true)
     **/
    private $destructionyear;

    /**
     * @var string
     * @ORM\Column(name="service", type="string", length=255, nullable=true)
     **/
    private $service;

    /**
     * @var string
     * @ORM\Column(name="legalentity", type="string", length=255, nullable=true)
     **/
    private $legalentity;

    /**
     * @var string
     * @ORM\Column(name="documentnature", type="string", length=255, nullable=true)
     **/
    private $documentnature;

    /**
     * @var string
     * @ORM\Column(name="documenttype", type="string", length=255, nullable=true)
     **/
    private $documenttype;

    /**
     * @var string
     * @ORM\Column(name="description1", type="string", length=255, nullable=true)
     **/
    private $description1;

    /**
     * @var string
     * @ORM\Column(name="description2", type="string", length=255, nullable=true)
     **/
    private $description2;

    /**
     * @var integer
     * @ORM\Column(name="limitnummin", type="integer", nullable=true)
     **/
    private $limitnummin;

    /**
     * @var integer
     * @ORM\Column(name="limitnummax", type="integer", nullable=true)
     **/
    private $limitnummax;

    /**
     * @var string
     * @ORM\Column(name="limitalphamin", type="string", length=255, nullable=true)
     **/
    private $limitalphamin;

    /**
     * @var string
     * @ORM\Column(name="limitalphamax", type="string", length=255, nullable=true)
     **/
    private $limitalphamax;

    /**
     * @var string
     * @ORM\Column(name="limitalphanummin", type="string", length=255, nullable=true)
     **/
    private $limitalphanummin;

    /**
     * @var string
     * @ORM\Column(name="limitalphanummax", type="string", length=255, nullable=true)
     **/
    private $limitalphanummax;

    /**
     * @var DateTime
     * @ORM\Column(name="limitdatemin", type="datetime", nullable=true)
     **/
    private $limitdatemin;

    /**
     * @var DateTime
     * @ORM\Column(name="limitdatemax", type="datetime", nullable=true)
     **/
    private $limitdatemax;

    /**
     * @var string
     * @ORM\Column(name="documentnumber", type="string", length=255, nullable=true)
     **/
    private $documentnumber;

    /**
     * @var string
     * @ORM\Column(name="boxnumber", type="string", length=255, nullable=true)
     **/
    private $boxnumber;

    /**
     * @var string
     * @ORM\Column(name="containernumber", type="string", length=255, nullable=true)
     **/
    private $containernumber;

    /**
     * @var string
     * @ORM\Column(name="provider", type="string", length=255, nullable=true)
     **/
    private $provider;


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
	 * Set name
	 * @param string $name
	 * @return IDPDeletedArchive
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

    /**
     * Set status
     * @param string $status
     * @return IDPDeletedArchive
     */
    public function setStatus( $status )
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set ordernumber
     * @param string $ordernumber
     * @return IDPDeletedArchive
     */
    public function setOrdernumber( $ordernumber )
    {
        $this->ordernumber = $ordernumber;

        return $this;
    }

    /**
     * Get ordernumber
     * @return string
     */
    public function getOrdernumber()
    {
        return $this->ordernumber;
    }

    /**
     * Set budgetcode
     * @param string $budgetcode
     * @return IDPDeletedArchive
     */
    public function setBudgetcode( $budgetcode )
    {
        $this->budgetcode = $budgetcode;

        return $this;
    }

    /**
     * Get budgetcode
     * @return string
     */
    public function getBudgetcode()
    {
        return $this->budgetcode;
    }

    /**
     * Set localization
     * @param string $localization
     * @return IDPDeletedArchive
     */
    public function setLocalization( $localization )
    {
        $this->localization = $localization;

        return $this;
    }

    /**
     * Get localization
     * @return string
     */
    public function getLocalization()
    {
        return $this->localization;
    }

    /**
     * Set localizationfree
     * @param string $localizationfree
     * @return IDPDeletedArchive
     */
    public function setLocalizationfree( $localizationfree )
    {
        $this->localizationfree = $localizationfree;

        return $this;
    }

    /**
     * Get localizationfree
     * @return string
     */
    public function getLocalizationfree()
    {
        return $this->localizationfree;
    }

    /**
     * Set closureyear
     * @param integer $closureyear
     * @return IDPDeletedArchive
     */
    public function setClosureyear( $closureyear )
    {
        $this->closureyear = $closureyear;

        return $this;
    }

    /**
     * Get closureyear
     * @return integer
     */
    public function getClosureyear()
    {
        return $this->closureyear;
    }

    /**
     * Set destructionyear
     * @param integer $destructionyear
     * @return IDPDeletedArchive
     */
    public function setDestructionyear( $destructionyear )
    {
        $this->destructionyear = $destructionyear;

        return $this;
    }

    /**
     * Get destructionyear
     * @return integer
     */
    public function getDestructionyear()
    {
        return $this->destructionyear;
    }

    /**
     * Set service
     * @param string $service
     * @return IDPDeletedArchive
     */
    public function setService( $service )
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set legalentity
     * @param string $legalentity
     * @return IDPDeletedArchive
     */
    public function setLegalentity( $legalentity )
    {
        $this->legalentity = $legalentity;

        return $this;
    }

    /**
     * Get legalentity
     * @return string
     */
    public function getLegalentity()
    {
        return $this->legalentity;
    }

    /**
     * Set documentnature
     * @param string $documentnature
     * @return IDPDeletedArchive
     */
    public function setDocumentnature( $documentnature )
    {
        $this->documentnature = $documentnature;

        return $this;
    }

    /**
     * Get documentnature
     * @return string
     */
    public function getDocumentnature()
    {
        return $this->documentnature;
    }

    /**
     * Set documenttype
     * @param string $documenttype
     * @return IDPDeletedArchive
     */
    public function setDocumenttype( $documenttype )
    {
        $this->documenttype = $documenttype;

        return $this;
    }

    /**
     * Get documenttype
     * @return string
     */
    public function getDocumentype()
    {
        return $this->documenttype;
    }

    /**
     * Set description1
     * @param string $description1
     * @return IDPDeletedArchive
     */
    public function setDescription1( $description1 )
    {
        $this->description1 = $description1;

        return $this;
    }

    /**
     * Get description1
     * @return string
     */
    public function getDescription1()
    {
        return $this->description1;
    }

    /**
     * Set description2
     * @param string $description2
     * @return IDPDeletedArchive
     */
    public function setDescription2( $description2 )
    {
        $this->description2 = $description2;

        return $this;
    }

    /**
     * Get description2
     * @return string
     */
    public function getDescription2()
    {
        return $this->description2;
    }

    /**
     * Set limitnummin
     * @param integer $limitnummin
     * @return IDPDeletedArchive
     */
    public function setLimitnummin( $limitnummin )
    {
        $this->limitnummin = $limitnummin;

        return $this;
    }

    /**
     * Get limitnummin
     * @return integer
     */
    public function getLimitnummin()
    {
        return $this->limitnummin;
    }

    /**
     * Set limitnummax
     * @param integer $limitnummax
     * @return IDPDeletedArchive
     */
    public function setLimitnummax( $limitnummax )
    {
        $this->limitnummax = $limitnummax;

        return $this;
    }

    /**
     * Get limitnummax
     * @return string
     */
    public function getLimitnummax()
    {
        return $this->limitnummax;
    }

    /**
     * Set limitalphamin
     * @param string $limitalphamin
     * @return IDPDeletedArchive
     */
    public function setLimitalphamin( $limitalphamin )
    {
        $this->limitalphamin = $limitalphamin;

        return $this;
    }

    /**
     * Get limitalphamin
     * @return string
     */
    public function getLimitalphamin()
    {
        return $this->limitalphamin;
    }

    /**
     * Set limitalphamax
     * @param string $limitalphamax
     * @return IDPDeletedArchive
     */
    public function setLimitalphamax( $limitalphamax )
    {
        $this->limitalphamax = $limitalphamax;

        return $this;
    }

    /**
     * Get limitalphamax
     * @return string
     */
    public function getLimitalphamax()
    {
        return $this->limitalphamax;
    }

    /**
     * Set limitalphanummin
     * @param string $limitalphanummin
     * @return IDPDeletedArchive
     */
    public function setLimitalphanummin( $limitalphanummin )
    {
        $this->limitalphanummin = $limitalphanummin;

        return $this;
    }

    /**
     * Get limitalphanummin
     * @return string
     */
    public function getLimitalphanummin()
    {
        return $this->limitalphanummin;
    }

    /**
     * Set limitalphanummax
     * @param string $limitalphanummax
     * @return IDPDeletedArchive
     */
    public function setLimitalphanummax( $limitalphanummax )
    {
        $this->limitalphanummax = $limitalphanummax;

        return $this;
    }

    /**
     * Get limitalphanummax
     * @return string
     */
    public function getLimitalphanummax()
    {
        return $this->limitalphanummax;
    }

    /**
     * Set limitdatemin
     * @param \DateTime $limitdatemin
     * @return IDPDeletedArchive
     */
    public function setLimitdatemin( $limitdatemin )
    {
        $this->limitdatemin = $limitdatemin;

        return $this;
    }

    /**
     * Get limitdatemin
     * @return \DateTime
     */
    public function getLimitdatemin()
    {
        return $this->limitdatemin;
    }

    /**
     * Set limitdatemax
     * @param \DateTime $limitdatemax
     * @return IDPDeletedArchive
     */
    public function setLimitdatemax( $limitdatemax )
    {
        $this->limitdatemax = $limitdatemax;

        return $this;
    }

    /**
     * Get limitdatemax
     * @return \DateTime
     */
    public function getLimitdatemax()
    {
        return $this->limitdatemax;
    }


    /**
     * Set documentnumber
     * @param string $documentnumber
     * @return IDPDeletedArchive
     */
    public function setDocumentnumber( $documentnumber )
    {
        $this->documentnumber = $documentnumber;

        return $this;
    }

    /**
     * Get documentnumber
     * @return string
     */
    public function getDocumentnumber()
    {
        return $this->documentnumber;
    }

    /**
     * Set boxnumber
     * @param string $boxnumber
     * @return IDPDeletedArchive
     */
    public function setBoxnumber( $boxnumber )
    {
        $this->boxnumber = $boxnumber;

        return $this;
    }

    /**
     * Get boxnumber
     * @return string
     */
    public function getBoxnumber()
    {
        return $this->boxnumber;
    }

    /**
     * Set containernumber
     * @param string $containernumber
     * @return IDPDeletedArchive
     */
    public function setContainernumber( $containernumber )
    {
        $this->containernumber = $containernumber;

        return $this;
    }

    /**
     * Get containernumber
     * @return string
     */
    public function getContainernumber()
    {
        return $this->containernumber;
    }

    /**
     * Set provider
     * @param string $provider
     * @return IDPDeletedArchive
     */
    public function setProvider( $provider )
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

}
