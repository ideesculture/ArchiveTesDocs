<?php
namespace bs\Core\UsersBundle\SessionMng;

use bs\Core\UsersBundle\Controller\UsersController;
use \DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use bs\Core\UsersBundle\SessionMng\bsCoreSessionManager;

class bsCoreUserSession extends bsCoreSessionManager
{
	const BS_CORE_USER_LOGGED = 'bsCoreUserLogged' ;
	const BS_CORE_USER_ID = 'bsCoreUserID' ;
	const BS_CORE_USER_LASTACTION = 'bsCoreUserLastAction';

	private $doctrine;
	private $user;
	private $user_extension;
	private $user_services;
	private $user_addresses;

	function __construct( $session, $doctrine, $user = null ){
		parent::__construct( $session );
		$this->doctrine = $doctrine;
		$this->user = $user;			// User object
		if( $user ){
			$temp_ = $this->doctrine->getRepository('bsCoreUsersBundle:IDPUserExtensions')->findBy(
					array('user' => $this->user->getId() ), null, 1 );
			$this->user_extension = $temp_?$temp_[0]:null; // User extension
			$this->user_services = $this->doctrine->getRepository('bsCoreUsersBundle:IDPUserServices')->findBy(
					array('user' => $this->user->getId() ), null );	// User Services allowed List
            $this->user_addresses = $this->doctrine->getRepository('bsCoreUsersBundle:IDPUserAddresses')->findBy(
                    array('user' => $this->user->getId() ), null ); // User Addresses linked list
		} else {
			$this->user_extension = null;
			$this->user_services = null;
			$this->user_addresses = null;
		}
	}

	public function isUserLogged( $phpsessid = null, $update = true ){
		if( $this->checkSession( self::BS_CORE_USER_LOGGED ) ){
            $now_ = new DateTime();
            $now = $now_->getTimestamp();

			if( $this->user != null ){

			    if( $phpsessid != null && $this->user->getPhpsessid() != $phpsessid )
			        return false;

				if( $update && $this->user ){

					$this->user->setLastaction( $now );
					$this->doctrine->getManager()->persist( $this->user );
					$this->doctrine->getManager()->flush();

					$this->setValue( self::BS_CORE_USER_LOGGED, true );
					$this->setValue( self::BS_CORE_USER_LASTACTION, $now );
				}
				return true;
			}

			if( !$this->user ){
				$userID = $this->getValue( self::BS_CORE_USER_ID, -1 );
				if( $userID < 0 )
					return false;
				$this->user = $this->doctrine->getRepository('bsCoreUsersBundle:bsUsers')->find( $userID );
				$temp_ = $this->doctrine->getRepository('bsCoreUsersBundle:IDPUserExtensions')->findBy(
					array('user' => $userID ), null, 1 );
				$this->user_extension = $temp_?$temp_[0]:null;
				$this->user_services = $this->doctrine->getRepository('bsCoreUsersBundle:IDPUserServices')->findBy(
					array('user' => $userID ), null );
				$this->user_addresses = $this->doctrine->getRepository('bsCoreUsersBundle:IDPUserAddresses')->findBy(
				    array('user' => $userID ), null );
			}

            if( $this->user && $phpsessid != null && $this->user->getPhpsessid() != $phpsessid )
                return false;

            if( $update && $this->user ){
				$now_ = new DateTime();
				$now = $now_->getTimestamp();

                $this->user->setLastaction( $now );
				$this->doctrine->getManager()->persist( $this->user );
				$this->doctrine->getManager()->flush();

				$this->setValue( self::BS_CORE_USER_LOGGED, true );
				$this->setValue( self::BS_CORE_USER_LASTACTION, $now );
			}
			return true;
		}
		return false;
	}

	public function logUser( $id ){
/*
		if( $this->isUserLogged(  ) )
			return false;
*/
		$now_ = new DateTime();
		$now = $now_->getTimestamp();

		$this->setValue( self::BS_CORE_USER_LOGGED, true );
		$this->setValue( self::BS_CORE_USER_ID, $id );
		$this->setValue( self::BS_CORE_USER_LASTACTION, $now );

		$this->user->setLastaction( $now );
		$this->user->setConnected( true );
		$this->doctrine->getManager()->persist( $this->user );
		$this->doctrine->getManager()->flush();

		return true;
	}

	public function unlogUser( ){
/*
		if( !$this->isUserLogged(  false ) )
			return null;
*/
		$this->closeSession( );

		$this->user->setConnected( false );
		$this->doctrine->getManager()->persist( $this->user );
		$this->doctrine->getManager()->flush();

		return true;
	}

	public function getUserRoles( ){
		if( !$this->isUserLogged(  ) )
			return null;

		return $this->user->getRoles();
	}

	public function hasRole( $roleStr ){
	    foreach ( $this->getUserRoles() as $role ){
	        if( $role->getName() == $roleStr )
	            return true;
        }
        return false;
    }

	public function getUserFirstRoleName(){
		if( !$this->isUserLogged(  ) )
			return null;

		if( !$this->user->getRoles() )
			return null;

		foreach( $this->user->getRoles() as $role )
			return $role->getName();
	}

	public function getUserScale(){
	    $userRole = $this->getUserRoles();
	    if( !$userRole ) return 9999;
	    return $userRole[0]->getScale();
    }

	public function getUserRights( ){
		if( !$this->isUserLogged(  ) )
			return null;

		return $this->user->getRights();
	}

	public function getUserServices( ){
		if( !$this->isUserLogged(  ) )
			return null;

		return $this->user_services;
	}

	public function getUserAddresses( ){
	    if( !$this->isUserLogged( ) )
	        return null;

	    return $this->user_addresses;
    }

	public function isUserGotRight( $right ){
		if( !$this->isUserLogged(  ) )
			return false;

		$rights = $this->user->getRights();

		if( $rights == null )
			return false;

		foreach( $rights as $elem )
			if( $elem->getName() == $right )
				return true;

		return false;
	}

	public function getUserId( ){
		if( !$this->isUserLogged(  ) )
			return null;

		return $this->user->getId();
	}

	public function getUser(){
		if( !$this->isUserLogged(  ) )
			return null;

		return $this->user;
	}

	public function getUserExtension(){
		if( !$this->isUserLogged(  ) )
			return null;

		return $this->user_extension;
	}
}

?>