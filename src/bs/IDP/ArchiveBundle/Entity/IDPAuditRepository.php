<?php

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

use \DateTime;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;


/**
 * IDPAuditRepository
 *
 */
class IDPAuditRepository extends EntityRepository
{
    // add one entry in audit
    // $action: type of action, see IDPConstants AUDIT_ACTION_*
    // $user_id: id of user who made this action
    // $field: on which field this action has been made, see IDPConstant FIELD_*
    // $entity: on which table this action has been made, see IDPConstant ENTITY_*
    // $entity_id: on which entry in this table the action has been made
    // $new _str / _int : what is the new value of the field (either in string, int or both if necessary)
    // $old _str / _int : what was the precedent value of the field
    // $complete_ua_id : null or id of complete description
    // $object_type : null or type of Object during status modification
    public function addOneAuditEntry( $action, $user_id, $field, $entity, $entity_id, $new_str, $new_int, $old_str, $old_int, $complete_ua_id, $object_type = null, $flushToDB = true ){
        $now = new DateTime();
        $when = $now->getTimestamp();

        $auditEntry = new IDPAudit();
        $auditEntry->setAction( $action );
        $auditEntry->setUserid( $user_id );
        $auditEntry->setTimestamp( $when );
        $auditEntry->setField( $field );
        $auditEntry->setEntity( $entity );
        $auditEntry->setEntityid( $entity_id );
        $auditEntry->setNewstr( $new_str );
        $auditEntry->setNewint( $new_int );
        $auditEntry->setOldstr( $old_str );
        $auditEntry->setOldint( $old_int );
        $auditEntry->setCompleteuaid( $complete_ua_id );
        $auditEntry->setObjectType( $object_type );

        $this->getEntityManager()->persist( $auditEntry );
        if( $flushToDB )
            $this->getEntityManager()->flush();

        return $auditEntry;
    }

    // add multiple entries in audit
    // $arrayOfEntries: array of array with 'action', 'user_id', 'field', 'entity', 'entity_id', 'new_str', 'new_int', 'old_str', 'old_int', 'complete_ua_id', 'object_type values
    public function addMultipleAuditEntry( $arrayOfEntries ){
        $now = new DateTime();
        $when = $now->getTimestamp();

        if( $arrayOfEntries == null )
            return -1;
        if( sizeof( $arrayOfEntries ) <= 0 )
            return -1;

        $nbEntries = 0;

        foreach( $arrayOfEntries as $entry ){
            $auditEntry = new IDPAudit();
            $auditEntry->setAction( array_key_exists('action', $entry )?$entry['action']:null );
            $auditEntry->setUserid( array_key_exists( 'user_id', $entry )?$entry['user_id']:null );
            $auditEntry->setTimestamp( $when );
            $auditEntry->setField( array_key_exists( 'field', $entry )?$entry['field']:null );
            $auditEntry->setEntity( array_key_exists( 'entity', $entry )?$entry['entity']:null );
            $auditEntry->setEntityid( array_key_exists( 'entity_id', $entry )?$entry['entity_id']:null );
            $auditEntry->setNewstr( array_key_exists( 'new_str', $entry )?$entry['new_str']:null );
            $auditEntry->setNewint( array_key_exists( 'new_int', $entry )?$entry['new_int']:null );
            $auditEntry->setOldstr( array_key_exists( 'old_str', $entry )?$entry['old_str']:null );
            $auditEntry->setOldint( array_key_exists( 'old_int', $entry )?$entry['old_int']:null );
            $auditEntry->setCompleteuaid( array_key_exists( 'complete_ua_id', $entry )?$entry['complete_ua_id']:null );
            $auditEntry->setObjectType( array_key_exists('object_type', $entry)?$entry['object_type']:null );

            $this->getEntityManager()->persist( $auditEntry );
            $nbEntries++;
        }
        $this->getEntityManager()->flush();

        return $nbEntries;
    }
}





