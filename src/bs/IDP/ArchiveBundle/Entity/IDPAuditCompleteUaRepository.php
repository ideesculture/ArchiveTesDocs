<?php

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

use \DateTime;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;


/**
 * IDPAuditCompleteUaRepository
 *
 */
class IDPAuditCompleteUaRepository extends EntityRepository
{
    // add one entry in auditCompleteUa
    public function addOneAuditCompleteUaEntry( $service_id, $legal_entity_id, $localization, $contain, $provider_id, $budget_code_id, $document_nature_id, $document_type_id ){

        $auditCompleteUaEntry = new IDPAuditCompleteUa();
        $auditCompleteUaEntry->setServiceid( $service_id );
        $auditCompleteUaEntry->setLegalentityid( $legal_entity_id );
        $auditCompleteUaEntry->setLocalization( $localization );
        $auditCompleteUaEntry->setContain( $contain );
        $auditCompleteUaEntry->setProviderid( $provider_id );
        $auditCompleteUaEntry->setBudgetcodeid( $budget_code_id );
        $auditCompleteUaEntry->setDocumentnatureid( $document_nature_id );
        $auditCompleteUaEntry->setDocumenttypeid( $document_type_id );

        $this->getEntityManager()->persist( $auditCompleteUaEntry );
        $this->getEntityManager()->flush();

        return $auditCompleteUaEntry;
    }

    public function getNextID(){
        $queryStr = 'SELECT cua.id FROM bsIDPArchiveBundle:IDPAuditCompleteUa cua ORDER BY cua.id DESC';

        $query = $this->getEntityManager()->createQuery( $queryStr )->setMaxResults(1);
        $result = $query->getScalarResult();

        if($result)
            return intval($result[0]) + 1;
        else
            return 1;
    }
}





