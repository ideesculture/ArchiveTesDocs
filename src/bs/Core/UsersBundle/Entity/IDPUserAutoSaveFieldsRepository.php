<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * IDPUserAutoSaveFieldsRepository
 */
class IDPUserAutoSaveFieldsRepository extends EntityRepository
{
    public function getArray( $id ){
        $query = $this->getEntityManager()
            ->createQuery("SELECT asf FROM bsCoreUsersBundle:IDPUserAutoSaveFields asf WHERE asf.id = :id")
            ->setParameter('id', $id);
        return $query->getArrayResult();
    }

}
