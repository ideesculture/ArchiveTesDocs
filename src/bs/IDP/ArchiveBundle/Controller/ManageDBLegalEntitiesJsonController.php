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
use bs\IDP\BackofficeBundle\Entity\IDPLegalEntities;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class ManageDBLegalEntitiesJsonController extends Controller
{

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}


	// =========================================================================
	// -- MANAGE DB -- LEGAL ENTITIES --

	public function legalentitieslistAction( Request $request ){
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ? $this->container->get('logger') : null;

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


		// Ask databse for total legal entities
		$totalLegalEntities = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPLegalEntities' )->countLegalEntities($search);

		// Ask database for legal entities range
		$legalEntities = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPLegalEntities' )->loadLegalEntitiesDatas( $search,$sort, $order, $limit, $offset );

        $response_data = array();

		foreach( $legalEntities as $legalEntity ){

			$cansuppress = true;

            // only need 1 archive with this link to disable suppress mode
            $archive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive')
				->findOneBy( array( 'legalentity' => $legalEntity->getId() ) );

            if( $archive ) $cansuppress = false;

			$line = array(
				'id' => $legalEntity->getId(),
				'longname' => $legalEntity->getLongname(),
				'cansuppress' => $cansuppress
			);
			array_push( $response_data, $line );
		}


		$return = array( 'total' => intval( $totalLegalEntities ), 'rows' => $response_data );

		return $this->jsonResponse( $return );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function legalentitiesdeleteAction( Request $request ){
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

		$legalEntity = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')
			->find($id);

		if($legalEntity == null){
			return $this->jsonResponse( array( 'message' => 'System Error: Legal Entity Id does not exist !'), 417 );
		}
		else{
			$em = $this->getDoctrine()->getManager();
			$em->remove($legalEntity);
			$em->flush();

			return $this->jsonResponse( array('success' => true) );
		}

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function legalentitiesaddAction( Request $request ){
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

		$legalEntity = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPLegalEntities' )
			->findBy( array( 'longname' => $name ) );

		if( $legalEntity != null ){
			return $this->jsonResponse( array( 'message' => "L'entit� l�gale $name existe d�j� !"), 417 );
		}

		$legalEntity = new IDPLegalEntities();
		$legalEntity->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($legalEntity);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function legalentitiesModifyAction( Request $request ){
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

		$legalEntity = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPLegalEntities' )
			->find( $id );

		if( $legalEntity == null ){
			return $this->jsonResponse( array( 'message' => "System Error: Legal Entity Id does not exist !"), 417 );
		}

		$legalEntity->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($legalEntity);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function legalentitieslinkslistAction(Request $request)
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

		if( $parameters->has( 'legalEntityId' ) )
			$legalEntityId = $parameters->get('legalEntityId');
		else
			return $this->jsonResponse( array('message' => 'System Error : legalEntityId to delete needed'), 400 );

		$legalEntity = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')
        		->find($legalEntityId);

		if($legalEntity == null)
			return $this->jsonResponse( array('message' => 'System Error'), 417 );
		else{
			// Retreive linked table objects
			$services = $legalEntity->getServices();
			$return = [];

			foreach( $services as $service ){
				array_push( $return, array( 'serviceID' => $service->getId() ));
			}

			return $this->jsonResponse( $return );
		}
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function legalentitieslinkssetAction(Request $request)
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
		if( $parameters->has( 'legalEntityId' ) )
			$legalEntityId = $parameters->get('legalEntityId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : legalEntityId to modify needed' ), 400 );

		if( $parameters->has( 'serviceId' ) )
			$serviceId = $parameters->get('serviceId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : serviceId to modify needed' ), 400 );

		// Get back entities to make a new link
		$legalEntity = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')
        		->find($legalEntityId);

		if($legalEntity == null)
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
			if( !$legalEntity->getServices()->contains( $service ) ){
				// We have two entities, make a link between
				$em = $this->getDoctrine()->getManager();
				$legalEntity->addService( $service );
				$service->addLegalEntity( $legalEntity );
				$em->persist( $legalEntity );
				$em->persist( $service );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Service added successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function legalentitieslinksunsetAction(Request $request)
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
		if( $parameters->has( 'legalEntityId' ) )
			$legalEntityId = $parameters->get('legalEntityId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : legalEntityId to modify needed' ), 400 );

		if( $parameters->has( 'serviceId' ) )
			$serviceId = $parameters->get('serviceId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : serviceId to modify needed' ), 400 );

		// Get back entities to make a new link
		$legalEntity = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')
        		->find($legalEntityId);
		if( $legalEntity == null )
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
			$services = $legalEntity->getServices();

		if( $services == null )
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

        $em = $this->getDoctrine()->getManager();
		foreach( $services as $service )
			if( $legalEntity->getServices()->contains( $service ) ){
				// We have two entities, make a link between
				$legalEntity->removeService( $service );
				$service->removeLegalEntity( $legalEntity );
				$em->persist( $legalEntity );
				$em->persist( $service );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Right removed successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

}