<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPColumns
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPColumnsRepository")
 */
class IDPColumns
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
     * @ORM\Column(name="field_name", type="string", length=255)
     **/
    private $field_name;

    /**
     * @var integer
     *
     * @ORM\Column( name="translation_id", type="integer" )
     */
    private $translation_id;

    /**
     * @var integer
     *
     * @ORM\Column( name="order_idx", type="integer" )
     */
    private $order_idx;

    /**
     * @var boolean
     *
     * @ORM\Column( name="view_by_config", type="boolean" )
     */
    private $view_by_config;

    /**
     * @var integer
     *
     * @ORM\Column( name="config_idx", type="integer" )
     */
    private $config_idx;

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
     * Set field_name
     *
     * @param string $field_name
     * @return IDPColumns
     */
    public function setFieldname($field_name)
    {
        $this->field_name = $field_name;

        return $this;
    }

    /**
     * Get field_name
     *
     * @return string
     */
    public function getFieldname()
    {
        return $this->field_name;
    }

    /**
     * Set translation_id
     *
     * @param integer $translation_id
     * @return IDPColumns
     */
    public function setTranslationid($translation_id)
    {
        $this->translation_id = $translation_id;

        return $this;
    }

    /**
     * Get translation_id
     *
     * @return integer
     */
    public function getTranslationid()
    {
        return $this->translation_id;
    }

    /**
     * Set order_idx
     *
     * @param integer $order_idx
     * @return IDPColumns
     */
    public function setOrderidx($order_idx)
    {
        $this->order_idx = $order_idx;

        return $this;
    }

    /**
     * Get order_idx
     *
     * @return integer
     */
    public function getOrderidx()
    {
        return $this->order_idx;
    }

    /**
     * Set view_by_config
     *
     * @param boolean $view_by_config
     * @return IDPColumns
     */
    public function setViewbyconfig($view_by_config)
    {
        $this->view_by_config = $view_by_config;

        return $this;
    }

    /**
     * Get view_by_config
     *
     * @return boolean
     */
    public function getViewbyconfig()
    {
        return $this->view_by_config;
    }

    /**
     * Set config_idx
     *
     * @param integer $config_idx
     * @return IDPColumns
     */
    public function setConfigidx($config_idx)
    {
        $this->config_idx = $config_idx;

        return $this;
    }

    /**
     * Get config_idx
     *
     * @return integer
     */
    public function getConfigidx()
    {
        return $this->config_idx;
    }
}
