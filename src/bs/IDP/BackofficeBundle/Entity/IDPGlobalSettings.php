<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPGlobalSettings
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPGlobalSettingsRepository")
 */
class IDPGlobalSettings
{
    // Password Settings Constants
    const PASSWORD_MIN_LENGTH                       = 'PASSWORD_MIN_LENGTH';
    const PASSWORD_COMPLEXITY                       = 'PASSWORD_COMPLEXITY';
    // Password Complexity values
    const PASSWORD_COMPLEXITY_CHARS_LOWER           = 1;
    const PASSWORD_COMPLEXITY_CHARS_UPPER           = 2;
    const PASSWORD_COMPLEXITY_CHARS_SPECIAL         = 4;
    const PASSWORD_COMPLEXITY_NUMBERS               = 8;
    // Default Value
    const DEFAULT_PASSWORD_MIN_LENGTH               = 8;
    const DEFAULT_PASSWORD_COMPLEXITY               = 15;

    // Export Type Constants
    const EXPORT_TYPE                               = 'EXPORT_TYPE';
    // Export Type Values
    const EXPORT_TYPE_IDP                           = 1;
    const EXPORT_TYPE_XLS                           = 2;
    // Default Value
    const DEFAULT_EXPORT_TYPE                       = 2;

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
     * @return IDPGlobalSettings
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
     * @return IDPGlobalSettings
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
