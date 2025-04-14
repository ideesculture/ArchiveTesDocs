<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPServiceSettings
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPServiceSettingsRepository")
 */
class IDPServiceSettings
{
    const COMMON_SERVICE_SETTINGS_BUDGET_CODE       = 1;
    const COMMON_SERVICE_SETTINGS_DOCUMENT_NATURE   = 2;
    const COMMON_SERVICE_SETTINGS_DOCUMENT_TYPE     = 3;
    const COMMON_SERVICE_SETTINGS_DESCRIPTION_1     = 4;
    const COMMON_SERVICE_SETTINGS_DESCRIPTION_2     = 5;
    const COMMON_SERVICE_SETTINGS_DOCUMENT_NUMBER   = 6;
    const COMMON_SERVICE_SETTINGS_BOX_NUMBER        = 7;
    const COMMON_SERVICE_SETTINGS_CONTAINER_NUMBER  = 8;
    const COMMON_SERVICE_SETTINGS_PROVIDER          = 9;
    const COMMON_SERVICE_SETTINGS_LIMITS_DATE       = 10;
    const COMMON_SERVICE_SETTINGS_LIMITS_NUM        = 11;
    const COMMON_SERVICE_SETTINGS_LIMITS_ALPHA      = 12;
    const COMMON_SERVICE_SETTINGS_LIMITS_ALPHANUM   = 13;

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
     * @ORM\Column( name="service_id", type="integer", nullable=true )
     */
    private $service_id;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_budgetcode", type="boolean" )
	 */
	private $view_budgetcode;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_budgetcode", type="boolean" )
	 */
	private $mandatory_budgetcode;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_documentnature", type="boolean" )
	 */
	private $view_documentnature;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_documentnature", type="boolean" )
	 */
	private $mandatory_documentnature;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_documenttype", type="boolean" )
	 */
	private $view_documenttype;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_documenttype", type="boolean" )
	 */
	private $mandatory_documenttype;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_description1", type="boolean" )
	 */
	private $view_description1;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_description1", type="boolean" )
	 */
	private $mandatory_description1;

	/**
	 * @var string
	 *
	 * @ORM\Column( name="name_description1", type="string", nullable=true )
	 */
	private $name_description1;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_description2", type="boolean" )
	 */
	private $view_description2;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_description2", type="boolean" )
	 */
	private $mandatory_description2;

	/**
	 * @var string
	 *
	 * @ORM\Column( name="name_description2", type="string", nullable=true )
	 */
	private $name_description2;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_limitsnum", type="boolean" )
	 */
	private $view_limitsnum;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_limitsnum", type="boolean" )
	 */
	private $mandatory_limitsnum;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_limitsalpha", type="boolean" )
	 */
	private $view_limitsalpha;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_limitsalpha", type="boolean" )
	 */
	private $mandatory_limitsalpha;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_limitsalphanum", type="boolean" )
	 */
	private $view_limitsalphanum;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_limitsalphanum", type="boolean" )
	 */
	private $mandatory_limitsalphanum;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_limitsdate", type="boolean" )
	 */
	private $view_limitsdate;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_limitsdate", type="boolean" )
	 */
	private $mandatory_limitsdate;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_filenumber", type="boolean" )
	 */
	private $view_filenumber;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_filenumber", type="boolean" )
	 */
	private $mandatory_filenumber;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_boxnumber", type="boolean" )
	 */
	private $view_boxnumber;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_boxnumber", type="boolean" )
	 */
	private $mandatory_boxnumber;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_containernumber", type="boolean" )
	 */
	private $view_containernumber;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_containernumber", type="boolean" )
	 */
	private $mandatory_containernumber;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="view_provider", type="boolean" )
	 */
	private $view_provider;

	/**
	 * @var boolean
	 *
	 * @ORM\Column( name="mandatory_provider", type="boolean" )
	 */
	private $mandatory_provider;

	/**
	 * @var integer
	 *
	 * @ORM\Column( name="default_language", type="integer", nullable=true )
	 */
	private $default_language;

    /**
     * @var boolean
     *
     * @ORM\Column( name="view_transfer_internal_basket", type="boolean" )
     */
    private $view_transfer_internal_basket;

    /**
     * @var boolean
     *
     * @ORM\Column( name="view_transfer_intermediate_basket", type="boolean" )
     */
    private $view_transfer_intermediate_basket;

    /**
     * @var boolean
     *
     * @ORM\Column( name="view_transfer_provider_basket", type="boolean" )
     */
    private $view_transfer_provider_basket;

    /**
     * @var boolean
     *
     * @ORM\Column( name="view_reloc_internal_basket", type="boolean" )
     */
    private $view_reloc_internal_basket;

    /**
     * @var boolean
     *
     * @ORM\Column( name="view_reloc_intermediate_basket", type="boolean" )
     */
    private $view_reloc_intermediate_basket;

    /**
     * @var boolean
     *
     * @ORM\Column( name="view_reloc_provider_basket", type="boolean" )
     */
    private $view_reloc_provider_basket;

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
     * Set service_id
     *
     * @param integer $service_id
     * @return IDPServiceSettings
     */
    public function setServiceid( $service_id ){
        $this->service_id = $service_id;

        return $this;
    }

    /**
     * Get service_id
     *
     * @return integer
     */
    public function getServiceid( ){
        return $this->service_id;
    }

	/**
	 * Set view_budgetcode
	 *
	 * @param boolean $view_budgetcode
	 * @return IDPServiceSettings
	 */
	public function setViewBudgetcode($view_budgetcode)
	{
		$this->view_budgetcode = $view_budgetcode;

		return $this;
	}

	/**
	 * Get view_budgetcode
	 *
	 * @return boolean
	 */
	public function getViewBudgetcode()
	{
		return $this->view_budgetcode;
	}

	/**
	 * Set mandatory_budgetcode
	 *
	 * @param boolean $mandatory_budgetcode
	 * @return IDPServiceSettings
	 */
	public function setMandatoryBudgetcode($mandatory_budgetcode)
	{
		$this->mandatory_budgetcode = $mandatory_budgetcode;

		return $this;
	}

	/**
	 * Get mandatory_budgetcode
	 *
	 * @return boolean
	 */
	public function getMandatoryBudgetcode()
	{
		return $this->mandatory_budgetcode;
	}

	/**
	 * Set view_documentnature
	 *
	 * @param boolean $view_documentnature
	 * @return IDPServiceSettings
	 */
	public function setViewDocumentnature($view_documentnature)
	{
		$this->view_documentnature = $view_documentnature;

		return $this;
	}

	/**
	 * Get view_documentnature
	 *
	 * @return boolean
	 */
	public function getViewDocumentnature()
	{
		return $this->view_documentnature;
	}

	/**
	 * Set mandatory_documentnature
	 *
	 * @param boolean $mandatory_documentnature
	 * @return IDPServiceSettings
	 */
	public function setMandatoryDocumentnature($mandatory_documentnature)
	{
		$this->mandatory_documentnature = $mandatory_documentnature;

		return $this;
	}

	/**
	 * Get mandatory_documentnature
	 *
	 * @return boolean
	 */
	public function getMandatoryDocumentnature()
	{
		return $this->mandatory_documentnature;
	}

	/**
	 * Set view_documenttype
	 *
	 * @param boolean $view_documenttype
	 * @return IDPServiceSettings
	 */
	public function setViewDocumenttype($view_documenttype)
	{
		$this->view_documenttype = $view_documenttype;

		return $this;
	}

	/**
	 * Get view_documenttype
	 *
	 * @return boolean
	 */
	public function getViewDocumenttype()
	{
		return $this->view_documenttype;
	}

	/**
	 * Set mandatory_documenttype
	 *
	 * @param boolean $mandatory_documenttype
	 * @return IDPServiceSettings
	 */
	public function setMandatoryDocumenttype($mandatory_documenttype)
	{
		$this->mandatory_documenttype = $mandatory_documenttype;

		return $this;
	}

	/**
	 * Get mandatory_documenttype
	 *
	 * @return boolean
	 */
	public function getMandatoryDocumenttype()
	{
		return $this->mandatory_documenttype;
	}

	/**
	 * Set view_description1
	 *
	 * @param boolean $view_description1
	 * @return IDPServiceSettings
	 */
	public function setViewDescription1($view_description1)
	{
		$this->view_description1 = $view_description1;

		return $this;
	}

	/**
	 * Get view_description1
	 *
	 * @return boolean
	 */
	public function getViewDescription1()
	{
		return $this->view_description1;
	}

	/**
	 * Set mandatory_description1
	 *
	 * @param boolean $mandatory_description1
	 * @return IDPServiceSettings
	 */
	public function setMandatoryDescription1($mandatory_description1)
	{
		$this->mandatory_description1 = $mandatory_description1;

		return $this;
	}

	/**
	 * Get mandatory_description1
	 *
	 * @return boolean
	 */
	public function getMandatoryDescription1()
	{
		return $this->mandatory_description1;
	}

	/**
	 * Set name_description1
	 *
	 * @param string $name_description1
	 * @return IDPServiceSettings
	 */
	public function setNameDescription1($name_description1)
	{
		$this->name_description1 = $name_description1;

		return $this;
	}

	/**
	 * Get name_description1
	 *
	 * @return string
	 */
	public function getNameDescription1()
	{
		return $this->name_description1;
	}

	/**
	 * Set view_description2
	 *
	 * @param boolean $view_description2
	 * @return IDPServiceSettings
	 */
	public function setViewDescription2($view_description2)
	{
		$this->view_description2 = $view_description2;

		return $this;
	}

	/**
	 * Get view_description2
	 *
	 * @return boolean
	 */
	public function getViewDescription2()
	{
		return $this->view_description2;
	}

	/**
	 * Set mandatory_description2
	 *
	 * @param boolean $mandatory_description1
	 * @return IDPServiceSettings
	 */
	public function setMandatoryDescription2($mandatory_description2)
	{
		$this->mandatory_description2 = $mandatory_description2;

		return $this;
	}

	/**
	 * Get mandatory_description2
	 *
	 * @return boolean
	 */
	public function getMandatoryDescription2()
	{
		return $this->mandatory_description2;
	}

	/**
	 * Set name_description2
	 *
	 * @param string $name_description1
	 * @return IDPServiceSettings
	 */
	public function setNameDescription2($name_description2)
	{
		$this->name_description2 = $name_description2;

		return $this;
	}

	/**
	 * Get name_description2
	 *
	 * @return string
	 */
	public function getNameDescription2()
	{
		return $this->name_description2;
	}

	/**
	 * Set view_limitsnum
	 *
	 * @param boolean $view_limitsnum
	 * @return IDPServiceSettings
	 */
	public function setViewLimitsnum($view_limitsnum)
	{
		$this->view_limitsnum = $view_limitsnum;

		return $this;
	}

	/**
	 * Get view_limitsnum
	 *
	 * @return boolean
	 */
	public function getViewLimitsnum()
	{
		return $this->view_limitsnum;
	}

	/**
	 * Set mandatory_limitsnum
	 *
	 * @param boolean $mandatory_limitsnum
	 * @return IDPServiceSettings
	 */
	public function setMandatoryLimitsnum($mandatory_limitsnum)
	{
		$this->mandatory_limitsnum = $mandatory_limitsnum;

		return $this;
	}

	/**
	 * Get mandatory_limitsnum
	 *
	 * @return boolean
	 */
	public function getMandatoryLimitsnum()
	{
		return $this->mandatory_limitsnum;
	}

	/**
	 * Set view_limitsalpha
	 *
	 * @param boolean $view_limitsalpha
	 * @return IDPServiceSettings
	 */
	public function setViewLimitsalpha($view_limitsalpha)
	{
		$this->view_limitsalpha = $view_limitsalpha;

		return $this;
	}

	/**
	 * Get view_limitsalpha
	 *
	 * @return boolean
	 */
	public function getViewLimitsalpha()
	{
		return $this->view_limitsalpha;
	}

	/**
	 * Set mandatory_limitsalpha
	 *
	 * @param boolean $mandatory_limitsalpha
	 * @return IDPServiceSettings
	 */
	public function setMandatoryLimitsalpha($mandatory_limitsalpha)
	{
		$this->mandatory_limitsalpha = $mandatory_limitsalpha;

		return $this;
	}

	/**
	 * Get mandatory_limitsalpha
	 *
	 * @return boolean
	 */
	public function getMandatoryLimitsalpha()
	{
		return $this->mandatory_limitsalpha;
	}

	/**
	 * Set view_limitsalphanum
	 *
	 * @param boolean $view_limitsalphanum
	 * @return IDPServiceSettings
	 */
	public function setViewLimitsalphanum($view_limitsalphanum)
	{
		$this->view_limitsalphanum = $view_limitsalphanum;

		return $this;
	}

	/**
	 * Get view_limitsalphanum
	 *
	 * @return boolean
	 */
	public function getViewLimitsalphanum()
	{
		return $this->view_limitsnum;
	}

	/**
	 * Set mandatory_limitsalphanum
	 *
	 * @param boolean $mandatory_limitsalphanum
	 * @return IDPServiceSettings
	 */
	public function setMandatoryLimitsalphanum($mandatory_limitsalphanum)
	{
		$this->mandatory_limitsalphanum = $mandatory_limitsalphanum;

		return $this;
	}

	/**
	 * Get mandatory_limitsalphanum
	 *
	 * @return boolean
	 */
	public function getMandatoryLimitsalphanum()
	{
		return $this->mandatory_limitsalphanum;
	}

	/**
	 * Set view_limitsdate
	 *
	 * @param boolean $view_limitsdate
	 * @return IDPServiceSettings
	 */
	public function setViewLimitsdate($view_limitsdate)
	{
		$this->view_limitsdate = $view_limitsdate;

		return $this;
	}

	/**
	 * Get view_limitsdate
	 *
	 * @return boolean
	 */
	public function getViewLimitsdate()
	{
		return $this->view_limitsdate;
	}

	/**
	 * Set mandatory_limitsdate
	 *
	 * @param boolean $mandatory_limitsdate
	 * @return IDPServiceSettings
	 */
	public function setMandatoryLimitsdate($mandatory_limitsdate)
	{
		$this->mandatory_limitsdate = $mandatory_limitsdate;

		return $this;
	}

	/**
	 * Get mandatory_limitsdate
	 *
	 * @return boolean
	 */
	public function getMandatoryLimitsdate()
	{
		return $this->mandatory_limitsdate;
	}

	/**
	 * Set view_filenumber
	 *
	 * @param boolean $view_filenumber
	 * @return IDPServiceSettings
	 */
	public function setViewFilenumber($view_filenumber)
	{
		$this->view_filenumber = $view_filenumber;

		return $this;
	}

	/**
	 * Get view_filenumber
	 *
	 * @return boolean
	 */
	public function getViewFilenumber()
	{
		return $this->view_filenumber;
	}

	/**
	 * Set mandatory_filenumber
	 *
	 * @param boolean $mandatory_filenumber
	 * @return IDPServiceSettings
	 */
	public function setMandatoryFilenumber($mandatory_filenumber)
	{
		$this->mandatory_filenumber = $mandatory_filenumber;

		return $this;
	}

	/**
	 * Get mandatory_filenumber
	 *
	 * @return boolean
	 */
	public function getMandatoryFilenumber()
	{
		return $this->mandatory_filenumber;
	}

	/**
	 * Set view_boxnumber
	 *
	 * @param boolean $view_boxnumber
	 * @return IDPServiceSettings
	 */
	public function setViewBoxnumber($view_boxnumber)
	{
		$this->view_boxnumber = $view_boxnumber;

		return $this;
	}

	/**
	 * Get view_boxnumber
	 *
	 * @return boolean
	 */
	public function getViewBoxnumber()
	{
		return $this->view_boxnumber;
	}

	/**
	 * Set mandatory_boxnumber
	 *
	 * @param boolean $mandatory_boxnumber
	 * @return IDPServiceSettings
	 */
	public function setMandatoryBoxnumber($mandatory_boxnumber)
	{
		$this->mandatory_boxnumber = $mandatory_boxnumber;

		return $this;
	}

	/**
	 * Get mandatory_boxnumber
	 *
	 * @return boolean
	 */
	public function getMandatoryBoxnumber()
	{
		return $this->mandatory_boxnumber;
	}

	/**
	 * Set view_containernumber
	 *
	 * @param boolean $view_containernumber
	 * @return IDPServiceSettings
	 */
	public function setViewContainernumber($view_containernumber)
	{
		$this->view_containernumber = $view_containernumber;

		return $this;
	}

	/**
	 * Get view_containernumber
	 *
	 * @return boolean
	 */
	public function getViewContainernumber()
	{
		return $this->view_containernumber;
	}

	/**
	 * Set mandatory_containernumber
	 *
	 * @param boolean $mandatory_containernumber
	 * @return IDPServiceSettings
	 */
	public function setMandatoryContainernumber($mandatory_containernumber)
	{
		$this->mandatory_containernumber = $mandatory_containernumber;

		return $this;
	}

	/**
	 * Get mandatory_containernumber
	 *
	 * @return boolean
	 */
	public function getMandatoryContainernumber()
	{
		return $this->mandatory_containernumber;
	}

	/**
	 * Set view_provider
	 *
	 * @param boolean $view_provider
	 * @return IDPServiceSettings
	 */
	public function setViewProvider($view_provider)
	{
		$this->view_provider = $view_provider;

		return $this;
	}

	/**
	 * Get view_provider
	 *
	 * @return boolean
	 */
	public function getViewProvider()
	{
		return $this->view_provider;
	}

	/**
	 * Set mandatory_provider
	 *
	 * @param boolean $mandatory_provider
	 * @return IDPServiceSettings
	 */
	public function setMandatoryProvider($mandatory_provider)
	{
		$this->mandatory_provider = $mandatory_provider;

		return $this;
	}

	/**
	 * Get mandatory_provider
	 *
	 * @return boolean
	 */
	public function getMandatoryProvider()
	{
		return $this->mandatory_provider;
	}

    /**
     * Set view_transfer_internal_basket
     *
     * @param boolean $view_transfer_internal_basket
     * @return IDPServiceSettings
     */
    public function setViewTransferInternalBasket( $view_transfer_internal_basket ){
        $this->view_transfer_internal_basket = $view_transfer_internal_basket;
        return $this;
    }
    /**
     * Get view_transfer_internal_basket
     *
     * @return boolean
     */
    public function getViewTransferInternalBasket(  ){
        return $this->view_transfer_internal_basket;
    }

    /**
     * Set view_transfer_intermediate_basket
     *
     * @param boolean $view_transfer_intermediate_basket
     * @return IDPServiceSettings
     */
    public function setViewTransferIntermediateBasket( $view_transfer_intermediate_basket ){
        $this->view_transfer_intermediate_basket = $view_transfer_intermediate_basket;
        return $this;
    }
    /**
     * Get view_transfer_intermediate_basket
     *
     * @return boolean
     */
    public function getViewTransferIntermediateBasket(  ){
        return $this->view_transfer_intermediate_basket;
    }

    /**
     * Set view_transfer_provider_basket
     *
     * @param boolean $view_transfer_provider_basket
     * @return IDPServiceSettings
     */
    public function setViewTransferProviderBasket( $view_transfer_provider_basket ){
        $this->view_transfer_provider_basket = $view_transfer_provider_basket;
        return $this;
    }
    /**
     * Get view_transfer_provider_basket
     *
     * @return boolean
     */
    public function getViewTransferProviderBasket(  ){
        return $this->view_transfer_provider_basket;
    }

    /**
     * Set view_reloc_internal_basket
     *
     * @param boolean $view_reloc_internal_basket
     * @return IDPServiceSettings
     */
    public function setViewRelocInternalBasket( $view_reloc_internal_basket ){
        $this->view_reloc_internal_basket = $view_reloc_internal_basket;
        return $this;
    }
    /**
     * Get view_reloc_internal_basket
     *
     * @return boolean
     */
    public function getViewRelocInternalBasket(  ){
        return $this->view_reloc_internal_basket;
    }

    /**
     * Set view_reloc_intermediate_basket
     *
     * @param boolean $view_reloc_intermediate_basket
     * @return IDPServiceSettings
     */
    public function setViewRelocIntermediateBasket( $view_reloc_intermediate_basket ){
        $this->view_reloc_intermediate_basket = $view_reloc_intermediate_basket;
        return $this;
    }
    /**
     * Get view_reloc_intermediate_basket
     *
     * @return boolean
     */
    public function getViewRelocIntermediateBasket(  ){
        return $this->view_reloc_intermediate_basket;
    }

    /**
     * Set view_reloc_provider_basket
     *
     * @param boolean $view_reloc_provider_basket
     * @return IDPServiceSettings
     */
    public function setViewRelocProviderBasket( $view_reloc_provider_basket ){
        $this->view_reloc_provider_basket = $view_reloc_provider_basket;
        return $this;
    }
    /**
     * Get view_reloc_provider_basket
     *
     * @return boolean
     */
    public function getViewRelocProviderBasket(  ){
        return $this->view_reloc_provider_basket;
    }

	/**
	 * Set default_language
	 *
	 * @param integer $default_language
	 * @return IDPServiceSettings
	 */
	public function setDefaultLanguage($default_language)
	{
		$this->default_language = $default_language;

		return $this;
	}

	/**
	 * Get default_language
	 *
	 * @return integer
	 */
	public function getDefaultLanguage()
	{
		return $this->default_language;
	}

}
