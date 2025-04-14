<?php
// (c) Archimage - IDP Consulting 2017
// Author: Cyril PATRIGEON - BeSecure Labs
//

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;

/**
 * IDPAuditCompleteUa
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\ArchiveBundle\Entity\IDPAuditCompleteUaRepository")
 */
class IDPAuditCompleteUa
{
    const INTERNAL      = 1;
    const INTERMEDIATE  = 2;
    const PROVIDER      = 3;

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
     * @ORM\Column( name="service_id", type="integer", nullable=true )
     */
    private $service_id;

    /**
     * @var integer
     *
     * @ORM\Column( name="legal_entity_id", type="integer", nullable=true )
     */
    private $legal_entity_id;

    /**
     * @var integer
     *
     * @ORM\Column( name="localization", type="integer", nullable=true )
     */
    private $localization; // internal, intermediate or provider

    /**
     * @var integer
     *
     * @ORM\Column( name="provider_id", type="integer", nullable=true )
     */
    private $provider_id;

    /**
     * @var integer
     *
     * @ORM\Column( name="budget_code_id", type="integer", nullable=true )
     */
    private $budget_code_id;

    /**
     * @var integer
     *
     * @ORM\Column( name="document_nature_id", type="integer", nullable=true )
     */
    private $document_nature_id;

    /**
     * @var integer
     *
     * @ORM\Column( name="document_type_id", type="integer", nullable=true )
     */
    private $document_type_id;

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
     * Get service_id
     *
     * @return integer
     */
    public function getServiceid( ){
        return $this->service_id;
    }
    /**
     * Set service_id
     *
     * @param integer $serviceid
     * @return IDPAuditCompleteUa
     */
    public function setServiceid( $serviceid ){
        $this->service_id = $serviceid;
        return $this;
    }

    /**
     * Get legal_entity_id
     *
     * @return integer
     */
    public function getLegalentityid( ){
        return $this->legal_entity_id;
    }
    /**
     * Set legal_entity_id
     *
     * @param integer $legalentityid
     * @return IDPAuditCompleteUa
     */
    public function setLegalentityid( $legalentityid ){
        $this->legal_entity_id = $legalentityid;
        return $this;
    }

    /**
     * Get localization
     *
     * @return integer
     */
    public function getLocalization( ){
        return $this->localization;
    }
    /**
     * Set localization
     *
     * @param integer $localization
     * @return IDPAuditCompleteUa
     */
    public function setLocalization( $localization ){
        $this->localization = $localization;
        return $this;
    }

    /**
     * Get provider_id
     *
     * @return integer
     */
    public function getProviderid( ){
        return $this->provider_id;
    }
    /**
     * Set provider_id
     *
     * @param integer $providerid
     * @return IDPAuditCompleteUa
     */
    public function setProviderid( $providerid ){
        $this->provider_id = $providerid;
        return $this;
    }

    /**
     * Get budget_code_id
     *
     * @return integer
     */
    public function getBudgetcodeid( ){
        return $this->budget_code_id;
    }
    /**
     * Set budget_code_id
     *
     * @param integer $budgetcodeid
     * @return IDPAuditCompleteUa
     */
    public function setBudgetcodeid( $budgetcodeid ){
        $this->budget_code_id = $budgetcodeid;
        return $this;
    }

    /**
     * Get document_nature_id
     *
     * @return integer
     */
    public function getDocumentnatureid( ){
        return $this->document_nature_id;
    }
    /**
     * Set document_nature_id
     *
     * @param integer $documentnatureid
     * @return IDPAuditCompleteUa
     */
    public function setDocumentnatureid( $documentnatureid ){
        $this->document_nature_id = $documentnatureid;
        return $this;
    }

    /**
     * Get document_type_id
     *
     * @return integer
     */
    public function getDocumenttypeid( ){
        return $this->document_type_id;
    }
    /**
     * Set document_type_id
     *
     * @param integer $documenttypeid
     * @return IDPAuditCompleteUa
     */
    public function setDocumenttypeid( $documenttypeid ){
        $this->document_type_id = $documenttypeid;
        return $this;
    }
}
