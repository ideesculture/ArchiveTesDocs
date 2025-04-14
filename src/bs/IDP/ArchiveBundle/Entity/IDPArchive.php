<?php
// (c) Archimage - IDP Consulting 2016
// Author: Cyril PATRIGEON - BeSecure Labs
//
/* -- Modifications --
v0.4.0: add precision datas to manage all precisions in one storage, instead of one storage per function. i.e will replace consultask, returnask,...
*/

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IDPArchive
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\ArchiveBundle\Entity\IDPArchiveRepository")
 */
class IDPArchive
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
     * @ORM\ManyToOne(targetEntity="\bs\Core\UsersBundle\Entity\bsUsers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     **/
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\Core\UsersBundle\Entity\bsUsers")
     * @ORM\JoinColumn(name="lastactionby_id", referencedColumnName="id", nullable=true)
     **/
    private $lastactionby;

    /**
     * @var integer
     *
     * @ORM\Column(name="import_id", type="integer", nullable=true)
     */
    private $import_id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=2000, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="IDPArchivesStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=true)
     **/
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="ordernumber", type="string", length=255, nullable=true)
     */
    private $ordernumber;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPBudgetCodes")
     * @ORM\JoinColumn(name="budgetcode_id", referencedColumnName="id", nullable=true)
     **/
    private $budgetcode;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPLocalizations")
     * @ORM\JoinColumn(name="localization_id", referencedColumnName="id", nullable=true)
     **/
    private $localization;

    /**
     * @var string
     *
     * @ORM\Column(name="localizationfree", type="string", length=255, nullable=true)
     */
    private $localizationfree;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPLocalizations")
     * @ORM\JoinColumn(name="oldlocalization_id", referencedColumnName="id", nullable=true)
     **/
    private $oldlocalization;

    /**
     * @var string
     *
     * @ORM\Column(name="oldlocalizationfree", type="string", length=255, nullable=true)
     */
    private $oldlocalizationfree;

    /**
     * @var integer
     *
     * @ORM\Column(name="closureyear", type="integer", nullable=true)
     */
    private $closureyear;

    /**
     * @var integer
     *
     * @ORM\Column(name="destructionyear", type="integer", nullable=true)
     */
    private $destructionyear;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPServices")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=true)
     **/
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPLegalEntities")
     * @ORM\JoinColumn(name="legalentity_id", referencedColumnName="id", nullable=true)
     **/
    private $legalentity;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures")
     * @ORM\JoinColumn(name="documentnature_id", referencedColumnName="id", nullable=true)
     **/
    private $documentnature;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPDocumentTypes")
     * @ORM\JoinColumn(name="documenttype_id", referencedColumnName="id", nullable=true)
     **/
    private $documenttype;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPDescriptions1")
     * @ORM\JoinColumn(name="description1_id", referencedColumnName="id", nullable=true)
     **/
    private $description1;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPDescriptions2")
     * @ORM\JoinColumn(name="description2_id", referencedColumnName="id", nullable=true)
     **/
    private $description2;

    /**
     * @var integer
     *
     * @ORM\Column(name="limitnummin", type="integer", nullable=true)
     */
    private $limitnummin;

    /**
     * @var integer
     *
     * @ORM\Column(name="limitnummax", type="integer", nullable=true)
     */
    private $limitnummax;

    /**
     * @var string
     *
     * @ORM\Column(name="limitalphanummin", type="string", length=255, nullable=true)
     */
    private $limitalphanummin;

    /**
     * @var string
     *
     * @ORM\Column(name="limitalphanummax", type="string", length=255, nullable=true)
     */
    private $limitalphanummax;

    /** @var string
     *
     * @ORM\Column(name="limitalphamin", type="string", length=255, nullable=true)
     */
    private $limitalphamin;

    /**
     * @var string
     *
     * @ORM\Column(name="limitalphamax", type="string", length=255, nullable=true)
     */
    private $limitalphamax;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="limitdatemin", type="date", nullable=true )
     */
    private $limitdatemin;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="limitdatemax", type="date", nullable=true )
     */
    private $limitdatemax;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=true )
     */
    private $createdat;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="modifiedat", type="datetime", nullable=true )
     */
    private $modifiedat;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="precisiondate", type="datetime", nullable=true)
     */
    private $precisiondate;

    /**
     * @ORM\ManyToOne( targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPDeliverAddress" )
     * @ORM\JoinColumn( name="precisionaddress_id", referencedColumnName="id", nullable=true )
     */
    private $precisionwhere;

    /**
     * @var string
     *
     * @ORM\Column( name="precisionfloor", type="string", length=255, nullable=true )
     */
    private $precisionfloor;

    /**
     * @var string
     *
     * @ORM\Column( name="precisionoffice", type="string", length=255, nullable=true )
     */
    private $precisionoffice;

    /**
     * @var string
     *
     * @ORM\Column( name="precisionwho", type="string", length=255, nullable=true )
     */
    private $precisionwho;

    /**
     * @var string
     *
     * @ORM\Column( name="precisioncomment", type="string", length=255, nullable=true )
     */
    private $precisioncomment;

    /**
     * @var string
     *
     * @ORM\Column(name="documentnumber", type="string", length=255, nullable=true )
     */
    private $documentnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="boxnumber", type="string", length=255, nullable=true )
     */
    private $boxnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="containernumber", type="string", length=255, nullable=true )
     */
    private $containernumber;

    /**
     * @ORM\ManyToOne(targetEntity="\bs\IDP\BackofficeBundle\Entity\IDPProviders")
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id", nullable=true)
     **/
    private $provider;

    /**
     * @var integer
     *
     * @ORM\Column(name="locked", type="integer", nullable=true )
     */
    private $locked;

    /**
     * @var integer
     *
     * @ORM\Column(name="lockbegintime", type="integer", nullable=true )
     */
    private $lockbegintime;

    /**
     * @var integer
     *
     * @ORM\Column(name="serviceentrydate", type="integer", nullable=true )
     */
    private $serviceentrydate;

    /**
     * @var integer
     *
     * @ORM\Column(name="saveserviceentrydate", type="integer", nullable=true )
     */
    private $saveserviceentrydate;

    /**
     * @var integer
     *
     * @ORM\Column(name="containerasked", type="integer" )
     */
    private $containerasked = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="boxasked", type="integer" )
     */
    private $boxasked = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="objecttype", type="integer", nullable=true )
     */
    private $objecttype;
    /**
     * @var integer
     *
     * @ORM\Column(name="futureobjecttype", type="integer", nullable=true )
     */
    private $futureobjecttype;

    /**
     * @var unlimited
     *
     * @ORM\Column(name="unlimited", type="integer", nullable=true )
     */
    private $unlimited;

    /**
     * @var unlimitedcomments
     *
     * @ORM\Column(name="unlimitedcomments", type="string", nullable=true )
     */
    private $unlimitedcomments;

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
     * Set owner
     *
     * @param \bs\Core\UsersBundle\Entity\bsUsers $owner
     * @return IDPArchive
     */
    public function setOwner(\bs\Core\UsersBundle\Entity\bsUsers $owner )
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \bs\Core\UsersBundle\Entity\bsUsers
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set lastactionby
     *
     * @param \bs\Core\UsersBundle\Entity\bsUsers $lastactionby
     * @return IDPArchive
     */
    public function setLastactionby(\bs\Core\UsersBundle\Entity\bsUsers $lastactionby = null )
    {
        $this->lastactionby = $lastactionby;

        return $this;
    }

    /**
     * Get lastactionby
     *
     * @return \bs\Core\UsersBundle\Entity\bsUsers
     */
    public function getLastactionby()
    {
        return $this->lastactionby;
    }

    /**
     * Set import_id
     *
     * @param integer $import_id
     * @return IDPArchive
     */
    public function setImportId($import_id)
    {
        $this->import_id = $import_id;

        return $this;
    }

    /**
     * Get import_id
     *
     * @return integer
     */
    public function getImportId()
    {
        return $this->import_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return IDPArchive
     */
    public function setName($name)
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
     * Set status
     *
     * @param \bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus $status
     * @return IDPArchive
     */
    public function setStatus(\bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set ordernumber
     *
     * @param string $ordernumber
     * @return IDPArchive
     */
    public function setOrdernumber($ordernumber)
    {
        $this->ordernumber = $ordernumber;

        return $this;
    }

    /**
     * Get ordernumber
     *
     * @return string
     */
    public function getOrdernumber()
    {
        return $this->ordernumber;
    }

    /**
     * Set budgetcode
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPBudgetCodes $budgetcode
     * @return IDPArchive
     */
    public function setBudgetcode(\bs\IDP\BackofficeBundle\Entity\IDPBudgetCodes $budgetcode = null)
    {
        $this->budgetcode = $budgetcode;

        return $this;
    }

    /**
     * Get budgetcode
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPBudgetCodes
     */
    public function getBudgetcode()
    {
        return $this->budgetcode;
    }

    /**
     * Set localization
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPLocalizations $localization
     * @return IDPArchive
     */
    public function setLocalization(\bs\IDP\BackofficeBundle\Entity\IDPLocalizations $localization = null )
    {
        $this->localization = $localization;

        return $this;
    }

    /**
     * Get localization
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPLocalizations
     */
    public function getLocalization()
    {
        return $this->localization;
    }

    /**
     * Set localizationfree
     *
     * @param string $localizationfree
     * @return IDPArchive
     */
    public function setLocalizationfree($localizationfree)
    {
        $this->localizationfree = $localizationfree;

        return $this;
    }

    /**
     * Get localizationfree
     *
     * @return string
     */
    public function getLocalizationfree()
    {
        return $this->localizationfree;
    }

    /**
     * Set oldlocalization
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPLocalizations $oldlocalization
     * @return IDPArchive
     */
    public function setOldlocalization(\bs\IDP\BackofficeBundle\Entity\IDPLocalizations $oldlocalization = null )
    {
        $this->oldlocalization = $oldlocalization;

        return $this;
    }

    /**
     * Get oldlocalization
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPLocalizations
     */
    public function getOldlocalization()
    {
        return $this->oldlocalization;
    }

    /**
     * Set oldlocalizationfree
     *
     * @param string $oldlocalizationfree
     * @return IDPArchive
     */
    public function setOldlocalizationfree($oldlocalizationfree)
    {
        $this->oldlocalizationfree = $oldlocalizationfree;

        return $this;
    }

    /**
     * Get oldlocalizationfree
     *
     * @return string
     */
    public function getOldlocalizationfree()
    {
        return $this->oldlocalizationfree;
    }

    /**
     * Set closureyear
     *
     * @param integer $closureyear
     * @return IDPArchive
     */
    public function setClosureyear($closureyear)
    {
        $this->closureyear = $closureyear;

        return $this;
    }

    /**
     * Get closureyear
     *
     * @return integer
     */
    public function getClosureyear()
    {
        return $this->closureyear;
    }

    /**
     * Set destructionyear
     *
     * @param integer $destructionyear
     * @return IDPArchive
     */
    public function setDestructionyear($destructionyear)
    {
        $this->destructionyear = $destructionyear;

        return $this;
    }

    /**
     * Get destructionyear
     *
     * @return integer
     */
    public function getDestructionyear()
    {
        return $this->destructionyear;
    }

    /**
     * Set service
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPServices $service
     * @return IDPArchive
     */
    public function setService(\bs\IDP\BackofficeBundle\Entity\IDPServices $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPServices
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set legalentity
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPLegalEntities $legalentity
     * @return IDPArchive
     */
    public function setLegalentity(\bs\IDP\BackofficeBundle\Entity\IDPLegalEntities $legalentity = null)
    {
        $this->legalentity = $legalentity;

        return $this;
    }

    /**
     * Get legalentity
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPLegalEntities
     */
    public function getLegalentity()
    {
        return $this->legalentity;
    }

    /**
     * Set documentnature
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures $documentnature
     * @return IDPArchive
     */
    public function setDocumentnature(\bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures $documentnature = null)
    {
        $this->documentnature = $documentnature;

        return $this;
    }

    /**
     * Get documentnature
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures
     */
    public function getDocumentnature()
    {
        return $this->documentnature;
    }

    /**
     * Set documenttype
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDocumentTypes $documenttype
     * @return IDPArchive
     */
    public function setDocumenttype(\bs\IDP\BackofficeBundle\Entity\IDPDocumentTypes $documenttype = null)
    {
        $this->documenttype = $documenttype;

        return $this;
    }

    /**
     * Get documenttype
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPDocumentTypes
     */
    public function getDocumenttype()
    {
        return $this->documenttype;
    }

    /**
     * Set description1
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDescriptions1 $description1
     * @return IDPArchive
     */
    public function setDescription1(\bs\IDP\BackofficeBundle\Entity\IDPDescriptions1 $description1 = null)
    {
        $this->description1 = $description1;

        return $this;
    }

    /**
     * Get description1
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPDescriptions1
     */
    public function getDescription1()
    {
        return $this->description1;
    }

    /**
     * Set description2
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDescriptions2 $description2
     * @return IDPArchive
     */
    public function setDescription2(\bs\IDP\BackofficeBundle\Entity\IDPDescriptions2 $description2 = null)
    {
        $this->description2 = $description2;

        return $this;
    }

    /**
     * Get description2
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPDescriptions2
     */
    public function getDescription2()
    {
        return $this->description2;
    }

    /**
     * Set limitnummin
     *
     * @param integer $limitnummin
     * @return IDPArchive
     */
    public function setLimitnummin($limitnummin)
    {
        $this->limitnummin = $limitnummin;

        return $this;
    }

    /**
     * Get limitnummin
     *
     * @return integer
     */
    public function getLimitnummin()
    {
        return $this->limitnummin;
    }

    /**
     * Set limitnummax
     *
     * @param integer $limitnummax
     * @return IDPArchive
     */
    public function setLimitnummax($limitnummax)
    {
        $this->limitnummax = $limitnummax;

        return $this;
    }

    /**
     * Get limitnummax
     *
     * @return integer
     */
    public function getLimitnummax()
    {
        return $this->limitnummax;
    }

    /**
     * Set limitalphanummin
     *
     * @param string $limitalphanummin
     * @return IDPArchive
     */
    public function setLimitalphanummin($limitalphanummin)
    {
        $this->limitalphanummin = $limitalphanummin;

        return $this;
    }

    /**
     * Get limitalphanummin
     *
     * @return string
     */
    public function getLimitalphanummin()
    {
        return $this->limitalphanummin;
    }

    /**
     * Set limitalphanummax
     *
     * @param string $limitalphanummax
     * @return IDPArchive
     */
    public function setLimitalphanummax($limitalphanummax)
    {
        $this->limitalphanummax = $limitalphanummax;

        return $this;
    }

    /**
     * Get limitalphanummax
     *
     * @return string
     */
    public function getLimitalphanummax()
    {
        return $this->limitalphanummax;
    }

    /**
     * Set limitalphamin
     *
     * @param string $limitalphamin
     * @return IDPArchive
     */
    public function setLimitalphamin($limitalphamin)
    {
        $this->limitalphamin = $limitalphamin;

        return $this;
    }

    /**
     * Get limitalphamin
     *
     * @return string
     */
    public function getLimitalphamin()
    {
        return $this->limitalphamin;
    }

    /**
     * Set limitalphamax
     *
     * @param string $limitalphamax
     * @return IDPArchive
     */
    public function setLimitalphamax($limitalphamax)
    {
        $this->limitalphamax = $limitalphamax;

        return $this;
    }

    /**
     * Get limitalphamax
     *
     * @return string
     */
    public function getLimitalphamax()
    {
        return $this->limitalphamax;
    }

    /**
     * Set limitdatemin
     *
     * @param \DateTime $limitdatemin
     * @return IDPArchive
     */
    public function setLimitdatemin($limitdatemin)
    {
        $this->limitdatemin = $limitdatemin;

        return $this;
    }

    /**
     * Get limitdatemin
     *
     * @return \DateTime
     */
    public function getLimitdatemin()
    {
        return $this->limitdatemin;
    }

    /**
     * Set limitdatemax
     *
     * @param \DateTime $limitdatemax
     * @return IDPArchive
     */
    public function setLimitdatemax($limitdatemax)
    {
        $this->limitdatemax = $limitdatemax;

        return $this;
    }

    /**
     * Get limitdatemax
     *
     * @return \DateTime
     */
    public function getLimitdatemax()
    {
        return $this->limitdatemax;
    }

    /**
     * Set createdat
     *
     * @param \DateTime $createdat
     * @return IDPArchive
     */
    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;

        return $this;
    }

    /**
     * Get createdat
     *
     * @return \DateTime
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * Set modifiedat
     *
     * @param \DateTime $modifiedat
     * @return IDPArchive
     */
    public function setModifiedat($modifiedat)
    {
        $this->modifiedat = $modifiedat;

        return $this;
    }

    /**
     * Get modifiedat
     *
     * @return \DateTime
     */
    public function getModifiedat()
    {
        return $this->modifiedat;
    }


    /**
     * Set documentnumber
     *
     * @param string $documentnumber
     * @return IDPArchive
     */
    public function setDocumentnumber($documentnumber)
    {
        $this->documentnumber = $documentnumber;

        return $this;
    }

    /**
     * Get documentnumber
     *
     * @return string
     */
    public function getDocumentnumber()
    {
        return $this->documentnumber;
    }

    /**
     * Set boxnumber
     *
     * @param string $boxnumber
     * @return IDPArchive
     */
    public function setBoxnumber($boxnumber)
    {
        $this->boxnumber = $boxnumber;

        return $this;
    }

    /**
     * Get boxnumber
     *
     * @return string
     */
    public function getBoxnumber()
    {
        return $this->boxnumber;
    }

    /**
     * Set containernumber
     *
     * @param string $containernumber
     * @return IDPArchive
     */
    public function setContainernumber($containernumber)
    {
        $this->containernumber = $containernumber;

        return $this;
    }

    /**
     * Get containernumber
     *
     * @return string
     */
    public function getContainernumber()
    {
        return $this->containernumber;
    }

    /**
     * Set provider
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPProviders $provider
     * @return IDPArchive
     */
    public function setProvider(\bs\IDP\BackofficeBundle\Entity\IDPProviders $provider = null)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPProviders
     */
    public function getProvider()
    {
        return $this->provider;
    }


    /**
     * Set precisiondate
     *
     * @param \DateTime $precisiondate
     * @return IDPArchive
     */
    public function setPrecisiondate( $precisiondate )
    {
        $this->precisiondate = $precisiondate;

        return $this;
    }

    /**
     * Get precisiondate
     *
     * @return \DateTime
     */
    public function getPrecisiondate()
    {
        return $this->precisiondate;
    }

    /**
     * Set precisionwhere
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPDeliverAddress $precisionwhere
     * @return IDPArchive
     */
    public function setPrecisionwhere(\bs\IDP\BackofficeBundle\Entity\IDPDeliverAddress $precisionwhere = null)
    {
        $this->precisionwhere = $precisionwhere;

        return $this;
    }

    /**
     * Get precisionwhere
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPDeliverAddress
     */
    public function getPreicisionwhere()
    {
        return $this->precisionwhere;
    }

    /**
     * Set precisionfloor
     *
     * @param string $precisionfloor
     * @return IDPArchive
     */
    public function setPrecisionfloor( $precisionfloor )
    {
        $this->precisionfloor = $precisionfloor;

        return $this;
    }

    /**
     * Get precisionfloor
     *
     * @return string
     */
    public function getPrecisionfloor()
    {
        return $this->precisionfloor;
    }

    /**
     * Set precisionoffice
     *
     * @param string $precisionoffice
     * @return IDPArchive
     */
    public function setPrecisionoffice( $precisionoffice )
    {
        $this->precisionoffice = $precisionoffice;

        return $this;
    }

    /**
     * Get precisionoffice
     *
     * @return string
     */
    public function getPrecisionoffice()
    {
        return $this->precisionoffice;
    }

    /**
     * Set precisionwho
     *
     * @param string $precisionwho
     * @return IDPArchive
     */
    public function setPrecisionwho( $precisionwho )
    {
        $this->precisionwho = $precisionwho;

        return $this;
    }

    /**
     * Get precisionwho
     *
     * @return string
     */
    public function getPrecisionwho()
    {
        return $this->precisionwho;
    }

    /**
     * Set precisioncomment
     *
     * @param string $precisioncomment
     * @return IDPArchive
     */
    public function setPrecisioncomment( $precisioncomment )
    {
        $this->precisioncomment = $precisioncomment;

        return $this;
    }

    /**
     * Get precisioncomment
     *
     * @return string
     */
    public function getPrecisioncomment()
    {
        return $this->precisioncomment;
    }

    /**
     * Set locked
     *
     * @param integer $locked
     * @return IDPArchive
     */
    public function setLocked( $locked ){
        $this->locked = $locked;

        return $this;
    }
    /**
     * Get locked
     *
     * @return integer
     */
    public function getLocked(  ){
        return $this->locked;
    }

    /**
     * Set lockbegintime
     *
     * @param integer lockbegintime
     * @return IDPArchive
     */
    public function setLockbegintime( $lockbegintime ){
        $this->lockbegintime = $lockbegintime;

        return $this;
    }
    /**
     * Get lockbegintime
     *
     * @return integer
     */
    public function getLockbegintime(  ){
        return $this->lockbegintime;
    }

    /**
     * Set serviceentrydate
     *
     * @param integer serviceentrydate
     * @return IDPArchive
     */
    public function setServiceentrydate( $serviceentrydate ){
        $this->serviceentrydate = $serviceentrydate;

        return $this;
    }
    /**
     * Get serviceentrydate
     *
     * @return integer
     */
    public function getServiceentrydate(  ){
        return $this->serviceentrydate;
    }

    /**
     * Set saveserviceentrydate
     *
     * @param integer saveserviceentrydate
     * @return IDPArchive
     */
    public function setSaveserviceentrydate( $saveserviceentrydate ){
        $this->saveserviceentrydate = $saveserviceentrydate;

        return $this;
    }
    /**
     * Get saveserviceentrydate
     *
     * @return integer
     */
    public function getSaveserviceentrydate(  ){
        return $this->saveserviceentrydate;
    }

    /**
     * Set containerasked
     *
     * @param integer $containerasked
     * @return IDPArchive
     */
    public function setContainerasked( $containerasked ){
        $this->containerasked = $containerasked;

        return $this;
    }
    /**
     * Get containerasked
     *
     * @return integer
     */
    public function getContainerasked(  ){
        return $this->containerasked;
    }

    /**
     * Set boxasked
     *
     * @param integer $boxasked
     * @return IDPArchive
     */
    public function setBoxasked( $boxasked ){
        $this->boxasked = $boxasked;

        return $this;
    }
    /**
     * Get boxasked
     *
     * @return integer
     */
    public function getBoxasked(  ){
        return $this->boxasked;
    }

    /**
     * Set unlimited
     *
     * @param integer unlimited
     * @return IDPArchive
     */
    public function setUnlimited( $unlimited ){
        $this->unlimited = $unlimited;

        return $this;
    }
    /**
     * Get unlimited
     *
     * @return integer
     */
    public function getUnlimited( ){
        return $this->unlimited;
    }

    /**
     * Set unlimitedcomments
     *
     * @param string unlimitedcomments
     * @return IDPArchive
     */
    public function setUnlimitedcomments( $unlimitedcomments ){
        $this->unlimitedcomments = $unlimitedcomments;

        return $this;
    }
    /**
     * Get unlimitedcomments
     *
     * @return string
     */
    public function getUnlimitedcomments(){
        return $this->unlimitedcomments;
    }

    /**
     * Set objecttype
     *
     * @param integer objecttype
     * @return IDPArchive
     */
    public function setObjecttype( $objecttype ){
        $this->objecttype = $objecttype;
        return $this;
    }
    /**
     * Get objecttype
     *
     * @return integer
     */
    public function getObjecttype()
    {
        return $this->objecttype;
    }

    /**
     * Set futureobjecttype
     *
     * @param integer futureobjecttype
     * @return IDPArchive
     */
    public function setFutureobjecttype( $futureobjecttype ){
        $this->futureobjecttype = $futureobjecttype;
        return $this;
    }
    /**
     * Get futureobjecttype
     *
     * @return integer
     */
    public function getFutureobjecttype()
    {
        return $this->futureobjecttype;
    }

}
