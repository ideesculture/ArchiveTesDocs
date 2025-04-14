<?php

namespace bs\IDP\ArchiveBundle\Controller;

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

use bs\IDP\BackofficeBundle\Entity\IDPServices;
use bs\IDP\BackofficeBundle\Entity\IDPDescriptions1;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class ManageDBDescriptions1JsonController extends Controller
{

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}


	// =========================================================================
	// -- MANAGE DB -- DESCRIPTIONS 1 --

	public function descriptions1listAction( Request $request ){
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
			$limit = 10;
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

		// Ask databse for total descriptions 1
		$totalDescriptions1 = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions1' )->countDescriptions1($search);
		// Ask database for descriptions 1 range
		$descriptions1 = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions1' )->loadDescriptions1Datas( $search, $sort, $order, $limit, $offset );

		$response_data = array();

		foreach( $descriptions1 as $description1 ){

			$cansuppress = true;
			$archive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive')
				->findOneBy( array( 'description1' => $description1->getId() ) );
			if( $archive ) $cansuppress = false;

			$line = array(
				'id' => $description1->getId(),
				'longname' => $description1->getLongname(),
				'cansuppress' => $cansuppress
			);
			array_push( $response_data, $line );
		}

		$return = array( 'total' => intval( $totalDescriptions1 ), 'rows' => $response_data );

		return $this->jsonResponse( $return );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function descriptions1deleteAction( Request $request ){
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

		$description1 = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')
			->find($id);

		if($description1 == null){
			return $this->jsonResponse( array( 'message' => 'System Error: Description 1 Id does not exist !'), 417 );
		}
		else{
			$em = $this->getDoctrine()->getManager();
			$em->remove($description1);
			$em->flush();

			return $this->jsonResponse( array('success' => true) );
		}

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function descriptions1addAction( Request $request ){
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

		$description1 = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions1' )
			->findBy( array( 'longname' => $name ) );

		if( $description1 != null ){
			return $this->jsonResponse( array( 'message' => "Le Descriptif 1 $name existe déjà !"), 417 );
		}

		$description1 = new IDPDescriptions1();
		$description1->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($description1);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function descriptions1ModifyAction( Request $request ){
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

		$description1 = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions1' )
			->find( $id );

		if( $description1 == null ){
			return $this->jsonResponse( array( 'message' => "System Error: Description 1 Id does not exist !"), 417 );
		}

		$description1->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($description1);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function descriptions1linkslistAction(Request $request)
	{
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
		//$parameters = $request->request;

		if( $parameters->has( 'description1Id' ) )
			$description1Id = $parameters->get('description1Id');
		else
			return $this->jsonResponse( array('message' => 'System Error : description1Id to delete needed'), 400 );

		$description1 = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')
        		->find($description1Id);

		if($description1 == null)
			return $this->jsonResponse( array('message' => 'System Error'), 417 );
		else{
			// Retreive linked table objects
			$services = $description1->getServices();
			$return = [];

			foreach( $services as $service ){
				array_push( $return, array( 'serviceID' => $service->getId() ));
			}

			return $this->jsonResponse( $return );
		}
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function descriptions1linkssetAction(Request $request)
	{
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
		//$parameters = $request->request;

		// Parameter verification
		if( $parameters->has( 'description1Id' ) )
			$description1Id = $parameters->get('description1Id');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : description1Id to modify needed' ), 400 );

		if( $parameters->has( 'serviceId' ) )
			$serviceId = $parameters->get('serviceId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : serviceId to modify needed' ), 400 );

		// Get back entities to make a new link
		$description1 = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')
        		->find($description1Id);

		if($description1 == null)
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

		// If $serviceId is -1 this means set all services, so in both case we make a collection of what to set
		if( $serviceId != -1 ){
			$services = new \Doctrine\Common\Collections\ArrayCollection();
			$service = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPServices')
				->find($serviceId);
			$services[] = $service;
		}
		else
			$services = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPServices')
				->findAll( );

		if($services == null)
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

		foreach( $services as $service )
			if( !$description1->getServices()->contains( $service ) ){
				// We have two entities, make a link between
				$em = $this->getDoctrine()->getManager();
				$description1->addService( $service );
				$service->addDescriptions1( $description1 );
				$em->persist( $description1 );
				$em->persist( $service );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Service added successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function descriptions1linksunsetAction(Request $request)
	{
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
		//$parameters = $request->request;

		// Parameter verification
		if( $parameters->has( 'description1Id' ) )
			$description1Id = $parameters->get('description1Id');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : description1Id to modify needed' ), 400 );

		if( $parameters->has( 'serviceId' ) )
			$serviceId = $parameters->get('serviceId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : serviceId to modify needed' ), 400 );

		// Get back entities to make a new link
		$description1 = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')
        		->find($description1Id);
		if( $description1 == null )
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

		// If $serviceId is -1 this means unset all services, so in both case we make a collection of what to unset
		if( $serviceId != -1 ){
			$services = new \Doctrine\Common\Collections\ArrayCollection();
			$service = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPServices')
				->find($serviceId);
			$services[] = $service;
		}
		else
			$services = $description1->getServices();

		if( $services == null )
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

        $em = $this->getDoctrine()->getManager();
		foreach( $services as $service )
			if( $description1->getServices()->contains( $service ) ){
				// We have two entities, make a link between
				$description1->removeService( $service );
				$service->removeDescriptions1( $description1 );
				$em->persist( $description1 );
				$em->persist( $service );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Right removed successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

}