<?php

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IDPImport.php
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\ArchiveBundle\Entity\IDPImportRepository")
 */
class IDPImport
{
    const IDP_IMPORT_STATUS_UNKNOWN = 0;
    const IDP_IMPORT_STATUS_START = 1;
    const IDP_IMPORT_STATUS_IN_PROGRESS = 2;
    const IDP_IMPORT_STATUS_END = 10;

    const IDP_IMPORT_STATUS_ERROR = 50;

    const IDP_IMPORT_STATUS_CANCEL_IN_PROGRESS = 75;
    const IDP_IMPORT_STATUS_CANCELED = 76;
    const IDP_IMPORT_STATUS_DEFINITIVE_VALIDATION = 99;

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
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="progress", type="integer", nullable=true)
     */
    private $progress;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="datebegin", type="datetime", nullable=true)
     */
    private $date_begin;

    /**
     * @var EstimatedEnd
     *
     * @ORM\Column(name="estimatedend", type="datetime", nullable=true)
     */
    private $estimated_end;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateend", type="datetime", nullable=true)
     */
    private $date_end;

    /**
     * @var integer
     *
     * @ORM\Column(name="nblinesfile", type="integer", nullable=true)
     */
    private $nb_lines_file;

    /**
     * @var integer
     *
     * @ORM\Column(name="nblinesimported", type="integer", nullable=true)
     */
    private $nb_lines_imported;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_lines_error", type="integer", nullable=true)
     */
    private $nb_lines_error;

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
     * Set filename
     *
     * @param string $filename
     * @return IDPImport
     */
    public function setFilename( $filename )
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return IDPImport
     */
    public function setStatus( $status )
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set progress
     *
     * @param integer $progress
     * @return IDPImport
     */
    public function setProgress( $progress )
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress
     *
     * @return string
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set datebegin
     *
     * @param DateTime $datebegin
     * @return IDPImport
     */
    public function setDateBegin( $date_begin )
    {
        $this->date_begin = $date_begin;

        return $this;
    }

    /**
     * Get datebegin
     *
     * @return DateTime
     */
    public function getDateBegin()
    {
        return $this->date_begin;
    }

    /**
     * Set estimated_end
     *
     * @param DateTime $estimated_end
     * @return IDPImport
     */
    public function setEstimatedEnd( $estimated_end )
    {
        $this->estimated_end = $estimated_end;

        return $this;
    }

    /**
     * Get estimated_end
     *
     * @return DateTime
     */
    public function getEstimatedEnd()
    {
        return $this->estimated_end;
    }

    /**
     * Set date_end
     *
     * @param DateTime $date_end
     * @return IDPImport
     */
    public function setDateEnd( $date_end )
    {
        $this->date_end = $date_end;

        return $this;
    }

    /**
     * Get date_end
     *
     * @return DateTime
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * Set nb_lines_file
     *
     * @param integer $nb_lines_file
     * @return IDPImport
     */
    public function setNbLinesFile ( $nb_lines_file )
    {
        $this->nb_lines_file = $nb_lines_file;

        return $this;
    }

    /**
     * Get nb_lines_file
     *
     * @return integer
     */
    public function getNbLinesFile()
    {
        return $this->nb_lines_file;
    }

    /**
     * Set nb_lines_error
     *
     * @param integer $nb_lines_error
     * @return IDPImport
     */
    public function setNbLinesError( $nb_lines_error )
    {
        $this->nb_lines_error = $nb_lines_error;

        return $this;
    }

    /**
     * Get nb_lines_error
     *
     * @return integer
     */
    public function getNbLinesError()
    {
        return $this->nb_lines_error;
    }

    /**
     * Set nb_lines_imported
     *
     * @param integer $nb_lines_imported
     * @return IDPImport
     */
    public function setNbLinesImported( $nb_lines_imported )
    {
        $this->nb_lines_imported = $nb_lines_imported;

        return $this;
    }

    /**
     * Get nb_lines_imported
     *
     * @return integer
     */
    public function getNbLinesImported()
    {
        return $this->nb_lines_imported;
    }
}
