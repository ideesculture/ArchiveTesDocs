<?php

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IDPReconciliation.php
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\ArchiveBundle\Entity\IDPReconciliationRepository")
 */
class IDPReconciliation
{
    const NO_RECONCILIATION_IN_PROGRESS         = 0;

    const UPLOAD_IN_PROGRESS                    = 51;
    const VERIFICATION_IN_PROGRESS              = 52;
    const DATABASE_COPY_IN_PROGRESS             = 53;

    const RESET_IN_PROGRESS                     = 60;

    const TREATMENT_IN_PROGRESS                 = 1;
    const RESULT_FILE_GENERATION_IN_PROGRESS    = 2;

    const RECONCILIATION_READY                  = 9;

    const ERROR_FILE_NOT_FOUND                  = 10;
    const ERROR_PROCESS_ASYNC                   = 11;
    const ERROR_FILE_EMPTY                      = 12;
    const ERROR_LOCALIZATION_MISSING            = 13;
    const ERROR_LOCALIZATION_WRONG              = 14;
    const ERROR_FILE_IS_NOT_TEXT                = 15;
    const ERROR_DATABASE_COPY_ALREADY_EXISTS    = 16;
    const ERROR_DATABASE_COPY                   = 17;
    const ERROR_STEP1                           = 18;
    const ERROR_STEP1_FILE_OPEN                 = 19;
    const ERROR_STEP1_GET_PROVIDERS             = 20;
    const ERROR_STEP1_GET_STATUSES              = 21;
    const ERROR_STEP2                           = 22;
    const ERROR_STEP2_GET_PROVIDERS             = 23;
    const ERROR_STEP2_GET_STATUSES              = 24;
    const ERROR_STEP2_UA_ITERATOR               = 25;
    const ERROR_FILE_GENERATION                 = 26;
    const ERROR_FILE_GEN_DB_ERROR               = 27;
    const ERROR_FILE_GEN_CREATE_FILE            = 28;
    const ERROR_FILE_GEN_WRITE_FILE             = 29;

    public static $ERROR_MESSAGES = [
        self::ERROR_FILE_NOT_FOUND => "Une erreur de transfert du fichier d'analyse s'est produite ! [E02010]",
        self::ERROR_PROCESS_ASYNC => "Une erreur lors de l'exécution du processus de rapprochement s'est produite ! [E02011]",
        self::ERROR_FILE_EMPTY => "Le fichier transmis est vide, aucun rapprochement n'est possible ! [E02012]",
        self::ERROR_LOCALIZATION_MISSING => "Aucune localisation n'a été transmise, le rapprochement n'est pas possible ! [E02013]",
        self::ERROR_LOCALIZATION_WRONG => "La localisation transmise ne correspond a aucune connue d'Archimage ! [E02014]",
        self::ERROR_FILE_IS_NOT_TEXT => "Le fichier transmis n'est pas un fichier texte, aucun rapprochement n'est possible ! [E02015]",
        self::ERROR_DATABASE_COPY_ALREADY_EXISTS => "Une erreur de Base de données a été rencontrée ! [E02016]",
        self::ERROR_DATABASE_COPY => "Une erreur de Base de données a été rencontrée ! [E02017]",
        self::ERROR_STEP1 => "Une erreur est survenue pendant la première phase d'analyse ! [E02018]",
        self::ERROR_STEP1_FILE_OPEN => "Une erreur est survenue pendant la tentative d'ouverture du fichier de rapprochement ! [E02019]",
        self::ERROR_STEP1_GET_PROVIDERS => "Une erreur est survenue pendant la récupération en base des comptes prestataires ! [E02020]",
        self::ERROR_STEP1_GET_STATUSES => "Une erreur est survenue pendant la récupération en base des états ! [E02021]",
        self::ERROR_STEP2 => "Une erreur est survenue pendant la deuxième phase d'analyse ! [E02022]",
        self::ERROR_STEP2_GET_PROVIDERS => "Une erreur est survenue pendant la récupération en base des comptes prestataires ! [E02023]",
        self::ERROR_STEP2_GET_STATUSES => "Une erreur est survenue pendant la récupération en base des états ! [E02024]",
        self::ERROR_STEP2_UA_ITERATOR => "Une erreur est survenue lors de la recherche des unités d'archives en base en vue d'analyse ! [E02025]",
        self::ERROR_FILE_GENERATION => "Une erreur est survenue pendant la phase de création des rapports ! [E02026]",
        self::ERROR_FILE_GEN_DB_ERROR => "Une erreur de base de données est survenue pendant la création des rapports ! [E02027]",
        self::ERROR_FILE_GEN_CREATE_FILE => "Une erreur de création de fichier est survenue pendant la création des rapports ! [E02028]",
        self::ERROR_FILE_GEN_WRITE_FILE => "Une erreur d'écriture dans un fichier est survenue pendant la création des rapports ! [E02029]"
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
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename = null;

    /**
     * @var string
     *
     * @ORM\Column(name="realfilename", type="string", length=255, nullable=true)
     */
    private $real_filename = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="percentstep1", type="integer")
     */
    private $percent_step1 = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="percentstep2", type="integer")
     */
    private $percent_step2 = 0;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="datebeginstep1", type="datetime", nullable=true)
     */
    private $date_begin_step1 = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="datebeginstep2", type="datetime", nullable=true)
     */
    private $date_begin_step2 = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="estimatedendstep1", type="datetime", nullable=true)
     */
    private $estimated_end_step1 = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateendstep1", type="datetime", nullable=true)
     */
    private $date_end_step1 = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="estimatedendstep2", type="datetime", nullable=true)
     */
    private $estimated_end_step2 = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateendstep2", type="datetime", nullable=true)
     */
    private $date_end_step2 = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="nblinesinfile", type="integer", nullable=true)
     */
    private $nb_lines_in_file = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="nblinestreated", type="integer", nullable=true)
     */
    private $nb_lines_treated = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbentriesinbdd", type="integer", nullable=true)
     */
    private $nb_entries_in_bdd = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbentriestreated", type="integer", nullable=true)
     */
    private $nb_entries_treated = null;

    /**
     * @var string
     *
     * @ORM\Column(name="resultfilename", type="string", length=255, nullable=true)
     */
    private $result_filename = null;

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
     * @return IDPReconciliation
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
     * Get realfilename
     *
     * @return string
     */
    public function getRealFilename()
    {
        return $this->real_filename;
    }

    /**
     * Set realfilename
     *
     * @param string $real_filename
     * @return IDPReconciliation
     */
    public function setRealFilename( $real_filename )
    {
        $this->real_filename = $real_filename;

        return $this;
    }

    /**
     * Get percent_step1
     *
     * @return integer
     */
    public function getPercentStep1()
    {
        return $this->percent_step1;
    }

    /**
     * Set percent_step1
     *
     * @param integer $percent_step1
     * @return IDPReconciliation
     */
    public function setPercentStep1( $percent_step1 )
    {
        $this->percent_step1 = $percent_step1;

        return $this;
    }

    /**
     * Get percent_step2
     *
     * @return integer
     */
    public function getPercentStep2()
    {
        return $this->percent_step2;
    }

    /**
     * Set percent_step2
     *
     * @param integer $percent_step2
     * @return IDPReconciliation
     */
    public function setPercentStep2( $percent_step2 )
    {
        $this->percent_step2 = $percent_step2;

        return $this;
    }

    /**
     * Get date_begin_step1
     *
     * @return DateTime
     */
    public function getDateBeginStep1()
    {
        return $this->date_begin_step1;
    }

    /**
     * Set date_begin_step1
     *
     * @param DateTime $date_begin_step1
     * @return IDPReconciliation
     */
    public function setDateBeginStep1( $date_begin_step1 )
    {
        $this->date_begin_step1 = $date_begin_step1;

        return $this;
    }

    /**
     * Get date_end_step1
     *
     * @return DateTime
     */
    public function getDateEndStep1()
    {
        return $this->date_end_step1;
    }

    /**
     * Set date_end_step1
     *
     * @param DateTime $date_end_step1
     * @return IDPReconciliation
     */
    public function setDateEndStep1( $date_end_step1 )
    {
        $this->date_end_step1 = $date_end_step1;

        return $this;
    }

    /**
     * Get estimated_end_step1
     *
     * @return DateTime
     */
    public function getEstimatedEndStep1()
    {
        return $this->estimated_end_step1;
    }

    /**
     * Set estimated_end_step1
     *
     * @param DateTime $estimated_end_step1
     * @return IDPReconciliation
     */
    public function setEstimatedEndStep1( $estimated_end_step1 )
    {
        $this->estimated_end_step1 = $estimated_end_step1;

        return $this;
    }

    /**
     * Get date_begin_step2
     *
     * @return DateTime
     */
    public function getDateBeginStep2()
    {
        return $this->date_begin_step2;
    }

    /**
     * Set date_begin_step2
     *
     * @param DateTime $date_begin_step2
     * @return IDPReconciliation
     */
    public function setDateBeginStep2( $date_begin_step2 )
    {
        $this->date_begin_step2 = $date_begin_step2;

        return $this;
    }

    /**
     * Get date_end_step2
     *
     * @return DateTime
     */
    public function getDateEndStep2()
    {
        return $this->date_end_step2;
    }

    /**
     * Set date_end_step2
     *
     * @param DateTime $date_end_step2
     * @return IDPReconciliation
     */
    public function setDateEndStep2( $date_end_step2 )
    {
        $this->date_end_step2 = $date_end_step2;

        return $this;
    }

    /**
     * Get estimated_end_step2
     *
     * @return DateTime
     */
    public function getEstimatedEndStep2()
    {
        return $this->estimated_end_step2;
    }

    /**
     * Set estimated_end_step2
     *
     * @param DateTime $estimated_end_step2
     * @return IDPReconciliation
     */
    public function setEstimatedEndStep2( $estimated_end_step2 )
    {
        $this->estimated_end_step2 = $estimated_end_step2;

        return $this;
    }

    /**
     * Get nb_lines_in_file
     *
     * @return Integer
     */
    public function getNbLinesInFile()
    {
        return $this->nb_lines_in_file;
    }

    /**
     * Set nb_lines_in_file
     *
     * @param Integer $nb_lines_in_file
     * @return IDPReconciliation
     */
    public function setNbLinesInFiles( $nb_lines_in_file )
    {
        $this->nb_lines_in_file = $nb_lines_in_file;

        return $this;
    }

    /**
     * Get nb_lines_treated
     *
     * @return Integer
     */
    public function getNbLinesTreated()
    {
        return $this->nb_lines_treated;
    }

    /**
     * Set nb_lines_treated
     *
     * @param Integer $nb_lines_treated
     * @return IDPReconciliation
     */
    public function setNbLinesTreated( $nb_lines_treated )
    {
        $this->nb_lines_treated = $nb_lines_treated;

        return $this;
    }

    /**
     * Get nb_entries_in_bdd
     *
     * @return Integer
     */
    public function getNbEntriesInBdd()
    {
        return $this->nb_entries_in_bdd;
    }

    /**
     * Set nb_entries_in_bdd
     *
     * @param Integer $nb_entries_in_bdd
     * @return IDPReconciliation
     */
    public function setNbEntriesInBdd( $nb_entries_in_bdd )
    {
        $this->nb_entries_in_bdd = $nb_entries_in_bdd;

        return $this;
    }

    /**
     * Get nb_entries_treated
     *
     * @return Integer
     */
    public function getNbEntriesTreated()
    {
        return $this->nb_entries_treated;
    }

    /**
     * Set nb_entries_treated
     *
     * @param Integer $nb_entries_treated
     * @return IDPReconciliation
     */
    public function setNbEntriesTreated( $nb_entries_treated )
    {
        $this->nb_entries_treated = $nb_entries_treated;

        return $this;
    }
    /**
     * Get resultfilename
     *
     * @return string
     */
    public function getResultFilename()
    {
        return $this->result_filename;
    }

    /**
     * Set resultfilename
     *
     * @param string $result_filename
     * @return IDPReconciliation
     */
    public function setResultFilename( $result_filename )
    {
        $this->result_filename = $result_filename;

        return $this;
    }

}

