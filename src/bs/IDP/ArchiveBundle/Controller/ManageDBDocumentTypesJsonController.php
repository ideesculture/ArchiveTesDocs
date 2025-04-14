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

use bs\IDP\BackofficeBundle\Entity\IDPDocumentNatures;
use bs\IDP\BackofficeBundle\Entity\IDPDocumentTypes;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class ManageDBDocumentTypesJsonController extends Controller
{

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}


	// =========================================================================
	// -- MANAGE DB -- DOCUMENT TYPES --

	public function documenttypeslistAction( Request $request ){
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
			$order = 'asc';        if( $parameters->has( 'search' ) )
            $search = $parameters->get('search');
        else
            $search = null;


        // Ask databse for total document types
		$totalDocumentTypes = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDocumentTypes' )->countDocumentTypes($search);
		// Ask database for document types range
		$documentTypes = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDocumentTypes' )->loadDocumentTypesDatas( $search, $sort, $order, $limit, $offset );

		$response_data = array();

		foreach( $documentTypes as $documentType ){

			$cansuppress = true;
			$archive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive')
				->findOneBy( array( 'documenttype' => $documentType->getId() ) );
			if( $archive ) $cansuppress = false;

			$line = array(
				'id' => $documentType->getId(),
				'longname' => $documentType->getLongname(),
				'keepaliveduration' => $documentType->getKeepaliveduration(),
				'cansuppress' => $cansuppress
			);
			array_push( $response_data, $line );
		}

		$return = array( 'total' => intval( $totalDocumentTypes ), 'rows' => $response_data );

		return $this->jsonResponse( $return );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function documenttypesdeleteAction( Request $request ){
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

		$documentType = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')
			->find($id);

		if($documentType == null){
			return $this->jsonResponse( array( 'message' => 'System Error: Document Type Id does not exist !'), 417 );
		}
		else{
			$em = $this->getDoctrine()->getManager();
			$em->remove($documentType);
			$em->flush();

			return $this->jsonResponse( array('success' => true) );
		}

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function documenttypesaddAction( Request $request ){
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
		$duration = intval( $parameters->get('duration') );

		$documentType = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPDocumentTypes' )
			->findBy( array( 'longname' => $name ) );

		if( $documentType != null ){
			return $this->jsonResponse( array( 'message' => "Le Type de document $name existe déjà !"), 417 );
		}

		$documentType = new IDPDocumentTypes();
		$documentType->setLongname( $name );
		$documentType->setKeepaliveduration( $duration );

		$em = $this->getDoctrine()->getManager();
		$em->persist($documentType);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function documenttypesModifyAction( Request $request ){
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
		$duration = intval( $parameters->get('duration'));

		$documentType = $this->getDoctrine()
			->getRepository( 'bsIDPBackofficeBundle:IDPDocumentTypes' )
			->find( $id );

		if( $documentType == null ){
			return $this->jsonResponse( array( 'message' => "System Error: Document Type Id does not exist !"), 417 );
		}

		$documentType->setLongname( $name );
		$documentType->setKeepaliveduration( $duration );

		$em = $this->getDoctrine()->getManager();
		$em->persist($documentType);
		$em->flush();

		return $this->jsonResponse( array( 'success', true ) );

		//		}else{
		//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
		//			return new Response($content, 419);
		//		}
	}

	public function documenttypeslinkslistAction(Request $request)
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

		if( $parameters->has( 'documentTypeId' ) )
			$documentTypeId = $parameters->get('documentTypeId');
		else
			return $this->jsonResponse( array('message' => 'System Error : documentTypeId to delete needed'), 400 );

		$documentType = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')
        		->find($documentTypeId);

		if($documentType == null)
			return $this->jsonResponse( array('message' => 'System Error'), 417 );
		else{
			// Retreive linked table objects
			$documentNatures = $documentType->getDocumentNatures();
			$return = [];

			foreach( $documentNatures as $documentNature ){
				array_push( $return, array( 'documentNatureID' => $documentNature->getId() ));
			}

			return $this->jsonResponse( $return );
		}
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function documenttypeslinkssetAction(Request $request)
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
		if( $parameters->has( 'documentTypeId' ) )
			$documentTypeId = $parameters->get('documentTypeId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : documentTypeId to modify needed' ), 400 );

		if( $parameters->has( 'documentNatureId' ) )
			$documentNatureId = $parameters->get('documentNatureId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : documentNatureId to modify needed' ), 400 );

		// Get back entities to make a new link
		$documentType = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')
        		->find($documentTypeId);

		if($documentType == null)
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

		// If $documentNatureId is -1 this means set all document natures, so in both case we make a collection of what to set
		if( $documentNatureId != -1 ){
			$documentNatures = new \Doctrine\Common\Collections\ArrayCollection();
			$documentNature = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
				->find($documentNatureId);
			$documentNatures[] = $documentNature;
		}
		else
			$documentNatures = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
				->findAll( );

		if($documentNatures == null)
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

		foreach( $documentNatures as $documentNature )
			if( !$documentType->getDocumentNatures()->contains( $documentNature ) ){
				// We have two entities, make a link between
				$em = $this->getDoctrine()->getManager();
				$documentType->addDocumentNature( $documentNature );
				$documentNature->addDocumentType( $documentType );
				$em->persist( $documentType );
				$em->persist( $documentNature );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Document Nature added successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

	public function documenttypeslinksunsetAction(Request $request)
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
		if( $parameters->has( 'documentTypeId' ) )
			$documentTypeId = $parameters->get('documentTypeId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : documentTypeId to modify needed' ), 400 );

		if( $parameters->has( 'documentNatureId' ) )
			$documentNatureId = $parameters->get('documentNatureId');
		else
			return $this->jsonResponse( array( 'message' => 'System Error : documentNatureId to modify needed' ), 400 );

		// Get back entities to make a new link
		$documentType = $this->getDoctrine()
        		->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')
        		->find($documentTypeId);
		if( $documentType == null )
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

		// If $serviceId is -1 this means unset all services, so in both case we make a collection of what to unset
		if( $documentNatureId != -1 ){
			$documentNatures = new \Doctrine\Common\Collections\ArrayCollection();
			$documentNature = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
				->find($documentNatureId);
			$documentNatures[] = $documentNature;
		}
		else
			$documentNatures = $documentType->getDocumentNatures();

		if( $documentNatures == null )
			return $this->jsonResponse( array( 'message' => 'System Error' ), 417 );

        $em = $this->getDoctrine()->getManager();
		foreach( $documentNatures as $documentNature )
			if( $documentType->getDocumentNatures()->contains( $documentNature ) ){
				// We have two entities, make a link between
				$documentType->removeDocumentNature( $documentNature );
				$documentNature->removeDocumentType( $documentType );
				$em->persist( $documentType );
				$em->persist( $documentNature );
				$em->flush();
			}

		return $this->jsonResponse( array( 'message' => 'Document Nature removed successfully' ));
		//		} else
		//			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );
	}

}