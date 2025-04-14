<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPainSettings
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPMainSettingsRepository")
 */
class IDPMainSettings
{
    // E305 All services visibility configured at once
    const ALL_SERVICES_CONFIGURED_AT_ONCE           = 'ALL_SERVICES_CONFIGURED_AT_ONCE';
    // Default Value
    const MAIN_SETTINGS_DEFAULT_VALUES = [
        IDPMainSettings::ALL_SERVICES_CONFIGURED_AT_ONCE  => 0          // i.e. false
    ];

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
     * @ORM\Column( name="name", type="string", length=255 )
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column( name="intvalue", type="integer" )
     */
    private $int_value;


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
     *
     * @param string $name
     * @return IDPMainSettings
     */
    public function setName( $name ){
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName( ){
        return $this->name;
    }

    /**
     * Set int_value
     *
     * @param integer $int_value
     * @return IDPMainSettings
     */
    public function setIntValue($int_value)
    {
        $this->int_value = $int_value;

        return $this;
    }

    /**
     * Get int_value
     *
     * @return integer
     */
    public function getIntValue()
    {
        return $this->int_value;
    }
}
