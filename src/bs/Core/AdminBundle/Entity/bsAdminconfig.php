<?php

namespace bs\Core\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * bsAdminconfig
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\Core\AdminBundle\Entity\bsAdminconfigRepository")
 */
class bsAdminconfig
{
    const CURRENT_SOFTWARE_MAJOR_VERSION    = 1; //
    const CURRENT_SOFTWARE_MINOR_VERSION    = 0; //
    const CURRENT_SOFTWARE_RELEASE_VERSION  = 0; //

    // CURRENT_SOFTWARE_MAJOR_VERSION * 100000 + CURRENT_SOFTWARE_MINOR_VERSION * 1000 + CURRENT_SOFTWARE_RELEASE_VERSION
    const CURRENT_SOFTWARE_VERSION = 100000;

    const CURRENT_DATABASE_MAJOR_VERSION    = 1;
    const CURRENT_DATABASE_MINOR_VERSION    = 0;
    const CURRENT_DATABASE_RELEASE_VERSION  = 0;

    // CURRENT_DATABSE_MAJOR_VERSION * 100000 + CURRENT_DATABSE_MINOR_VERSION * 1000 + CURRENT_DATABSE_RELEASE_VERSION
    const CURRENT_DATABASE_VERSION = 100000;

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
     * @ORM\Column(name="softwaremajorversion", type="integer")
     */
    private $software_major_version;
    /**
     * @var integer
     *
     * @ORM\Column(name="softwareminorversion", type="integer")
     */
    private $software_minor_version;
    /**
     * @var integer
     *
     * @ORM\Column(name="softwarereleaseversion", type="integer")
     */
    private $software_release_version;

    /**
     * @var integer
     *
     * @ORM\Column(name="databasemajorversion", type="integer")
     */
    private $database_major_version;
    /**
     * @var integer
     *
     * @ORM\Column(name="databaseminorversion", type="integer")
     */
    private $database_minor_version;
    /**
     * @var integer
     *
     * @ORM\Column(name="databasereleaseversion", type="integer")
     */
    private $database_release_version;

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
     * Set software_major_version
     *
     * @param integer $software_major_version
     * @return bsAdminconfig
     */
    public function setSoftwaremajorversion($software_major_version)
    {
        $this->software_major_version = $software_major_version;

        return $this;
    }
    /**
     * Get software_major_version
     *
     * @return integer
     */
    public function getSoftwaremajorversion()
    {
        return $this->software_major_version;
    }
    /**
     * Set software_minor_version
     *
     * @param integer $software_minor_version
     * @return bsAdminconfig
     */
    public function setSoftwareminorversion($software_minor_version)
    {
        $this->software_minor_version = $software_minor_version;

        return $this;
    }
    /**
     * Get software_minor_version
     *
     * @return integer
     */
    public function getSoftwareminorversion()
    {
        return $this->software_minor_version;
    }
    /**
    * Set software_release_version
    *
    * @param integer $software_release_version
    * @return bsAdminconfig
    */
    public function setSoftwarereleaseversion($software_release_version)
    {
        $this->software_release_version = $software_release_version;

        return $this;
    }
    /**
     * Get software_release_version
     *
     * @return integer
     */
    public function getSoftwarereleaseversion()
    {
        return $this->software_release_version;
    }

    /**
     * Get software_version
     *  major_version * 100000 + minor_version * 1000 + release_version
     *  for purpose comparison only
     * @return integer
     */
    public function getSoftwareversion()
    {
        return $this->software_major_version * 100000 + $this->software_minor_version * 1000 + $this->software_release_version;
    }



    /**
     * Set database_major_version
     *
     * @param integer $database_major_version
     * @return bsAdminconfig
     */
    public function setDatabasemajorversion($database_major_version)
    {
        $this->database_major_version = $database_major_version;

        return $this;
    }
    /**
     * Get database_major_version
     *
     * @return integer
     */
    public function getDatabasemajorversion()
    {
        return $this->database_major_version;
    }
    /**
     * Set database_minor_version
     *
     * @param integer $database_minor_version
     * @return bsAdminconfig
     */
    public function setDatabaseminorversion($database_minor_version)
    {
        $this->database_minor_version = $database_minor_version;

        return $this;
    }
    /**
     * Get database_minor_version
     *
     * @return integer
     */
    public function getDatabaseminorversion()
    {
        return $this->database_minor_version;
    }
    /**
     * Set database_release_version
     *
     * @param integer $database_release_version
     * @return bsAdminconfig
     */
    public function setDatabasereleaseversion($database_release_version)
    {
        $this->database_release_version = $database_release_version;

        return $this;
    }
    /**
     * Get database_release_version
     *
     * @return integer
     */
    public function getDatabasereleaseversion()
    {
        return $this->database_release_version;
    }

    /**
     * Get database_version
     *  major_version * 100000 + minor_version * 1000 + release_version
     *  for purpose comparison only
     * @return integer
     */
    public function getDatabaseversion()
    {
        return $this->database_major_version * 100000 + $this->database_minor_version * 1000 + $this->database_release_version;
    }

}
