<?php

namespace bs\Core\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\Core\AdminBundle\Security\IDPArchimageRights;

use bs\Core\BackofficeBundle\Entity\IDPUserPagesSettings;
use bs\Core\BackofficeBundle\Entity\IDPUserColumnsSettings;
use bs\Core\UsersBundle\Entity\IDPUserServices;
use bs\Core\UsersBundle\Entity\IDPUserAddresses;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class AdminJsonController extends Controller
{
	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	const ADMIN_ROLE = 100;

    const BSUFIELD_FIRSTNAME 			= 0;
    const BSUFIELD_NAME                 = 1;
    const BSUFIELD_LOGIN                = 2;
    const BSUFIELD_PASSWORD             = 3;
    const BSUFIELD_INITIAL              = 4;
    const BSUFIELD_ROLE                 = 5;
    const BSUFIELD_SERVICES             = 6;
    const BSUFIELD_ADDRESSES            = 7;

    // User actions

	public function listAction( Request $request )
	{
//		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Acces interdit, vous n\'avez pas les droits suffisants pour acceder a cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        // GET
			$parameters = $request->query;
			// POST
			 //$parameters = $request->request;

			if( $parameters->has( 'limit' ) )
				$limit = $parameters->get('limit');
			else
				$limit = 10;
			if( $parameters->has( 'offset' ) )
				$offset = $parameters->get('offset');
			else
				$offset = 0;
			if( $parameters->has( 'sort' ) )
				$sort = $parameters->get('sort');
			else
				$sort = 'login';
			if( $parameters->has( 'order' ) )
				$order = $parameters->get('order');
			else
				$order = 'asc';
            if( $parameters->has( 'search' ) )
                $search = $parameters->get('search');
            else
                $search = null;

			$currentUserRoleMax = 999;
			foreach( $bsUserSession->getUser()->getRoles() as $role )
				if( $role->getScale() < $currentUserRoleMax )
					$currentUserRoleMax = $role->getScale();

			$totalUsers = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsUsers' )->countUsers( $search );

			$users = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsUsers' )->loadUsers( $search, $sort, $order, $limit, $offset );

			$response_data = array();

			// TODO: Admin see all archivist and users and himself
			// Archivist see all users only

			foreach( $users as $user ){
				$userRoleMax = 0;
				foreach( $user->getRoles() as $role )
					if( $role->getScale() > $userRoleMax )
						$userRoleMax = $role->getScale();

				if( ($currentUserRoleMax == 0 ) || ($currentUserRoleMax < $userRoleMax) ){
					$line = array(
						'id' => $user->getId(),
						'lastname' => $user->getLastname(),
						'firstname' => $user->getFirstname(),
						'login' => $user->getLogin(),
                        'changepass' => $user->getChangepass(),
                        'connected' => $user->getConnected(),
                        'lastaction' => $user->getLastaction(),
                        'phpsessid' => $user->getPhpsessid()
					);
					array_push( $response_data, $line );
				}
			}
			$response = array( 'total' => intval( $totalUsers ), 'rows' => $response_data );

			return $this->jsonResponse( $response );
//		}

//		return $this->jsonResponse( array('message' => 'System Error : Only Ajax Request Allowed'), 419 );
	}

    protected function deleteUserSettings( $userID, $fullLog, &$log ){

        $upsRep = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPUserPagesSettings' );
        $ucsRep = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPUserColumnsSettings' );

        // Verify if settings exists
        $settings = $upsRep->findBy( array( 'user_id' => $userID ) );
        if( !$settings && count( $settings ) <= 1 ){
            array_push( $log, "User Page Settings doesn't exist for user: $userID" );
            return false;
        }

        $em = $this->getDoctrine()->getManager();
        $countPages = 0;
        $countColumns = 0;

        foreach ( $settings as $pageSettings ){
            $countPages++;

            $pageSettingsID = $pageSettings->getId();

            // Get all columns settings for the pageSettings
            $columnsSettings = $ucsRep->findBy( array( 'user_page_settings' => $pageSettings ) );
            if( $columnsSettings && count( $columnsSettings ) >= 1 ) {
                foreach ( $columnsSettings as $columnSettings ){
                    $countColumns++;
                    $columnSettingID = $columnSettings->getId();

                    // delete settings
                    $em->remove( $columnSettings );

                    if( $fullLog ){
                        if( $columnSettingID <= 0 )
                            array_push( $log, " - Error while deleting Column Settings $columnSettingID" );
                        else
                            array_push( $log, " - Column Settings $columnSettingID successfully deleted" );
                    }
                }
            }
            $em->remove( $pageSettings );
            if( $fullLog )
                array_push( $log, " * Page Settings $pageSettingsID successfully deleted" );
        }
        $em->flush();
        array_push( $log, "$countColumns Columns and $countPages Pages deleted" );
        return true;
    }

	public function deleteAction(Request $request)
	{
//		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        // GET
			$parameters = $request->query;
			// POST
			//$parameters = $request->request;

			if( $parameters->has( 'id' ) )
				$userID = $parameters->get('id');
			else {
				$content = json_encode(array('message' => 'System Error : UserId to delete needed'));
				return new Response($content, 419);
			}

			$user = $this->getDoctrine()
        		->getRepository('bsCoreUsersBundle:bsUsers')
        		->find($userID);

			if($user == null){
				$return = array('success' => false);
			}
			else{
				$em = $this->getDoctrine()->getManager();

                // delete settings
                $log = array();
                $this->deleteUserSettings( $userID, false, $log );

				// delete Extension
				$userExts = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserExtensions')->findBy( array( 'user' => $userID ));
				foreach( $userExts as $userExt )
					$em->remove( $userExt );
				$em->flush();

				// delete Services link
				$userServices = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserServices')->findBy( array( 'user' => $userID ));
				foreach( $userServices as $userService )
					$em->remove( $userService );
				$em->flush();

				// delete Addresses link
                $userAddresses = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserAddresses')->findBy( array( 'user' => $userID ));
                foreach( $userAddresses as $userAddress )
                    $em->remove( $userAddress );
                $em->flush();

                // Delete Asf
                $user_asf = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserAutoSaveFields')->findOneBy( array( 'user_id' => $userID ));
                if( $user_asf ){
                    $em->remove( $user_asf );
                    $em->flush();
                }

				// delete user
				$em->remove($user);
				$em->flush();

				$return = array('success' => true);
			}

			$response = new Response(json_encode($return));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
			/*
		}else{
			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
 		return new Response($content, 419);
		}
			*/
	}

	public function getRolesListAction(Request $request)
	{	// should send only allowed Roles based on user role

		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

            $return = [];

			// Retreive linked table objects
			$roles = $this->getDoctrine()
							->getRepository('bsCoreUsersBundle:bsRoles')
							->findAllScaleMin( $bsUserSession->getUserRoles()[0]->getScale() );

			$roleList = [];
			foreach( $roles as $role ){
				array_push( $return, array( 'id' => $role->getId(), 'description' => $role->getDescription() ));
			}

			$response = new Response(json_encode($return));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}else{
			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
			return new Response($content, 419);
		}
	}

	public function getRightsListAction(Request $request)
	{	// should send only allowed Roles based on user role

		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

            $return = [];

			// Retreive linked table objects
			$rights = $this->getDoctrine()
							->getRepository('bsCoreUsersBundle:bsRights')
							->findAllScaleMin( $bsUserSession->getUserRoles()[0]->getScale() );

			$rightsList = [];
			foreach( $rights as $right ){
				array_push( $return, array( 'id' => $right->getId(), 'description' => $right->getDescription() ));
			}

			$response = new Response(json_encode($return));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}else{
			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
			return new Response($content, 419);
		}
	}

	public function getUserRightsListAction(Request $request)
	{	// should send only allowed Roles based on user role

		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

            // GET
			//$parameters = $request->query;
			// POST
			$parameters = $request->request;

			if( $parameters->has( 'userId' ) )
				$userID = $parameters->get('userId');
			else {
				$content = json_encode(array('message' => 'System Error : UserId to delete needed'));
				return new Response($content, 419);
			}

			$user = $this->getDoctrine()
        		->getRepository('bsCoreUsersBundle:bsUsers')
        		->find($userID);

			if($user == null){
				$content = json_encode(array('message' => 'System Error'));
				return new Response($content, 419);
			}else{
				// Retreive linked table objects
				$rights = $user->getRights();
				$return = [];

				foreach( $rights as $right ){
					array_push( $return, array( 'id' => $right->getId() ));
				}

				$response = new Response(json_encode($return));
				$response->headers->set('Content-Type', 'application/json');
				return $response;
			}
		}else{
			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
			return new Response($content, 419);
		}
	}

	public function setUserRightAction(Request $request)
	{	// should send only allowed Roles based on user role

		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

            // GET
			//$parameters = $request->query;
			// POST
			$parameters = $request->request;

			// Parameter verification
			if( $parameters->has( 'userId' ) )
				$userID = $parameters->get('userId');
			else
				return $this->jsonResponse( array( 'message' => 'System Error : UserId to modify needed' ), 419 );

			if( $parameters->has( 'rightId' ) )
				$rightID = $parameters->get('rightId');
			else
				return $this->jsonResponse( array( 'message' => 'System Error : RightId to modify needed' ), 419 );

			// Get back entities to make a new link
			$user = $this->getDoctrine()
        		->getRepository('bsCoreUsersBundle:bsUsers')
        		->find($userID);

			if($user == null)
				return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

			// If rightID is -1 this means set all rights, so in both case we make a collection of what to unset
			if( $rightID != -1 ){
				$rights = new \Doctrine\Common\Collections\ArrayCollection();
				$right = $this->getDoctrine()
					->getRepository('bsCoreUsersBundle:bsRights')
					->find($rightID);
				$rights[] = $right;
			}
			else
				$rights = $this->getDoctrine()
					->getRepository('bsCoreUsersBundle:bsRights')
					->findAll( );

			if($rights == null)
				return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

			foreach( $rights as $right )
				if( !$user->getRights()->contains( $right ) ){
					// We have two entities, make a link between
					$em = $this->getDoctrine()->getManager();
					$user->addRight( $right );
					$right->addUser( $user );
					$em->persist( $right );
					$em->persist( $user );
					$em->flush();
				}

			return $this->jsonResponse( array( 'message' => 'Right added successfully' ));
		} else
			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function unsetUserRightAction(Request $request)
	{	// should send only allowed Roles based on user role

		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

            // GET
			//$parameters = $request->query;
			// POST
			$parameters = $request->request;

			// Parameter verification
			if( $parameters->has( 'userId' ) )
				$userID = $parameters->get('userId');
			else
				return $this->jsonResponse( array( 'message' => 'System Error : UserId to modify needed' ), 419 );

			if( $parameters->has( 'rightId' ) )
				$rightID = $parameters->get('rightId');
			else
				return $this->jsonResponse( array( 'message' => 'System Error : RightId to modify needed' ), 419 );

			// Get back entities to make a new link
			$user = $this->getDoctrine()
        		->getRepository('bsCoreUsersBundle:bsUsers')
        		->find($userID);
			if( $user == null )
				return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

			// If rightID is -1 this means unset all rights, so in both case we make a collection of what to unset
			if( $rightID != -1 ){
				$rights = new \Doctrine\Common\Collections\ArrayCollection();
				$right = $this->getDoctrine()
					->getRepository('bsCoreUsersBundle:bsRights')
					->find($rightID);
				$rights[] = $right;
			}
			else
				$rights = $user->getRights();

			if( $rights == null )
				return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

			foreach( $rights as $right )
				if( $user->getRights()->contains( $right ) ){
					// We have two entities, make a link between
					$em = $this->getDoctrine()->getManager();
					$user->removeRight( $right );
					$right->removeUser( $user );
					$em->persist( $right );
					$em->persist( $user );
					$em->flush();
				}

			return $this->jsonResponse( array( 'message' => 'Right removed successfully' ));
		} else
			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

    public function toggleChangePassAction( Request $request ) {

        if($request->isXmlHttpRequest()) {

            $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
            if( !$bsUserSession->isUserLogged() )
                return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

            if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
                return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

            // GET
            //$parameters = $request->query;
            // POST
            $parameters = $request->request;

            // Parameter verification
            if( $parameters->has( 'userId' ) )
                $userID = $parameters->get('userId');
            else
                return $this->jsonResponse( array( 'message' => 'System Error : UserId to modify needed' ), 419 );

            // Get back entities to make a new link
            $user = $this->getDoctrine()
                ->getRepository('bsCoreUsersBundle:bsUsers')
                ->find($userID);
            if( $user == null )
                return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

            $user->setChangepass( !$user->getChangepass() );
//            if( $user->getChangepass() )
//                $user->setPassword( md5( $user->getLogin() ) );
            $em = $this->getDoctrine()->getManager();
            $em->persist( $user );
            $em->flush();

            return $this->jsonResponse( array( 'message' => 'Change pass toggled successfully' ));
        }

        return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
    }

    public function unlockUserAction( Request $request ) {

        if($request->isXmlHttpRequest()) {

            $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
            if( !$bsUserSession->isUserLogged() )
                return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

            if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
                return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

            // GET
            //$parameters = $request->query;
            // POST
            $parameters = $request->request;

            // Parameter verification
            if( $parameters->has( 'userId' ) )
                $userID = $parameters->get('userId');
            else
                return $this->jsonResponse( array( 'message' => 'System Error : UserId to modify needed' ), 419 );

            // Get back entities to make a new link
            $user = $this->getDoctrine()
                ->getRepository('bsCoreUsersBundle:bsUsers')
                ->find($userID);
            if( $user == null )
                return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

            $user->setConnected( false );
            $user->setPhpsessid( null );
            $em = $this->getDoctrine()->getManager();
            $em->persist( $user );
            $em->flush();

            return $this->jsonResponse( array( 'message' => 'User disconnected successfully' ));
        }

        return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
    }

	// Role Actions
	public function rolesListAction( Request $request )
	{
		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

            // GET
			$parameters = $request->query;
			// POST
			//$parameters = $request->request;

			if( $parameters->has( 'limit' ) )
				$limit = $parameters->get('limit');
			else
				$limit = 10;
			if( $parameters->has( 'offset' ) )
				$offset = $parameters->get('offset');
			else
				$offset = 0;
			if( $parameters->has( 'sort' ) )
				$sort = $parameters->get('sort');
			else
				$sort = 'id';
			if( $parameters->has( 'order' ) )
				$order = $parameters->get('order');
			else
				$order = 'asc';

			$totalRoles = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsRoles' )->count();

			$roles = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsRoles' )->findBy(
				array (),						// no criteria
				array( $sort => $order ),
				$limit,
				$offset );

			$response_data = array();
			foreach( $roles as $role ){
				$line = array(
					'id' => $role->getId(),
					'name' => $role->getName(),
					'description' => $role->getDescription(),
					'scale' => $role->getScale()
				);
				array_push( $response_data, $line );
			}
			$response = array( 'total' => intval( $totalRoles ), 'rows' => $response_data );

			return $this->jsonResponse( $response );
		}
		return $this->jsonResponse( array('message' => 'System Error : Only Ajax Request Allowed'), 419 );
	}

	public function rolesDeleteAction(Request $request)
	{
		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

			// GET
			//$parameters = $request->query;
			// POST
			$parameters = $request->request;

			if( $parameters->has( 'id' ) )
				$roleID = $parameters->get('id');
			else {
				$content = json_encode(array('message' => 'System Error : RoleId to delete needed'));
				return new Response($content, 419);
			}

			$role = $this->getDoctrine()
        		->getRepository('bsCoreUsersBundle:bsRoles')
        		->find($roleID);

			if($role == null){
				$return = array('success' => false);
			}
			else{
				$em = $this->getDoctrine()->getManager();

				// delete role
				$em->remove($role);
				$em->flush();

				$return = array('success' => true);
			}

			$response = new Response(json_encode($return));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}else{
			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
			return new Response($content, 419);
		}
	}

	public function getRoleRightsListAction(Request $request)
	{	// should send only allowed Roles based on user role

		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

			// GET
			//$parameters = $request->query;
			// POST
			$parameters = $request->request;

			if( $parameters->has( 'roleId' ) )
				$roleID = $parameters->get('roleId');
			else {
				$content = json_encode(array('message' => 'System Error : RoleId to delete needed'));
				return new Response($content, 419);
			}

			$role = $this->getDoctrine()
        		->getRepository('bsCoreUsersBundle:bsRoles')
        		->find($roleID);

			if($role == null){
				$content = json_encode(array('message' => 'System Error'));
				return new Response($content, 419);
			}else{
				// Retreive linked table objects
				$rights = $role->getRights();
				$return = [];

				foreach( $rights as $right ){
					array_push( $return, array( 'id' => $right->getId() ));
				}

				$response = new Response(json_encode($return));
				$response->headers->set('Content-Type', 'application/json');
				return $response;
			}
		}else{
			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
			return new Response($content, 419);
		}
	}

	public function setRoleRightAction(Request $request)
	{
		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

            // GET
			//$parameters = $request->query;
			// POST
			$parameters = $request->request;

			// Parameter verification
			if( $parameters->has( 'roleId' ) )
				$roleID = $parameters->get('roleId');
			else
				return $this->jsonResponse( array( 'message' => 'System Error : RoleId to modify needed' ), 419 );

			if( $parameters->has( 'rightId' ) )
				$rightID = $parameters->get('rightId');
			else
				return $this->jsonResponse( array( 'message' => 'System Error : RightId to modify needed' ), 419 );

			// Get back entities to make a new link
			$role = $this->getDoctrine()
        		->getRepository('bsCoreUsersBundle:bsRoles')
        		->find($roleID);

			if($role == null)
				return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

			// If rightID is -1 this means set all rights, so in both case we make a collection of what to unset
			if( $rightID != -1 ){
				$rights = new \Doctrine\Common\Collections\ArrayCollection();
				$right = $this->getDoctrine()
					->getRepository('bsCoreUsersBundle:bsRights')
					->find($rightID);
				$rights[] = $right;
			}
			else
				$rights = $this->getDoctrine()
					->getRepository('bsCoreUsersBundle:bsRights')
					->findAll( );

			if($rights == null)
				return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

			foreach( $rights as $right )
				if( !$role->getRights()->contains( $right ) ){
					// We have two entities, make a link between
					$em = $this->getDoctrine()->getManager();
					$role->addRight( $right );
					$right->addRole( $role );
					$em->persist( $right );
					$em->persist( $role );
					$em->flush();
				}

			return $this->jsonResponse( array( 'message' => 'Right added successfully' ));
		} else
			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function unsetRoleRightAction(Request $request)
	{
		if($request->isXmlHttpRequest()) {

			$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
			if( !$bsUserSession->isUserLogged() )
				return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

			if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
				return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

            // Reconciliation in progress
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
            if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
                return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

            // GET
			//$parameters = $request->query;
			// POST
			$parameters = $request->request;

			// Parameter verification
			if( $parameters->has( 'roleId' ) )
				$roleID = $parameters->get('roleId');
			else
				return $this->jsonResponse( array( 'message' => 'System Error : UserId to modify needed' ), 419 );

			if( $parameters->has( 'rightId' ) )
				$rightID = $parameters->get('rightId');
			else
				return $this->jsonResponse( array( 'message' => 'System Error : RightId to modify needed' ), 419 );

			// Get back entities to make a new link
			$role = $this->getDoctrine()
        		->getRepository('bsCoreUsersBundle:bsRoles')
        		->find($roleID);
			if( $role == null )
				return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

			// If rightID is -1 this means unset all rights, so in both case we make a collection of what to unset
			if( $rightID != -1 ){
				$rights = new \Doctrine\Common\Collections\ArrayCollection();
				$right = $this->getDoctrine()
					->getRepository('bsCoreUsersBundle:bsRights')
					->find($rightID);
				$rights[] = $right;
			}
			else
				$rights = null; //$user->getRights();

			if( $rights == null )
				return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

			foreach( $rights as $right )
				if( $role->getRights()->contains( $right ) ){
					// We have two entities, make a link between
					$em = $this->getDoctrine()->getManager();
					$role->removeRight( $right );
					$right->removeRole( $role );
					$em->persist( $right );
					$em->persist( $role );
					$em->flush();
				}

			return $this->jsonResponse( array( 'message' => 'Right removed successfully' ));
		} else
			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function updatefieldAction( Request $request ){

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
            return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;

        $bsuid = $parameters->get('bsuid');
        $bsufield = $parameters->get('bsufield');
        $value = $parameters->get('value');
        $param = $parameters->get('param');

        $user = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:bsUsers')
            ->find($bsuid);
        if( $user == null )
            return $this->jsonResponse( array( 'message' => 'System Error' ), 419 );

        $em = $this->getDoctrine()->getManager();

        switch( $bsufield ){
            case self::BSUFIELD_FIRSTNAME:
                $user->setFirstname( $value );
                $em->persist( $user );
                break;

            case self::BSUFIELD_NAME:
                $user->setLastname( $value );
                $em->persist( $user );
                break;

            case self::BSUFIELD_LOGIN:
                $user->setLogin( $value );
                $em->persist( $user );
                break;

            case self::BSUFIELD_PASSWORD:
                $user->setPassword( md5( $value ) );
                $em->persist( $user );
                break;

            case self::BSUFIELD_INITIAL:
                $userExtensionRet = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserExtensions' )->findBy( array( 'user' => $user->getId() ));
                if( $userExtensionRet ) {
                    $userExtensionRet->setInitials(strtoupper($value));
                    $em->persist($userExtensionRet);
                } else {
                    return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
                }
                break;

            case self::BSUFIELD_ROLE:

                $role = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsRoles' )->find( $value );
                if( $role ){
                    // Remove old Role
                    foreach( $user->getRoles() as $oldrole ){
                        $user->removeRole( $oldrole );
                        $oldrole->removeUser( $user );
                        $em->persist( $user );
                        $em->persist( $oldrole );
                    }

                    $user->addRole( $role );	// Tricky ManyToMany Symfony2 update thing ! (but it works !)
                    $role->addUser( $user );
                    $em->persist( $role );
                    $em->persist( $user );

                    // Remove old Rights
                    foreach( $user->getRights() as $right ){
                        $user->removeRight( $right );
                        $right->removeUser( $user );
                        $em->persist( $right );
                        $em->persist( $user );
                    }

                    foreach( $role->getRights() as $right ){
                        $user->addRight( $right );
                        $right->addUser( $user );
                        $em->persist( $right );
                        $em->persist( $user );
                    }
                } else {
                    return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
                }

                break;

            case self::BSUFIELD_SERVICES:
                if( $value > 0 ){
                    $service = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPServices')->find( $value );
                    if( !$service )
                        return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
                } else $service = null;

                if( $service ){
                    if( $param == 0 ){ // OFF
                        // delete Service link
                        $userService = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserServices')->findOneBy( array( 'user' => $user->getId(), 'service' => $value ));
                        if( $userService ) {
                            $em->remove($userService);
                            $em->flush();
                        }
                    } else { // ON
                        $userService = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserServices')->findOneBy( array( 'user' => $user->getId(), 'service' => $value ));
                        if( !$userService ) { // Add only if doesn't already exists
                            $userService = new IDPUserServices();
                            $userService->setUser($user);
                            $userService->setService($service);

                            $em->persist($userService);
                            $em->flush();
                        }
                    }
                }

                break;

            case self::BSUFIELD_ADDRESSES:
                if( $value > 0 ){
                    $address = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')->find( $value );
                    if( !$address )
                        return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
                } else $service = null;

                if( $address ){
                    if( $param == 0 ){ // OFF
                        // delete Address link
                        $userAddress = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserAddresses')->findOneBy( array( 'user' => $user->getId(), 'address' => $value ));
                        if( $userAddress ) {
                            $em->remove($userAddress);
                            $em->flush();
                        }
                    } else { // ON
                        $userAddress = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserAddresses')->findOneBy( array( 'user' => $user->getId(), 'address' => $value ));
                        if( !$userAddress ) { // Add only if doesn't already exists
                            $userAddress = new IDPUserAddresses();
                            $userAddress->setUser($user);
                            $userAddress->setAddress($address);

                            $em->persist($userAddress);
                            $em->flush();
                        }
                    }
                }

                break;
        }

        $em->flush();

        return $this->jsonResponse( array( 'message' => 'Success' ), 200 );

//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}
    }

    public function generateinitialsAction( Request $request ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
            return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;
        if( $parameters->has( 'firstname' ) )
            $firstname = strtoupper( trim( $parameters->get('firstname') ) );
        else
            return $this->jsonResponse( array( 'message' => 'NOK' ), 200 );
        if( $parameters->has( 'lastname' ) )
            $lastname = strtoupper( trim( $parameters->get('lastname') ) );
        else
            return $this->jsonResponse( array( 'message' => 'NOK' ), 200 );

        if( strlen( $firstname ) + strlen( $lastname ) < 4 )
            return $this->jsonResponse( array( 'message' => 'NOK' ), 200 );

        // Get all initials in DB
        $result = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserExtensions' )->findAll();
        $allInitials = [];
        foreach( $result as $userExt )
            $allInitials[] = $userExt->getInitials();

        $initials = null;

        if( strlen( $firstname ) == 1 ){
            for( $i = 0; $i < strlen($lastname)-2; $i++ )
                for( $j = $i+1; $j < strlen($lastname)-1; $j++ )
                    for( $k = $j+1; $k < strlen($lastname); $k++ ){
                        $initials = strtoupper( $firstname[0] . $lastname[$i] . $lastname[$j] . $lastname[$k] );
                        if( !in_array( $initials, $allInitials ) ){
                            return $this->jsonResponse( array( 'message' => 'OK', 'initials' => $initials ), 200 );
                        }
                    }
        } else {
            if( strlen( $lastname ) == 1 ){
                for( $i = 0; $i < strlen($firstname)-2; $i++ )
                    for( $j = $i+1; $j < strlen($firstname)-1; $j++ )
                        for( $k = $j+1; $k < strlen($firstname); $k++ ){
                            $initials = strtoupper( $firstname[$i] . $firstname[$j] . $firstname[$k] . $lastname[0] );
                            if( !in_array( $initials, $allInitials ) ){
                                return $this->jsonResponse( array( 'message' => 'OK', 'initials' => $initials ), 200 );
                            }
                        }
            } else {
                for( $i = 0; $i < strlen($firstname)-1; $i++ )
                    for( $j = $i+1; $j < strlen($firstname); $j++ )
                        for( $k = 0; $k < strlen($lastname)-1; $k++)
                            for( $l = $k+1; $l < strlen($lastname); $l++ ){
                                $initials = strtoupper( $firstname[$i] . $firstname[$j] . $lastname[$k] . $lastname[$l] );
                                if( !in_array( $initials, $allInitials ) ){
                                    return $this->jsonResponse( array( 'message' => 'OK', 'initials' => $initials ), 200 );
                                }
                            }
            }
        }

        return $this->jsonResponse( array( 'message' => 'NOK' ), 200 );
    }
    public function verifyinitialsAction( Request $request ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
            return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;
        if( $parameters->has( 'initials' ) )
            $initials = $parameters->get('initials');
        else
            return $this->jsonResponse( array( 'message' => 'NOK' ), 200 );
        if( $parameters->has( 'uid' ) )
            $uid = $parameters->get('uid');
        else
            return $this->jsonResponse( array( 'message' => 'uid needed' ), 419 );

        $userExt = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserExtensions')->findOneBy( array( 'initials' => strtoupper( $initials ) ) );

        if( $userExt ){
            if( $userExt->getUser()->getId() == $uid )
                $msg = 'OK';
            else
                $msg = 'NOK';
        } else
            $msg = 'OK';

        return $this->jsonResponse( array( 'message' => $msg ), 200 );
    }
    public function verifyloginunicityAction( Request $request ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : Inactive Session'), 419 );

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) )
            return $this->jsonResponse( array( 'message' => 'Accès interdit, vous n\'avez pas les droits suffisants pour accéder à cette fonction.' ), 403 );

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;
        if( $parameters->has( 'login' ) )
            $login = $parameters->get('login');
        else
            return $this->jsonResponse( array( 'message' => 'NOK' ), 200 );
        if( $parameters->has( 'uid' ) )
            $uid = $parameters->get('uid');
        else
            return $this->jsonResponse( array( 'message' => 'NOK' ), 200 );

        $user = $this->getDoctrine()->getRepository('bsCoreUsersBundle:bsUsers')->findOneBy( array( 'login' => $login ) );
        if( $user )
            if( $user->getId() == $uid ) // Ok it's the current user
                $response = true;
            else                        // We found a user and it's not the current one
                $response = false;
        else                            // We don't found any user
            $response = true;


        return $this->jsonResponse( array( 'message' => $response?'OK':'NOK' ), 200 );
    }
}
