<?php

namespace bs\IDP\ArchiveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;
use bs\IDP\ArchiveBundle\Form\Type\IDPArchiveType;
use bs\IDP\ArchiveBundle\Form\Type\IDPCustTransferType;
use bs\IDP\DashboardBundle\Entity\bsMessages;

use bs\IDP\BackofficeBundle\Entity\IDPServices;
use bs\IDP\BackofficeBundle\Entity\IDPProviders;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class ManageDBProvidersJsonController extends Controller
{

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}


	// =========================================================================
	// -- MANAGE DB -- PROVIDERS --

	public function providerslistAction( Request $request ){
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

		// Ask databse for total providers
		$totalProviders = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPProviders' )->countProviders($search);
		// Ask database for providers range
		$providers = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPProviders' )->loadProvidersDatas( $search,$sort, $order, $limit, $offset );

		$response_data = array();

		foreach( $providers as $provider ){

			$cansuppress = true;
			$archive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive')
				->findOneBy( array( 'provider' => $provider->getId() ) );
			if( $archive ) $cansuppress = false;

			$line = array(
				'id' => $provider->getId(),
				'longname' => $provider->getLongname(),
                'localization' => ($provider->getLocalization()?$provider->getLocalization()->getLongname():'-'),
				'cansuppress' => $cansuppress
			);
			array_push( $response_data, $line );
		}

		$return = array( 'total' => intval( $totalProviders ), 'rows' => $response_data );

		return $this->jsonResponse( $return );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function providersdeleteAction( Request $request ){
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

		$provider = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPProviders')
			->find($id);

		if($provider == null){
			return $this->jsonResponse( array( 'message' => 'System Error: Provider Id does not exist !'), 417 );
		}
		else{
            $em = $this->getDoctrine()->getManager();
			$em->remove($provider);
			$em->flush();

			return $this->jsonResponse( array('success' => true) );
		}

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function providersaddAction( Request $request ){
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

        $localization_id = $parameters->get('localization_id');

		$provider = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPProviders' )
			->findOneBy( array( 'longname' => $name ) );

		if( $provider != null ){
			return $this->jsonResponse( array( 'message' => "Le Prestataire $name existe déjà !"), 417 );
		}

        $localization = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPLocalizations' )
            ->findOneBy( array( 'id' => $localization_id ) );

        /*
        if( $localization == null ){
            return $this->jsonResponse( array( 'message' => "La localisation spécifiée n'existe pas !" ), 419 );
        }
        */

		$provider = new IDPProviders();
		$provider->setLongname( $name );
        $provider->setLocalization( $localization );

		$em = $this->getDoctrine()->getManager();
		$em->persist($provider);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function providersModifyAction( Request $request ){
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
        $localization_id = $parameters->get('localization_id');

		$provider = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPProviders' )
			->find( $id );

		if( $provider == null ){
			return $this->jsonResponse( array( 'message' => "System Error: Provider Id does not exist !"), 417 );
		}

        $localization = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPLocalizations' )
            ->find( $localization_id );

        if( $localization == null ){
            return $this->jsonResponse( array( 'message' => 'System Error: Localization Id does not exist !'), 417 );
        }

		$provider->setLongname( $name );
        $provider->setLocalization( $localization );

		$em = $this->getDoctrine()->getManager();
		$em->persist($provider);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function providerslinkslistAction(Request $request)
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

		if( $parameters->has( 'providerId' ) )
			$providerId = $parameters->get('providerId');
		else
			return $this->jsonResponse( array('message' => 'System Error : providerId to delete needed'), 400 );

		$provider = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPProviders')
        		->find($providerId);

		if($provider == null)
			return $this->jsonResponse( array('message' => 'System Error'), 417 );
		else{
			// Retreive linked table objects
			$services = $provider->getServices();
			$return = [];

			foreach( $services as $service ){
				array_push( $return, array( 'serviceID' => $service->getId() ));
			}

			return $this->jsonResponse( $return );
		}
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function providerslinkssetAction(Request $request)
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
		if( $parameters->has( 'providerId' ) )
			$providerId = $parameters->get('providerId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : providerId to modify needed' ), 400 );

		if( $parameters->has( 'serviceId' ) )
			$serviceId = $parameters->get('serviceId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : serviceId to modify needed' ), 400 );

		// Get back entities to make a new link
		$provider = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPProviders')
        		->find($providerId);

		if($provider == null)
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
			if( !$provider->getServices()->contains( $service ) ){
				// We have two entities, make a link between
				$em = $this->getDoctrine()->getManager();
				$provider->addService( $service );
				$service->addProvider( $provider );
				$em->persist( $provider );
				$em->persist( $service );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Service added successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function providerslinksunsetAction(Request $request)
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
		if( $parameters->has( 'providerId' ) )
			$providerId = $parameters->get('providerId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : providerId to modify needed' ), 400 );

		if( $parameters->has( 'serviceId' ) )
			$serviceId = $parameters->get('serviceId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : serviceId to modify needed' ), 400 );

		// Get back entities to make a new link
		$provider = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPProviders')
        		->find($providerId);
		if( $provider == null )
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
            return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );
			// $services = $budgetCode->getServices();

		if( $services == null )
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

        $em = $this->getDoctrine()->getManager();
		foreach( $services as $service )
			if( $provider->getServices()->contains( $service ) ){
				// We have two entities, make a link between
				$provider->removeService( $service );
				$service->removeProvider( $provider );
				$em->persist( $provider );
				$em->persist( $service );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Right removed successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}


}