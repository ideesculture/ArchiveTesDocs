<?php

namespace bs\IDP\ArchiveBundle\Controller;

use bs\IDP\BackofficeBundle\Entity\IDPServiceSettings;
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
use bs\Core\UsersBundle\Entity\IDPUserServices;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class ManageDBServicesJsonController extends Controller
{

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	// =========================================================================
	// -- MANAGE DB -- SERVICES --

	public function serviceslistAction( Request $request ){
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

		// Ask databse for total services
		$totalServices = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPServices' )->countServices($search);
		// Ask database for services range
		$services = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPServices' )->loadServicesDatas( $search, $sort, $order, $limit, $offset );

		$response_data = array();

		foreach( $services as $service ){

			$cansuppress = true;
			if( !$service->getLegalEntities()->isEmpty() ||
				!$service->getDocumentNatures()->isEmpty() ||
				!$service->getDescriptions1()->isEmpty() ||
				!$service->getDescriptions2()->isEmpty() ||
				!$service->getBudgetCodes()->isEmpty() ||
				!$service->getProviders()->isEmpty() )
				$cansuppress = false;
			if( $cansuppress ){
				$archive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive')
					->findOneBy( array( 'service' => $service->getId() ) );
				if( $archive ) $cansuppress = false;
			}

			$line = array(
				'id' => $service->getId(),
				'longname' => $service->getLongname(),
				'cansuppress' => $cansuppress
			);
			array_push( $response_data, $line );
		}

		if( $limit > 0 )
			$return = array( 'total' => intval( $totalServices ), 'rows' => $response_data );
		else
			$return = $response_data;

		return $this->jsonResponse( $return );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function servicesdeleteAction( Request $request ){
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

		$service = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServices')
			->find($id);

		if($service == null){
			return $this->jsonResponse( array( 'message' => 'System Error: Service Id does not exist !'), 417 );
		}
		else{
            // Get settings of for this service
            $settings = $this->getDoctrine()
                ->getRepository( 'bsIDPBackofficeBundle:IDPServiceSettings' )
                ->findOneBy( array( 'service_id' => $service->getId() ));

            $em = $this->getDoctrine()->getManager();
            if( $settings ){
                $em->remove( $settings );
                $em->flush();
            } else {
                return $this->jsonResponse( array( 'message' => 'Error while deleting dependencies'), 417 );
            }

			$em->remove($service);
			$em->flush();


			return $this->jsonResponse( array('success' => true) );
		}

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function servicesaddAction( Request $request ){
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

		$service = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPServices' )
			->findBy( array( 'longname' => $name ) );

		if( $service != null ){
			return $this->jsonResponse( array( 'message' => "Le service $name existe deja !"), 417 );
		}

		$service = new IDPServices();
		$service->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($service);
		$em->flush();

        // Copy default settings for this service
        $settings = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPServiceSettings' )
            ->findOneBy( array( 'service_id' => 0 ) );
        if( $settings ){
            $newSettings = clone $settings;
            $newSettings->setServiceid( $service->getId() );
            $em->persist( $newSettings );
            $em->flush();
        } else {
            $em->remove( $service );
            $em->flush();
            return $this->jsonResponse(array('message' => "Error while creating dependencies"), 417);
        }

        // Affect this service to all admin & super-admin (role <= 25)
        $usersToAffect = $this->getDoctrine()
            ->getRepository( 'bsCoreUsersBundle:bsUsers')
            ->findByRoleLimit( 25 );
        foreach( $usersToAffect as $user ){
            $userService = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserServices')->findOneBy( array( 'user' => $user->getId(), 'service' => $service->getId() ));
            if( !$userService ) { // Add only if doesn't already exists
                $userService = new IDPUserServices();
                $userService->setUser($user);
                $userService->setService($service);

                $em->persist($userService);
                $em->flush();
            }
        }

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function servicesModifyAction( Request $request ){
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

		$service = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPServices' )
			->find( $id );

		if( $service == null ){
			return $this->jsonResponse( array( 'message' => "System Error: Service Id does not exist !"), 417 );
		}

		$service->setLongname( $name );

		$em = $this->getDoctrine()->getManager();
		$em->persist($service);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

}