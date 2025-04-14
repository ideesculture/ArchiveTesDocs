<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

use bs\Core\UsersBundle\Entity\IDPUserFiles;

/**
 * IDPUserFilesRepository
 */
class IDPUserFilesRepository extends EntityRepository
{
    public function getAllUserFiles( $user_id ){

        if( $user_id >= 0 )
            $query = $this->getEntityManager()
                ->createQuery("SELECT f FROM bsCoreUsersBundle:IDPUserFiles f WHERE f.userid = :id")
                ->setParameter('id', $user_id);
        else
            $query = $this->getEntityManager()
                ->createQuery("SELECT f FROM bsCoreUsersBundle:IDPUserFiles f");

        return $query->getResult();
    }

    public function getUserFilesResume( $user_id ){
        $all_result = $this->getAllUserFiles( $user_id );
        $response = [ 'in_progress' => false, 'nb_files' => 0, 'nb_files_unseen' => 0 ];

        if( !$all_result )
            return $response;

        foreach ($all_result as $userFile){
            if( $userFile->getInProgress() )    $response['in_progress'] = true;
            if( $userFile->getNbDownload() == 0 ) $response['nb_files_unseen']++;
            $response['nb_files']++;
        }
        return $response;
    }
}
