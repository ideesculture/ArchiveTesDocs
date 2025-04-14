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
use bs\IDP\BackofficeBundle\Entity\IDPBudgetCodes;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class ManageDBBudgetCodesJsonController extends Controller
{

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}


	// =========================================================================
	// -- MANAGE DB -- BUDGET CODES --

	public function budgetcodeslistAction( Request $request ){
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

		// Ask databse for total budget codes
		$totalBudgetCodes = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPBudgetCodes' )->countBudgetCodes( $search );
		// Ask database for legal entities range
		$budgetCodes = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPBudgetCodes' )->loadBudgetCodesDatas( $search, $sort, $order, $limit, $offset );

		$response_data = array();

		foreach( $budgetCodes as $budgetCode ){

			$cansuppress = true;
			$archive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive')
				->findOneBy( array( 'budgetcode' => $budgetCode->getId() ) );
			if( $archive ) $cansuppress = false;

			$line = array(
				'id' => $budgetCode->getId(),
				'longname' => $budgetCode->getLongname(),
				'cansuppress' => $cansuppress
			);
			array_push( $response_data, $line );
		}

		$return = array( 'total' => intval( $totalBudgetCodes ), 'rows' => $response_data );

		return $this->jsonResponse( $return );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function budgetcodesdeleteAction( Request $request ){
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

		$budgetCode = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')
			->find($id);

		if($budgetCode == null){
			return $this->jsonResponse( array( 'message' => 'System Error: Budget Code Id does not exist !'), 400 );
		}
		else{
			$em = $this->getDoctrine()->getManager();
			$em->remove($budgetCode);
			$em->flush();

			return $this->jsonResponse( array('success' => true) );
		}

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function budgetcodesaddAction( Request $request ){
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

		$budgetCode = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPBudgetCodes' )
			->findBy( array( 'longname' => $name ) );

		if( $budgetCode != null ){
			return $this->jsonResponse( array( 'message' => "Le Code Budgétaire $name existe déjà !"), 400 );
		}

		$budgetCode = new IDPBudgetCodes();
		$budgetCode->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($budgetCode);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function budgetcodesModifyAction( Request $request ){
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

		$budgetCode = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPBudgetCodes' )
			->find( $id );

		if( $budgetCode == null ){
			return $this->jsonResponse( array( 'message' => "System Error: Budget Code Id does not exist !"), 400 );
		}

		$budgetCode->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($budgetCode);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function budgetcodeslinkslistAction(Request $request)
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

		if( $parameters->has( 'budgetCodeId' ) )
			$budgetCodeId = $parameters->get('budgetCodeId');
		else
			return $this->jsonResponse( array('message' => 'System Error : budgetCodeId to delete needed'), 400 );

		$budgetCode = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')
        		->find($budgetCodeId);

		if($budgetCode == null)
			return $this->jsonResponse( array('message' => 'System Error'), 417 );
		else{
			// Retreive linked table objects
			$services = $budgetCode->getServices();
			$return = [];

			foreach( $services as $service ){
				array_push( $return, array( 'serviceID' => $service->getId() ));
			}

			return $this->jsonResponse( $return );
		}
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function budgetcodeslinkssetAction(Request $request)
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
		if( $parameters->has( 'budgetCodeId' ) )
			$budgetCodeId = $parameters->get('budgetCodeId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : budgetCodeId to modify needed' ), 400 );

		if( $parameters->has( 'serviceId' ) )
			$serviceId = $parameters->get('serviceId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : serviceId to modify needed' ), 400 );

		// Get back entities to make a new link
		$budgetCode = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')
        		->find($budgetCodeId);

		if($budgetCode == null)
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
			if( !$budgetCode->getServices()->contains( $service ) ){
				// We have two entities, make a link between
				$em = $this->getDoctrine()->getManager();
				$budgetCode->addService( $service );
				$service->addBudgetCode( $budgetCode );
				$em->persist( $budgetCode );
				$em->persist( $service );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Service added successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function budgetcodeslinksunsetAction(Request $request)
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
		if( $parameters->has( 'budgetCodeId' ) )
			$budgetCodeId = $parameters->get('budgetCodeId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : budgetCodeId to modify needed' ), 400 );

		if( $parameters->has( 'serviceId' ) )
			$serviceId = $parameters->get('serviceId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : serviceId to modify needed' ), 400 );

		// Get back entities to make a new link
		$budgetCode = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')
        		->find($budgetCodeId);
		if( $budgetCode == null )
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
			$services = $budgetCode->getServices();

		if( $services == null )
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

        $em = $this->getDoctrine()->getManager();
		foreach( $services as $service )
			if( $budgetCode->getServices()->contains( $service ) ){
				// We have two entities, make a link between
				$budgetCode->removeService( $service );
				$service->removeBudgetCode( $budgetCode );
				$em->persist( $budgetCode );
				$em->persist( $service );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Right removed successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

}