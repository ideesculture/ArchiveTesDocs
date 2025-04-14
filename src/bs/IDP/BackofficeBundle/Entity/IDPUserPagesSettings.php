<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPUserPagesSettings
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPUserPagesSettingsRepository")
 */
class IDPUserPagesSettings
{
    const PAGE_TRANSFER                             = 1;
    const PAGE_CONSULT                              = 2;
    const PAGE_RETURN                               = 3;
    const PAGE_EXIT                                 = 4;
    const PAGE_DELETE                               = 5;
    const PAGE_RELOC                                = 6;
    const PAGE_VALID_TRANSFER_PROVIDER              = 7;
    const PAGE_VALID_TRANSFER_INTERMEDIATE          = 8;
    const PAGE_VALID_TRANSFER_INTERNAL              = 9;
    const PAGE_VALID_DELIVER_WITHOUT_PREPARATION    = 10;
    const PAGE_VALID_DELIVER_WITH_PREPARATION       = 11;
    const PAGE_VALID_RETURN                         = 12;
    const PAGE_VALID_EXIT                           = 13;
    const PAGE_VALID_DELETE                         = 14;
    const PAGE_VALID_RELOC_PROVIDER                 = 15;
    const PAGE_VALID_RELOC_INTERMEDIATE             = 16;
    const PAGE_VALID_RELOC_INTERNAL                 = 17;
    const PAGE_MANAGE_TRANSFER                      = 18;
    const PAGE_MANAGE_DELIVER                       = 19;
    const PAGE_MANAGE_RETURN                        = 20;
    const PAGE_MANAGE_EXIT                          = 21;
    const PAGE_MANAGE_DELETE                        = 22;
    const PAGE_MANAGE_RELOC                         = 23;
    const PAGE_CLOSE_TRANFER_PROVIDER               = 24;
    const PAGE_CLOSE_TRANSFER_INTERMEDIATE          = 25;
    const PAGE_CLOSE_TRANSFER_INTERNAL              = 26;
    const PAGE_CLOSE_DELIVER_WITHOUT_PREPARATION    = 27;
    const PAGE_CLOSE_DELIVER_WITH_PREPARATION       = 28;
    const PAGE_CLOSE_RETURN                         = 29;
    const PAGE_CLOSE_EXIT                           = 30;
    const PAGE_CLOSE_DELETE                         = 31;
    const PAGE_CLOSE_RELOC_PROVIDER                 = 32;
    const PAGE_CLOSE_RELOC_INTERMEDIATE             = 33;
    const PAGE_CLOSE_RELOC_INTERNAL                 = 34;
    const PAGE_CLOSE_UNLIMITED                      = 35;
    const PAGE_BDD_ENTRY_SERVICES                   = 36;
    const PAGE_BDD_ENTRY_LEGAL_ENTITIES             = 37;
    const PAGE_BDD_ENTRY_BUDGET_CODES               = 38;
    const PAGE_BDD_ENTRY_ACTIVITIES                 = 39;
    const PAGE_BDD_ENTRY_DOCUMENT_TYPES             = 40;
    const PAGE_BDD_ENTRY_DESCRIPTIONS_1             = 41;
    const PAGE_BDD_ENTRY_DESCRIPTIONS_2             = 42;
    const PAGE_BDD_ENTRY_ADRESSES                   = 43;
    const PAGE_BDD_ENTRY_LOCALIZATIONS              = 44;
    const PAGE_BDD_USERS                            = 45;
    const PAGE_BDD_PROVIDERS                        = 46;

    const USER_SETTINGS_MODIF_PAGE_NB_ROW_PER_PAGE = 1;
    const USER_SETTINGS_MODIF_PAGE_ARRAY_TYPE_LIST = 2;

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
     * @ORM\Column( name="page_id", type="integer", nullable=true )
     */
    private $page_id;

    /**
     * @var integer
     *
     * @ORM\Column( name="nb_row_per_page", type="integer", nullable=true )
     */
    private $nb_row_per_page;

    /**
     * @var boolean
     *
     * @ORM\Column( name="array_type_list", type="boolean" )
     */
    private $array_type_list;


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
     * @return IDPUserPagesSettings
     */
    public function setUserid( $user_id ){
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer
     */
    public function getUserid( ){
        return $this->user_id;
    }

    /**
     * Set page_id
     *
     * @param integer $page_id
     * @return IDPUserPagesSettings
     */
    public function setPageid( $page_id ){
        $this->page_id = $page_id;

        return $this;
    }

    /**
     * Get page_id
     *
     * @return integer
     */
    public function getPageid( ){
        return $this->page_id;
    }
    /**
     * Get nb_row_per_page
     *
     * @return integer
     */
    public function getNbrowperpage( ){
        return $this->nb_row_per_page;
    }

    /**
     * Set nb_row_per_page
     *
     * @param integer $nb_row_per_page
     * @return IDPUserPagesSettings
     */
    public function setNbrowperpage( $nb_row_per_page ){
        $this->nb_row_per_page = $nb_row_per_page;

        return $this;
    }

    /**
     * Set array_type_list
     *
     * @param boolean $array_type_list
     * @return IDPUserPagesSettings
     */
    public function setArraytypelist($array_type_list)
    {
        $this->array_type_list = $array_type_list;

        return $this;
    }

    /**
     * Get array_type_list
     *
     * @return boolean
     */
    public function getArraytypelist()
    {
        return $this->array_type_list;
    }

 }
