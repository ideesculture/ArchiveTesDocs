<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPGlobalStatuses.php
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\BackofficeBundle\Entity\IDPGlobalStatusesRepository")
 */
class IDPGlobalStatuses
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
     * @var boolean
     *
     * @ORM\Column( name="import_in_progress", type="boolean" )
     */
    private $import_in_progress; // import in progress

    /**
     * @var boolean
     *
     * @ORM\Column( name="cancel_in_progress", type="boolean" )
     */
    private $cancel_in_progress; // import cancellation

    /**
     * @var integer
     *
     * @ORM\Column( name="current_import_id", type="integer", nullable=true )
     */
    private $current_import_id;

    /**
     * @var integer
     *
     * @ORM\Column( name="reconciliation_in_progress", type="integer", nullable=true )
     */
    private $reconciliation_in_progress;

    /**
     * @var integer
     *
     * @ORM\Column( name="current_reconciliation_id", type="integer", nullable=true )
     */
    private $current_reconciliation_id;

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
     * Set import_in_progress
     *
     * @param boolean $import_in_progress
     * @return IDPGlobalStatuses
     */
    public function setImportInProgress( $import_in_progress ){
        $this->import_in_progress = $import_in_progress;

        return $this;
    }

    /**
     * Get import_in_progress
     *
     * @return boolean
     */
    public function getImportInProgress( ){
        return $this->import_in_progress;
    }

    /**
     * Set cancel_in_progress
     *
     * @param boolean $cancel_in_progress
     * @return IDPGlobalStatuses
     */
    public function setCancelInProgress( $cancel_in_progress ){
        $this->cancel_in_progress = $cancel_in_progress;

        return $this;
    }

    /**
     * Get cancel_in_progress
     *
     * @return boolean
     */
    public function getCancelInProgress( ){
        return $this->cancel_in_progress;
    }

    /**
     * Set current_import_id
     *
     * @param integer $current_import_id
     * @return IDPGlobalStatuses
     */

    public function setCurrentImportId($current_import_id)
    {
        $this->current_import_id = $current_import_id;

        return $this;
    }

    /**
     * Get current_import_id
     *
     * @return integer
     */
    public function getCurrentImportId()
    {
        return $this->current_import_id;
    }

    /**
     * Set reconciliation_in_progress
     *
     * @param integer $reconciliation_in_progress
     * @return IDPGlobalStatuses
     */

    public function setReconciliationInProgress($reconciliation_in_progress)
    {
        $this->reconciliation_in_progress = $reconciliation_in_progress;

        return $this;
    }

    /**
     * Get reconciliation_in_progress
     *
     * @return integer
     */
    public function getReconciliationInProgress()
    {
        return $this->reconciliation_in_progress;
    }

    /**
     * Set current_reconciliation_id
     *
     * @param integer $current_reconciliation_id
     * @return IDPGlobalStatuses
     */

    public function setCurrentReconciliationId($current_reconciliation_id)
    {
        $this->current_reconciliation_id = $current_reconciliation_id;

        return $this;
    }

    /**
     * Get current_reconciliation_id
     *
     * @return integer
     */
    public function getCurrentReconciliationId()
    {
        return $this->current_reconciliation_id;
    }
}
