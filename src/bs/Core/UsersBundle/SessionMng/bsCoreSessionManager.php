<?php
namespace bs\Core\UsersBundle\SessionMng;

use Symfony\Component\HttpFoundation\Session\Session;

class bsCoreSessionManager
{
	private $bsCoreSession;

	function __construct( $session ){
		$this->bsCoreSession = $session;
	}

	public function checkSession( $key ){
		return $this->bsCoreSession->has( $key );
	}

	public function getValue( $key, $default ){
		if( $this->checkSession( $key ) )
			return $this->bsCoreSession->get( $key );
		else
			return $default;
	}

	public function setValue( $key, $value ){
		$this->bsCoreSession->set( $key, $value );
	}

	public function closeSession( ){
		$this->bsCoreSession->invalidate();
	}
}

?>