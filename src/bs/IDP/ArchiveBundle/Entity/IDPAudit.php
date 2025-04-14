<?php
// (c) Archimage - IDP Consulting 2017
// Author: Cyril PATRIGEON - BeSecure Labs
//

namespace bs\IDP\ArchiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;

/**
 * IDPAudit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\IDP\ArchiveBundle\Entity\IDPAuditRepository")
 */
class IDPAudit
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
     * @var integer
     *
     * @ORM\Column( name="userId", type="integer", nullable=true )
     */
    private $userId; // id of user

    /**
     * @var integer
     *
     * @ORM\Column( name="timestamp", type="integer", nullable=true )
     */
    private $timestamp; // timestamp of action

    /**
     * @var integer
     *
     * @ORM\Column( name="field", type="integer", nullable=true )
     */
    private $field; // field modified

    /**
     * @var string
     *
     * @ORM\Column( name="new_str", type="string", nullable=true )
     */
    private $new_str; // new value of string field

    /**
     * @var integer
     *
     * @ORM\Column( name="new_int", type="integer", nullable=true )
     */
    private $new_int; // new value of integer field

    /**
     * @var string
     *
     * @ORM\Column( name="old_str", type="string", nullable=true )
     */
    private $old_str;

    /**
     * @var integer
     *
     * @ORM\Column( name="old_int", type="integer", nullable=true )
     */
    private $old_int;

    /**
     * @var integer
     *
     * @ORM\Column( name="entity", type="integer", nullable=true )
     */
    private $entity;

    /**
     * @var integer
     *
     * @ORM\Column( name="entity_id", type="integer", nullable=true )
     */
    private $entity_id;

    /**
     * @var action
     *
     * @ORM\Column( name="action", type="integer", nullable=true )
     */
    private $action; // create, modify, delete

    /**
     * @var completUAid
     *
     * @ORM\Column( name="complet_ua_id", type="integer", nullable=true )
     */
    private $complete_ua_id;

    /**
     * @var integer
     *
     * @ORM\Column( name="objectType", type="integer", nullable=true )
     */
    private $objectType; // type of object during status modification

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
     * Get userId
     *
     * @return integer
     */
    public function getUserid( ){
        return $this->userId;
    }
    /**
     * Set userId
     *
     * @param integer $userId
     * @return IDPAudit
     */
    public function setUserid( $userId ){
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get timestamp
     *
     * @return integer
     */
    public function getTimestamp( ){
        return $this->timestamp;
    }
    /**
     * Set timestamp
     *
     * @param integer timestamp
     * @return IDPAudit
     */
    public function setTimestamp( $timestamp ){
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * Get field
     *
     * @return integer
     */
    public function getField( ){
        return $this->field;
    }
    /**
     * Set field
     *
     * @param integer field
     * @return IDPAudit
     */
    public function setField( $field ){
        $this->field = $field;
        return $this;
    }

    /**
     * Get new_str
     *
     * @return string
     */
    public function getNewstr( ){
        return $this->new_str;
    }
    /**
     * Set new_str
     *
     * @param string $new_str
     * @return IDPAudit
     */
    public function setNewstr( $newstr ){
        $this->new_str = $newstr;
        return $this;
    }

    /**
     * Get new_int
     *
     * @return integer
     */
    public function getNewint( ){
        return $this->new_int;
    }
    /**
     * Set new_int
     *
     * @param integer $new_int
     * @return IDPAudit
     */
    public function setNewint( $newint ){
        $this->new_int = $newint;
        return $this;
    }

    /**
     * Get old_str
     *
     * @return string
     */
    public function getOldstr( ){
        return $this->old_str;
    }
    /**
     * Set old_str
     *
     * @param string oldstr
     * @return IDPAudit
     */
    public function setOldstr( $oldstr ){
        $this->old_str = $oldstr;
        return $this;
    }

    /**
     * Get old_int
     *
     * @return integer
     */
    public function getOldint( ){
        return $this->old_int;
    }
    /**
     * Set old_int
     *
     * @param integer oldint
     * @return IDPAudit
     */
    public function setOldint( $oldint ){
        $this->old_int = $oldint;
        return $this;
    }

    /**
     * Get entity
     *
     * @return integer
     */
    public function getEntity( ){
        return $this->entity;
    }
    /**
     * Set entity
     *
     * @param integer entity
     * @return IDPAudit
     */
    public function setEntity( $entity ){
        $this->entity = $entity;
        return $this;
    }

    /**
     * Get entity_id
     *
     * @return integer
     */
    public function getEntityid( ){
        return $this->entity_id;
    }
    /**
     * Set entity_id
     *
     * @param integer entityid
     * @return IDPAudit
     */
    public function setEntityid( $entityid ){
        $this->entity_id = $entityid;
    }

    /**
     * Get action
     *
     * @return integer
     */
    public function getAction( ){
        return $this->action;
    }
    /**
     * Set action
     *
     * @param integer action
     * @return IDPAudit
     */
    public function setAction( $action ){
        $this->action = $action;
        return $this;
    }

    /**
     * Get complete_ua_id
     *
     * @return integer
     */
    public function getCompleteuaid( ){
        return $this->complete_ua_id;
    }
    /**
     * Set complete_ua_id
     *
     * @param integer $completeuaid
     * @return IDPAudit
     */
    public function setCompleteuaid( $completeuaid ){
        $this->complete_ua_id = $completeuaid;
        return $this;
    }

    /**
     * Get objectType
     *
     * @return integer
     */
    public function getObjectType(){
        return $this->objectType;
    }

    /**
     * Set objectType
     *
     * @param integer $objectType
     * @return IDPAudit
     */
    public function setObjectType( $objectType ){
        $this->objectType = $objectType;
        return $this;
    }
}
