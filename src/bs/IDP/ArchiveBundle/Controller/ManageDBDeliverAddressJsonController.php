<?php

namespace bs\IDP\ArchiveBundle\Controller;

use bs\Core\UsersBundle\Entity\IDPUserAddresses;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;
use bs\IDP\ArchiveBundle\Form\Type\IDPArchiveType;
use bs\IDP\ArchiveBundle\Form\Type\IDPCustTransferType;
use bs\IDP\DashboardBundle\Entity\bsMessages;

use bs\IDP\BackofficeBundle\Entity\IDPDeliverAddress;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class ManageDBDeliverAddressJsonController extends Controller
{

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	// =========================================================================
	// -- MANAGE DB -- DELIVER ADDRESS --

	public function deliveraddresslistAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() ){
			return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403 );
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        //		if($request->isXmlHttpRequest()) {

		// GET
		$parameters = $request->query;
		// POST
		// $parameters = $request->request

		if( $parameters->has( 'limit' ) )
			$limit = $parameters->get('limit');
		else
			$limit = -1;
		if( $parameters->has( 'offset' ) )
			$offset = $parameters->get('offset');
		else
			$offset = 0;
		if( $parameters->has( 'sort' ) )
			$sort = $parameters->get('sort');
		else
			$sort = 'longname';
		if( $parameters->has( 'order' ) )
			$order = $parameters->get('order');
		else
			$order = 'asc';
        if( $parameters->has( 'search' ) )
            $search = $parameters->get('search');
        else
            $search = null;

		// Ask databse for total deliver address
		$totalDeliverAddress = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDeliverAddress' )->countDeliverAddress($search);
		// Ask database for deliver address range
		$deliverAddress = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDeliverAddress' )->loadDeliverAddressDatas( $search, $sort, $order, $limit, $offset );

		$response_data = array();

		foreach( $deliverAddress as $deliver ){

			$cansuppress = true;
			$archive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive')
				->findOneBy( array( 'precisionwhere' => $deliver->getId() ) );
			if( $archive ) $cansuppress = false;

			$line = array(
				'id' => $deliver->getId(),
				'longname' => $deliver->getLongname(),
				'cansuppress' => $cansuppress
			);
			array_push( $response_data, $line );
		}

		if( $limit > 0 )
			$return = array( 'total' => intval( $totalDeliverAddress ), 'rows' => $response_data );
		else
			$return = $response_data;

		return $this->jsonResponse( $return );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function deliveraddressdeleteAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() ){
			return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403 );
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        //		if($request->isXmlHttpRequest()) {

		// GET
		$parameters = $request->query;
		// POST
		// $parameters = $request->request

		$id = $parameters->get('id');

		$deliverAddress = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')
			->find($id);

		if($deliverAddress == null){
			return $this->jsonResponse( array( 'message' => 'System Error: Deliver Address Id does not exist !'), 400 );
		}
		else{
			$em = $this->getDoctrine()->getManager();
			$em->remove($deliverAddress);
			$em->flush();

			return $this->jsonResponse( array('success' => true) );
		}

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function deliveraddressaddAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() ){
			return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403 );
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        //		if($request->isXmlHttpRequest()) {

		// GET
		$parameters = $request->query;
		// POST
		// $parameters = $request->request

		$name = $parameters->get('name');

		$deliverAddress = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPDeliverAddress' )
			->findBy( array( 'longname' => $name ) );

		if( $deliverAddress != null ){
			return $this->jsonResponse( array( 'message' => "L'adresse de livraison $name existe déjà !"), 400 );
		}

		$deliverAddress = new IDPDeliverAddress();
		$deliverAddress->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($deliverAddress);
		$em->flush();

        // Affect this address to all admin & super-admin (role <= 25)
        $usersToAffect = $this->getDoctrine()
            ->getRepository( 'bsCoreUsersBundle:bsUsers')
            ->findByRoleLimit( 25 );
        foreach( $usersToAffect as $user ){
            $userAddress = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserAddresses')->findOneBy( array( 'user' => $user->getId(), 'address' => $deliverAddress->getId() ));
            if( !$userAddress ) { // Add only if doesn't already exists
                $userAddress = new IDPUserAddresses();
                $userAddress->setUser($user);
                $userAddress->setAddress($deliverAddress);

                $em->persist($userAddress);
                $em->flush();
            }
        }

        return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function deliveraddressModifyAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() ){
			return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403 );
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        //		if($request->isXmlHttpRequest()) {

		// GET
		$parameters = $request->query;
		// POST
		// $parameters = $request->request

		$id = $parameters->get('id');
		$name = $parameters->get('name');

		$deliverAddress = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPDeliverAddress' )
			->find( $id );

		if( $deliverAddress == null ){
			return $this->jsonResponse( array( 'message' => "System Error: Deliver Address Id does not exist !"), 417 );
		}

		$deliverAddress->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($deliverAddress);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

}