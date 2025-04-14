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
use bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class ManageDBDocumentNaturesJsonController extends Controller
{

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}


	// =========================================================================
	// -- MANAGE DB -- DOCUMENT NATURES --

	public function documentnatureslistAction( Request $request ){
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

		// Ask databse for total document natures
		$totalDocumentNatures = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDocumentNatures' )->countDocumentNatures($search);
		// Ask database for document nature range
		$documentNatures = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDocumentNatures' )->loadDocumentNaturesDatas( $search, $sort, $order, $limit, $offset );

		$response_data = array();

		foreach( $documentNatures as $documentNature ){

			$cansuppress = true;
			if( !$documentNature->getDocumentTypes()->isEmpty() )
				$cansuppress = false;
			else {
				$archive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive')
					->findOneBy( array( 'documentnature' => $documentNature->getId() ) );
				if( $archive ) $cansuppress = false;
			}

			$line = array(
				'id' => $documentNature->getId(),
				'longname' => $documentNature->getLongname(),
				'cansuppress' => $cansuppress
			);
			array_push( $response_data, $line );
		}

		if( $limit > 0 )
			$return = array( 'total' => intval( $totalDocumentNatures ), 'rows' => $response_data );
		else
			$return = $response_data;

		return $this->jsonResponse( $return );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function documentnaturesdeleteAction( Request $request ){
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

		$documentNature = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
			->find($id);

		if($documentNature == null){
			return $this->jsonResponse( array( 'message' => 'System Error: Document Nature Id does not exist !'), 417 );
		}
		else{
			$em = $this->getDoctrine()->getManager();
			$em->remove($documentNature);
			$em->flush();

			return $this->jsonResponse( array('success' => true) );
		}

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function documentnaturesaddAction( Request $request ){
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

		$documentNature = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPDocumentNatures' )
			->findBy( array( 'longname' => $name ) );

		if( $documentNature != null ){
			return $this->jsonResponse( array( 'message' => "La Nature de Document $name existe déjà !"), 417 );
		}

		$documentNature = new IDPDocumentNatures();
		$documentNature->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($documentNature);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function documentnaturesModifyAction( Request $request ){
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

		$documentNature = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPDocumentNatures' )
			->find( $id );

		if( $documentNature == null ){
			return $this->jsonResponse( array( 'message' => "System Error: Document Nature Id does not exist !"), 417 );
		}

		$documentNature->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($documentNature);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function documentnatureslinkslistAction(Request $request)
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

		if( $parameters->has( 'documentNatureId' ) )
			$documentNatureId = $parameters->get('documentNatureId');
		else
			return $this->jsonResponse( array('message' => 'System Error : documentNatureId to delete needed'), 400 );

		$documentNature = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
        		->find($documentNatureId);

		if($documentNature == null)
			return $this->jsonResponse( array('message' => 'System Error'), 417 );
		else{
			// Retreive linked table objects
			$services = $documentNature->getServices();
			$return = [];

			foreach( $services as $service ){
				array_push( $return, array( 'serviceID' => $service->getId() ));
			}

			return $this->jsonResponse( $return );
		}
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function documentnatureslinkssetAction(Request $request)
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
		if( $parameters->has( 'documentNatureId' ) )
			$documentNatureId = $parameters->get('documentNatureId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : documentNatureId to modify needed' ), 400 );

		if( $parameters->has( 'serviceId' ) )
			$serviceId = $parameters->get('serviceId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : serviceId to modify needed' ), 400 );

		// Get back entities to make a new link
		$documentNature = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
        		->find($documentNatureId);

		if($documentNature == null)
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
			if( !$documentNature->getServices()->contains( $service ) ){
				// We have two entities, make a link between
				$em = $this->getDoctrine()->getManager();
				$documentNature->addService( $service );
				$service->addDocumentNatures( $documentNature );
				$em->persist( $documentNature );
				$em->persist( $service );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Service added successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function documentnatureslinksunsetAction(Request $request)
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
		if( $parameters->has( 'documentNatureId' ) )
			$documentNatureId = $parameters->get('documentNatureId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : documentNatureId to modify needed' ), 400 );

		if( $parameters->has( 'serviceId' ) )
			$serviceId = $parameters->get('serviceId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : serviceId to modify needed' ), 400 );

		// Get back entities to make a new link
		$documentNature = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
        		->find($documentNatureId);
		if( $documentNature == null )
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
			$services = $documentNature->getServices();

		if( $services == null )
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

        $em = $this->getDoctrine()->getManager();
		foreach( $services as $service )
			if( $documentNature->getServices()->contains( $service ) ){
				// We have two entities, make a link between
				$documentNature->removeService( $service );
				$service->removeDocumentNatures( $documentNature );
				$em->persist( $documentNature );
				$em->persist( $service );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Right removed successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

}