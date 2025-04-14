<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPUserFiles
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\Core\UsersBundle\Entity\IDPUserFilesRepository")
 */
class IDPUserFiles
{
    const FILETYPE_XLS = 1;
    const FILETYPE_PDF = 2;

    // ----------------------------------------------------------------------------------------
    // Fields
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(name="user_id", type="integer", nullable=true )
     */
    private $userid;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var integer
     * @ORM\Column(name="filetype", type="integer", nullable=true )
     */
    private $filetype;

    /**
     * @var integer
     * @ORM\Column(name="filesize", type="integer", nullable=true )
     */
    private $filesize;

    /**
     * @var integer
     * @ORM\Column(name="filetime", type="integer", nullable=true )
     */
    private $filetime;

    /**
     * @var integer
     * @ORM\Column(name="nb_download", type="integer", nullable=true )
     */
    private $nbdownload;

    /**
     * @var boolean
     * @ORM\Column(name="in_progress", type="boolean", nullable=true )
     */
    private $inprogress;


    // ----------------------------------------------------------------------------------------
    // Setters & Getters
    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set userid
     * @param integer $userid
     * @return IDPUserFiles
     */
    public function setUserid($userid) {
        $this->userid = $userid;
        return $this;
    }

    /**
     * Get userid
     * @return integer
     */
    public function getUserid() {
        return $this->userid;
    }

    /**
     * Set name
     * @param string $name
     * @return IDPUserFiles
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set filename
     * @param string $filename
     * @return IDPUserFiles
     */
    public function setFilename($filename) {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Get filename
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * Set filetype
     * @param integer $filetype
     * @return IDPUserFiles
     */
    public function setFiletype($filetype) {
        $this->filetype = $filetype;
        return $this;
    }

    /**
     * Get filetype
     * @return integer
     */
    public function getFiletype() {
        return $this->filetype;
    }

    /**
     * Set filesize
     * @param integer $filesize
     * @return IDPUserFiles
     */
    public function setFilesize($filesize) {
        $this->filesize = $filesize;
        return $this;
    }

    /**
     * Get filesize
     * @return integer
     */
    public function getFilesize() {
        return $this->filesize;
    }

    /**
     * Set filetime
     * @param integer $filetime
     * @return IDPUserFiles
     */
    public function setFiletime($filetime) {
        $this->filetime = $filetime;
        return $this;
    }

    /**
     * Get filetime
     * @return integer
     */
    public function getFiletime() {
        return $this->filetime;
    }

    /**
     * Set nbdownload
     * @param integer $nbdownload
     * @return IDPUserFiles
     */
    public function setNbdownload($nbdownload) {
        $this->nbdownload = $nbdownload;
        return $this;
    }

    /**
     * Get nbdownload
     * @return integer
     */
    public function getNbdownload() {
        return $this->nbdownload;
    }

    /**
     * Set inprogress
     * @param boolean $inprogress
     * @return IDPUserFiles
     */
    public function setInprogress($inprogress) {
        $this->inprogress = $inprogress;
        return $this;
    }

    /**
     * Get inprogress
     * @return boolean
     */
    public function getInprogress() {
        return $this->inprogress;
    }

}
