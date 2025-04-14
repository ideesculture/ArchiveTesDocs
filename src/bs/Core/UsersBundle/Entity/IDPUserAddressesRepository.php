<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * IDPUserAddressesRepository
 *
 */
class IDPUserAddressesRepository extends EntityRepository
{
    public function delete( $userID ){
        $queryStr = 'DELETE ';
        $queryStr .= ' FROM bsCoreUsersBundle:IDPUserAddresses ua ';
        $queryStr .= " WHERE ua.user=$userID ";

        $query = $this->getEntityManager()->createQuery( $queryStr );

        return $query->getResult();
    }

}
