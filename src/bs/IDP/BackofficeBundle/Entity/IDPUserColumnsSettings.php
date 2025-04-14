<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPUserColumnsSettings
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPUserColumnsSettingsRepository")
 */
class IDPUserColumnsSettings
{
    const FIELD_SERVICE                 = 1;
    const FIELD_ORDER_NUMBER            = 2;
    const FIELD_LEGAL_ENTITY            = 3;
    const FIELD_NAME                    = 4;
    const FIELD_BUDGET_CODE             = 5;
    const FIELD_DOCUMENT_NATURE         = 6;
    const FIELD_DOCUMENT_TYPE           = 7;
    const FIELD_DESCRIPTION_1           = 8;
    const FIELD_DESCRIPTION_2           = 9;
    const FIELD_DOCUMENT_NUMBER         = 10;
    const FIELD_BOX_NUMBER              = 11;
    const FIELD_CONTAINER_NUMBER        = 12;
    const FIELD_PROVIDER                = 13;
    const FIELD_STATUS                  = 14;
    const FIELD_ID                      = 15;
    const FIELD_ADMINLIST               = 16;
    const FIELD_STATUS_CAPS             = 17;
    const FIELD_AUTHORIZED              = 18;
    const FIELD_LOCALIZATION            = 19;
    const FIELD_LOCALIZATION_FREE       = 20;
    const FIELD_LIMIT_DATE_MIN          = 21;
    const FIELD_LIMIT_DATE_MAX          = 22;
    const FIELD_LIMIT_NUM_MIN           = 23;
    const FIELD_LIMIT_NUM_MAX           = 24;
    const FIELD_LIMIT_ALPHA_MIN         = 25;
    const FIELD_LIMIT_ALPHA_MAX         = 26;
    const FIELD_LIMIT_ALPHANUM_MIN      = 27;
    const FIELD_LIMIT_ALPHANUM_MAX      = 28;
    const FIELD_CLOSURE_YEAR            = 29;
    const FIELD_DESTRUCTION_YEAR        = 30;
    const FIELD_STATUS_CODE             = 31;
    const FIELD_MODIFIED_AT             = 32;
    const FIELD_OLD_LOCALIZATION        = 33;
    const FIELD_OLD_LOCALIZATION_FREE   = 34;
    const FIELD_PROVIDER_ID             = 35;
    const FIELD_PRECISION_DATE          = 36;
    const FIELD_PRECISION_ADDRESS       = 37;
    const FIELD_PRECISION_FLOOR         = 38;
    const FIELD_PRECISION_OFFICE        = 39;
    const FIELD_PRECISION_WHO           = 40;
    const FIELD_PRECISION_COMMENT       = 41;

    const USER_SETTINGS_MODIF_COLUMN_VISIBLE         = 1;
    const USER_SETTINGS_MODIF_COLUMN_SORTED          = 2;
    const USER_SETTINGS_MODIF_COLUMN_SORT_TYPE_ASC   = 3;


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
     * @ORM\ManyToOne(targetEntity="bs\IDP\BackofficeBundle\Entity\IDPColumns")
     * @ORM\JoinColumn(name="column_id", referencedColumnName="id", nullable=true)
     **/
    private $column;

    /**
     * @var boolean
     *
     * @ORM\Column( name="visible", type="boolean" )
     */
    private $visible;

    /**
     * @var boolean
     *
     * @ORM\Column( name="sorted", type="boolean" )
     */
    private $sorted;

    /**
     * @var boolean
     *
     * @ORM\Column( name="sort_type_asc", type="boolean" )
     */
    private $sort_type_asc;

    /**
     * @var boolean
     *
     * @ORM\Column( name="switchable", type="boolean" )
     */
    private $switchable;

    /**
     * @var integer
     *
     * @ORM\Column( name="column_order", type="integer" )
     */
    private $column_order;

    /**
     * @ORM\ManyToOne( targetEntity="bs\IDP\BackofficeBundle\Entity\IDPUserPagesSettings")
     * @ORM\JoinColumn( name="user_page_settings_id", referencedColumnName="id", nullable=true )
     **/
    private $user_page_settings;


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
     * Set column
     *
     * @param \bs\IDP\ArchiveBundle\Entity\IDPColumns $column
     * @return IDPUserColumnsSettings
     */
    public function setColumn(\bs\IDP\BackofficeBundle\Entity\IDPColumns $column = null)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Get column
     *
     * @return \bs\IDP\ArchiveBundle\Entity\IDPColumns
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return IDPUserColumnsSettings
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set sorted
     *
     * @param boolean $sorted
     * @return IDPUserColumnsSettings
     */
    public function setSorted($sorted)
    {
        $this->sorted = $sorted;

        return $this;
    }

    /**
     * Get sorted
     *
     * @return boolean
     */
    public function getSorted()
    {
        return $this->sorted;
    }

    /**
     * Set sort_type_asc
     *
     * @param boolean $sort_type_asc
     * @return IDPUserColumnsSettings
     */
    public function setSorttypeasc($sort_type_asc)
    {
        $this->sort_type_asc = $sort_type_asc;

        return $this;
    }

    /**
     * Get sort_type_asc
     *
     * @return boolean
     */
    public function getSorttypeasc()
    {
        return $this->sort_type_asc;
    }

    /**
     * Set switchable
     *
     * @param boolean $switchable
     * @return IDPUserColumnsSettings
     */
    public function setSwitchable($switchable)
    {
        $this->switchable = $switchable;

        return $this;
    }

    /**
     * Get switchable
     *
     * @return boolean
     */
    public function getSwitchable()
    {
        return $this->switchable;
    }

    /**
     * Set user_page_settings
     *
     * @param \bs\IDP\BackofficeBundle\Entity\IDPUserPagesSettings $user_page_settings
     * @return IDPUserColumnsSettings
     */
    public function setUserpagesettings(\bs\IDP\BackofficeBundle\Entity\IDPUserPagesSettings $user_page_settings)
    {
        $this->user_page_settings = $user_page_settings;

        return $this;
    }

    /**
     * Get user_page_settings
     *
     * @return \bs\IDP\BackofficeBundle\Entity\IDPUserPagesSettings
     */
    public function getUserpagesettings()
    {
        return $this->user_page_settings;
    }

    /**
     * Set column_order
     *
     * @param integer $column_order;
     * @return IDPUserColumnsSettings
     */
    public function setColumnOrder( $column_order ){
        $this->column_order = $column_order;

        return $this;
    }

    /**
     * Get column_order
     *
     * @return integer
     */
    public function getColumnOrder( ){
        return $this->column_order;
    }

}
