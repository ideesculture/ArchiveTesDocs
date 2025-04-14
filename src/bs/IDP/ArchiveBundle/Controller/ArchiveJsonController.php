<?php
// (c) Archimage - IDP Consulting 2016
// Author: Cyril PATRIGEON - BeSecure Labs
//

namespace bs\IDP\ArchiveBundle\Controller;

use \DateTime;
use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;
use bs\IDP\ArchiveBundle\Entity\IDPDeletedArchive;
use bs\IDP\ArchiveBundle\Entity\IDPAudit;

use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

// Controller to achieve Json request
class ArchiveJsonController extends Controller
{
	const UAFIELD_SERVICE 			= 0;
	const UAFIELD_LEGALENTITY 		= 1;
	const UAFIELD_BUDGETCODE 		= 2;
	const UAFIELD_DOCUMENTNATURE	= 3;
	const UAFIELD_DOCUMENTTYPE		= 4;
	const UAFIELD_DESCRIPTION1		= 5;
	const UAFIELD_DESCRIPTION2		= 6;
	const UAFIELD_CLOSUREYEAR		= 7;
	const UAFIELD_DESTRUCTIONYEAR	= 8;
	const UAFIELD_DOCUMENTNUMBER	= 9;
	const UAFIELD_BOXNUMBER			=10;
	const UAFIELD_CONTAINERNUMBER	=11;
	const UAFIELD_PROVIDER			=12;
	const UAFIELD_NAME				=13;
	const UAFIELD_LIMITDATEMIN		=14;
	const UAFIELD_LIMITDATEMAX		=15;
	const UAFIELD_LIMITNUMMIN		=16;
	const UAFIELD_LIMITNUMMAX		=17;
	const UAFIELD_LIMITALPHAMIN		=18;
	const UAFIELD_LIMITALPHAMAX		=19;
	const UAFIELD_LIMITALPHANUMMIN	=20;
	const UAFIELD_LIMITALPHANUMMAX	=21;
    
    const ORDER_LAST_MODIFIED = 1;
    const ORDER_SERVICE_ORDERNB = 2; 

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	/**
	 * Send all datas of linked lists, with same structure id, name, foreign table id, in a json format
	 */
	public function forminitlistsAction(Request $request)
	{
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() ){
			$content = json_encode(array('message' => 'System Error : User not logged'));
			return new Response($content, 403);
		}

        // Reconciliation in progress (critical phases)
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

			$return = [];

			$userServices = $bsUserSession->getUserServices();

			$allowedServices = [];
			$allowedLegalEntities = [];
			$allowedDocumentNatures = [];
			$allowedDocumentTypes = [];
			$allowedDescription1 = [];
			$allowedDescription2 = [];
			$allowedBudgetCodes = [];
			$allowedProviders = [];

			foreach( $userServices as $userService )
				array_push( $allowedServices, $userService->getService()->getId() );

			// Must only restrict services, all other will be linked to that restricted choice

			// Retreive linked table objects
			$services = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPServices')
				->findall();
			$legalentities = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')
				->findall();
			$documentnatures = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
				->findall();
			$documenttypes = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')
				->findall();
			$documentdescription1 = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')
				->findall();
			$documentdescription2 = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPDescriptions2')
				->findall();
			$documentbudgetcodes = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')
				->findall();
			$providers = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPProviders')
				->findall();
            $localizations = $this->getDoctrine()
                ->getRepository('bsIDPBackofficeBundle:IDPLocalizations')
                ->findall();

			$serviceList = [];
			foreach( $services as $service ){
				if( in_array( $service->getId(), $allowedServices ) ){
					$service_legalentities = [];
					foreach( $service->getLegalEntities() as $service_legalentity ){
						array_push( $service_legalentities, $service_legalentity->getId() );
						array_push( $allowedLegalEntities, $service_legalentity->getId() );
					}

					$service_budgetcodes = [];
					foreach( $service->getBudgetCodes() as $service_budgetcode ){
						array_push( $service_budgetcodes, $service_budgetcode->getId() );
						array_push( $allowedBudgetCodes, $service_budgetcode->getId() );
					}

					$service_descriptions1 = [];
					foreach( $service->getDescriptions1() as $service_description1 ){
						array_push( $service_descriptions1, $service_description1->getId() );
						array_push( $allowedDescription1, $service_description1->getId() );
					}

					$service_descriptions2 = [];
					foreach( $service->getDescriptions2() as $service_description2 ){
						array_push( $service_descriptions2, $service_description2->getId() );
						array_push( $allowedDescription2, $service_description2->getId() );
					}

					$service_providers = [];
					foreach( $service->getProviders() as $service_provider ){
						array_push( $service_providers, $service_provider->getId() );
						array_push( $allowedProviders, $service_provider->getId() );
					}

					$service_documentnatures = [];
					foreach( $service->getDocumentNatures() as $service_documentnature){
						array_push( $service_documentnatures, $service_documentnature->getId() );
						array_push( $allowedDocumentNatures, $service_documentnature->getId() );
					}

                    array_push( $serviceList, array( $service->getId(), $service->getLongname(), $service_legalentities,
                        $service_budgetcodes, $service_descriptions1, $service_descriptions2, $service_providers,
                        $service_documentnatures ));
				}
			}
			array_push( $return, $serviceList );

			$legalentityList = [];
			foreach( $legalentities as $legalentity )
				if( in_array( $legalentity->getId(), $allowedLegalEntities ) )
					array_push( $legalentityList, array( $legalentity->getId(), $legalentity->getLongname() ));
			array_push( $return, $legalentityList );

			$documentnatureList = [];
			foreach( $documentnatures as $documentnature ) {
				if( in_array( $documentnature->getId(), $allowedDocumentNatures ) ) {
					$documentnature_documenttypes = [];
					foreach( $documentnature->getDocumentTypes() as $documentnature_documenttype ){
						array_push( $documentnature_documenttypes, $documentnature_documenttype->getId() );
						array_push( $allowedDocumentTypes, $documentnature_documenttype->getId() );
					}
					array_push( $documentnatureList, array( $documentnature->getId(), $documentnature->getLongname(), $documentnature_documenttypes ));
				}
			}
			array_push( $return, $documentnatureList );

			$documenttypeList = [];
			foreach( $documenttypes as $documenttype )
				if( in_array( $documenttype->getId(), $allowedDocumentTypes ) )
					array_push( $documenttypeList, array( $documenttype->getId(), $documenttype->getLongname(), $documenttype->getKeepaliveduration() ));
			array_push( $return, $documenttypeList );

			$description1List = [];
			foreach( $documentdescription1 as $description1 )
				if( in_array( $description1->getId(), $allowedDescription1 ) )
					array_push( $description1List, array( $description1->getId(), $description1->getLongname() ));
			array_push( $return, $description1List );

			$description2List = [];
			foreach( $documentdescription2 as $description2 )
				if( in_array( $description2->getId(), $allowedDescription2 ) )
					array_push( $description2List, array( $description2->getId(), $description2->getLongname() ));
			array_push( $return, $description2List );

			$budgetcodeList = [];
			foreach( $documentbudgetcodes as $budgetcode )
				if( in_array( $budgetcode->getId(), $allowedBudgetCodes ) )
					array_push( $budgetcodeList, array( $budgetcode->getId(), $budgetcode->getLongname() ));
			array_push( $return, $budgetcodeList  );

			$providerList = [];
			foreach( $providers as $provider )
				if( in_array( $provider->getId(), $allowedProviders ) )
					array_push( $providerList, array( $provider->getId(), $provider->getLongname(), ($provider->getLocalization()?$provider->getLocalization()->getId():-1)));
			array_push( $return, $providerList  );

            $localizationsList = [];
            foreach( $localizations as $localization )
                array_push( $localizationsList, array( $localization->getId(), $localization->getLongname() ));
            array_push( $return, $localizationsList  );

			$response = new Response(json_encode($return));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
/*
		}else{
			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
			return new Response($content, 419);
		}
*/
	}

	public function updatefieldAction( Request $request )
	{
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() )
			return $this->jsonResponse( array('message' => 'System Error : User not logged') , 403);

        // Reconciliation in progress (critical phases)
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        if($request->isXmlHttpRequest()) {

			// GET
			 $parameters = $request->query;
			// POST
			// $parameters = $request->request;

			$uaid = $parameters->get('uaid');
			$uafield = $parameters->get('uafield');
			$value = $parameters->get('value');

			$idparchive = $this->getDoctrine()
				->getRepository('bsIDPArchiveBundle:IDPArchive')
				->find($uaid);

			if( !$idparchive )
				return $this->jsonResponse( array('message' => 'System Error : archive id error') , 419);

		switch( $uafield ){
			case self::UAFIELD_SERVICE:
				if( $value > 0 ){
					$service = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPServices')->find($value);
					if( !$service )
						return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
				} else $service = null;
				$idparchive->setService( $service );
				$idparchive->setLegalentity( null );
				$idparchive->setBudgetcode( null );
				$idparchive->setDocumentnature( null );
				$idparchive->setDocumenttype( null );
				$idparchive->setDescription1( null );
				$idparchive->setDescription2( null );
				$idparchive->setProvider( null );
				break;
			case self::UAFIELD_LEGALENTITY:
				if( $value > 0 ){
					$legalentity = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')->find($value);
					if( !$legalentity )
						return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
				} else $legalentity = null;
				$idparchive->setLegalentity( $legalentity );
				break;
			case self::UAFIELD_BUDGETCODE:
				if( $value > 0 ){
					$budgetcode = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')->find($value);
					if( !$budgetcode )
						return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
				} else $budgetcode = null;
				$idparchive->setBudgetcode( $budgetcode );
				break;
			case self::UAFIELD_DOCUMENTNATURE:
				if( $value > 0 ){
					$documentnature = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')->find($value);
					if( !$documentnature )
						return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
				} else $documentnature = null;
				$idparchive->setDocumentnature( $documentnature );
				$idparchive->setDocumenttype( null );
				break;
			case self::UAFIELD_DOCUMENTTYPE:
				if( $value > 0 ){
					$documenttype = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')->find($value);
					if( !$documenttype )
						return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
				} else $documenttype = null;
				$idparchive->setDocumenttype( $documenttype );
				break;
			case self::UAFIELD_DESCRIPTION1:
				if( $value > 0 ){
					$description1 = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')->find($value);
					if( !$description1 )
						return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
				} else $description1 = null;
				$idparchive->setDescription1( $description1 );
				break;
			case self::UAFIELD_DESCRIPTION2:
				if( $value > 0 ){
					$description2 = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDescriptions2')->find($value);
					if( !$description2 )
						return $this->jsonResponse( array('message' => 'System Error : value error') , 419);
				} else $description2 = null;
				$idparchive->setDescription2( $description2 );
				break;
			case self::UAFIELD_CLOSUREYEAR:
				$idparchive->setClosureyear( $value );
				break;
			case self::UAFIELD_DESTRUCTIONYEAR:
				$idparchive->setDestructionyear( $value );
				break;
            case self::UAFIELD_DOCUMENTNUMBER:
                if( $value && strlen( trim($value) )!=0 )
                    $idparchive->setDocumentnumber( trim($value) );
                else
                    $idparchive->setDocumentnumber( null );
                break;
            case self::UAFIELD_BOXNUMBER:
                if( $value && strlen( trim($value) )!=0 )
                    $idparchive->setBoxnumber( trim($value) );
                else
                    $idparchive->setBoxnumber( null );
                break;
            case self::UAFIELD_CONTAINERNUMBER:
                if( $value && strlen( trim($value) )!=0 )
                    $idparchive->setContainernumber( trim($value) );
                else
                    $idparchive->setContainernumber( null );
                break;
			case self::UAFIELD_PROVIDER:
				if( $value > 0 ){
					$provider = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPProviders')->find($value);
					if( !$provider )
						return $this->jsonResponse( array('message' => 'System Error : value error'), 419);
				} else $provider = null;
				$idparchive->setProvider( $provider );
				break;
			case self::UAFIELD_NAME:
				$idparchive->setName( $value );
				break;
			case self::UAFIELD_LIMITDATEMIN:
                if( empty($value) )
                    $idparchive->setLimitdatemin( null );
                else
                    $idparchive->setLimitdatemin( DateTime::createFromFormat( 'j/m/Y', $value ));
				break;
			case self::UAFIELD_LIMITDATEMAX:
                if( empty($value) )
                    $idparchive->setLimitdatemax( null );
                else
                    $idparchive->setLimitdatemax( DateTime::createFromFormat( 'j/m/Y', $value ));
				break;
			case self::UAFIELD_LIMITNUMMIN:
				$idparchive->setLimitnummin( $value );
				break;
			case self::UAFIELD_LIMITNUMMAX:
				$idparchive->setLimitnummax( $value );
				break;
			case self::UAFIELD_LIMITALPHAMIN:
				$idparchive->setLimitalphamin( $value );
				break;
			case self::UAFIELD_LIMITALPHAMAX:
				$idparchive->setLimitalphamax( $value );
				break;
			case self::UAFIELD_LIMITALPHANUMMIN:
				$idparchive->setLimitalphanummin( $value );
				break;
			case self::UAFIELD_LIMITALPHANUMMAX:
				$idparchive->setLimitalphanummax( $value );
				break;

		}

		$em = $this->getDoctrine()->getManager();
		$em->persist( $idparchive );
		$em->flush();

		return $this->jsonResponse( array( 'message' => 'Success' ), 200 );

		}else{
			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
			return new Response($content, 419);
		}
	}


	public function deleteAction(Request $request)
	{
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() ){
			$content = json_encode(array('message' => 'System Error : User not logged'));
			return new Response($content, 403);
		}

        // Reconciliation in progress (critical phases)
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        if($request->isXmlHttpRequest()) {

			// GET
			// $ids = $request->query->get('id');
			// POST
			 $ids = $request->request->get('id');

            $idArr = explode( '|', $ids );

            $audit = [];

            foreach( $idArr as $id ){

                $archive = $this->getDoctrine()
                    ->getRepository('bsIDPArchiveBundle:IDPArchive')
                    ->find($id);

                if($archive == null){
                    $return = array('success' => false);
                } else {
                    $em = $this->getDoctrine()->getManager();

                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_DELETE,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_NA,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $archive->getId(),
                        'new_str' => null,
                        'new_int' => null,
                        'old_str' => null,
                        'old_int' => $archive->getStatus()->getId(),
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];

                    $em->remove($archive);

                    $return = array('success' => true);
                }
            }
            $em->flush();

            $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );

			$response = new Response(json_encode($return));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}else{
			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
			return new Response($content, 419);
		}
	}

    // bs_idp_archive_search_ajax:       /ajax/search
	public function searchAction(Request $request)
	{
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ? $this->container->get('logger') : null;

		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() ){
			$content = json_encode(array('message' => 'System Error : User not logged'));
			return new Response($content, 403);
		}

        // Reconciliation in progress (critical phases)
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $devMode = $this->container->getParameter('kernel.environment') == 'dev';

        $MAX_COUNT = 500;

		// Authorized From depends on Function
        // callFrom = 0
        $authorizedTransfer = ['DTA'];
		// callFrom = 1 (Deliver), 4 (Delete), 3 (Exit)
		$authorizedDeliver = [ 'DISI', 'DISINT', 'DISP' ]; // also for Delete and Exit
		// callFrom = 2 (Return)
		$authorizedReturn = [ 'CONI', 'CONINT', 'CONP', 'CONRIDISP', 'CONRINTDISP', 'CONRICONP', 'CONRINTCONP' ];
        // callFrom = 28 (Reloc)
        $authorizedReloc = ['DISI', 'DISINT', 'DISP', 'CONI', 'CONINT', 'CONP'];
        // callFrom = 35 (Unlimited)
        $forbiddenUnlimited = [ 'DTA', 'NAV', 'CSDI', 'CSAI', 'ESDI', 'CDAI', 'CDEI', 'EDEI', 'CSAINT', 'CSAP', 'CDAINT',
            'CDAP', 'CDEINT', 'CDEP', 'CSDINT', 'CSDP', 'ESDINT', 'ESDP', 'EDEINT', 'EDEP', 'GSAP' ];

		// Symfony2 doesn't include STR_TO_DATE and STRCMP in Doctrine, just add it
		$this->getDoctrine()->getEntityManager()->getConfiguration()->addCustomStringFunction('STR_TO_DATE', 'bs\IDP\ArchiveBundle\DQL\StrToDate');
		$this->getDoctrine()->getEntityManager()->getConfiguration()->addCustomStringFunction('STRCMP', 'bs\IDP\ArchiveBundle\DQL\Strcmp');

//		if($request->isXmlHttpRequest()) {

			// GET
			$parameters = $request->query;
			// POST
			// $parameters = $request->request

			$callFrom = $parameters->get('callFrom');
			if( strlen($callFrom)<=0 ) $callFrom = null;

			$service = $parameters->get('service');
			if (strlen($service)<=0) $service = null;
			$legalentity = $parameters->get('legalentity');
			if (strlen($legalentity)<=0) $legalentity = null;
			$description1 = $parameters->get('description1');
			if (strlen($description1)<=0) $description1 = null;
			$description2 = $parameters->get('description2');
			if (strlen($description2)<=0) $description2 = null;
			$name = $parameters->get('name');
			if (strlen($name)<=0) $name = null;
			$limitnum = $parameters->get('limitnum');
			if (strlen($limitnum)<=0) $limitnum = null;
			$limitalpha = $parameters->get('limitalpha');
			if (strlen($limitalpha)<=0) $limitalpha = null;
			$limitalphanum = $parameters->get('limitalphanum');
			if (strlen($limitalphanum)<=0) $limitalphanum = null;
			$limitdate = $parameters->get('limitdate');
			if (strlen($limitdate)<=0) $limitdate = null;
			$ordernumber = $parameters->get('ordernumber');
			if (strlen($ordernumber)<=0) $ordernumber = null;
			$budgetcode = $parameters->get('budgetcode');
			if (strlen($budgetcode)<=0) $budgetcode = null;
			$documentnature = $parameters->get('documentnature');
			if (strlen($documentnature)<=0) $documentnature = null;
			$documenttype = $parameters->get('documenttype');
			if (strlen($documenttype)<=0) $documenttype = null;
			$closureyear = $parameters->get('closureyear');
			if (strlen($closureyear)<=0) $closureyear = null;
			$destructionyear = $parameters->get('destructionyear');
			if (strlen($destructionyear)<=0) $destructionyear = null;
			$documentnumber = $parameters->get('documentnumber');
			if (strlen($documentnumber)<=0) $documentnumber = null;
			$boxnumber = $parameters->get('boxnumber');
			if (strlen($boxnumber)<=0) $boxnumber = null;
			$containernumber = $parameters->get('containernumber');
			if (strlen($containernumber)<=0) $containernumber = null;
			$provider = $parameters->get('provider');
			if (strlen($provider)<=0) $provider = null;
            if( $parameters->has('filterstatus'))
                $filterstatus = $parameters->get('filterstatus');
            else
                $filterstatus = 0;
            if( $parameters->has('filterwhere') )
                $filterwhere = $parameters->get('filterwhere');
            else
                $filterwhere = 0;
            if( $parameters->has('filterwith'))
                $filterwith = $parameters->get('filterwith');
            else
                $filterwith = 0;
            if( $parameters->has('filterlocalization')) {
                $filterlocalization = $parameters->get('filterlocalization');
                //$filterlocalizations = explode(',', $filterlocalization);
            }
            else
                $filterlocalization = null;
            $special = $parameters->get('special');
            if( strlen($special)<=0) $special=null;
            $unlimited = $parameters->get('unlimited');
            if( strlen($unlimited)<=0 ) $unlimited = 2;

            $pageOffset = 0;
            if( $parameters->has( 'pageOffset' ))
                $pageOffset = intval( $parameters->get( 'pageOffset' ));
            if( $pageOffset != 0 ) $pageOffset--;

            $pageSize = 10;
            if( $parameters->has( 'pageSize' ))
                $pageSize = intval( $parameters->get('pageSize' ));
            if( $pageSize < 10 ) $pageSize = 10;
            if( $pageSize > 100 ) $pageSize = 100;

            $offset = $pageOffset * $pageSize;

            $sortAsc = 'ASC';
            if( $parameters->has( 'sortAsc' ))
                if( strtoupper($parameters->get( 'sortAsc' )) == 'DESC' )
                    $sortAsc = 'DESC';

            $sortColumn = 'id';
            if( $parameters->has( 'sortColumn' ))
                $sortColumn = $parameters->get( 'sortColumn' );

/*
        if( $logger ) $logger->info( "Service: ".$service );
        if( $logger ) $logger->info( "LegelEntity: ".$legalentity );
        if( $logger ) $logger->info( "Description1: ".$description1 );
        if( $logger ) $logger->info( "Description2: ".$description2 );
        if( $logger ) $logger->info( "Name: ".$name );
        if( $logger ) $logger->info( "Limit Num: ".$limitnum );
        if( $logger ) $logger->info( "Limit Alpha: ".$limitalpha );
        if( $logger ) $logger->info( "Limit AlphaNum: ".$limitalphanum );
        if( $logger ) $logger->info( "Limit Date: ".$limitdate );
        if( $logger ) $logger->info( "Order Number: ".$ordernumber );
        if( $logger ) $logger->info( "Budget Code: ".$budgetcode );
        if( $logger ) $logger->info( "Document Nature: ".$documentnature );
        if( $logger ) $logger->info( "Document Type: ".$documenttype );
        if( $logger ) $logger->info( "Closure year: ".$closureyear);
        if( $logger ) $logger->info( "Destruction year: ".$destructionyear );
        if( $logger ) $logger->info( "Document Number: ".$documentnumber );
        if( $logger ) $logger->info( "Box Number: ".$boxnumber );
        if( $logger ) $logger->info( "Container Number: ".$containernumber );

        if( $logger ) $logger->info( "Provider: ".$provider );
        if( $logger ) $logger->info( "Filter Status: ".$filterstatus );
        if( $logger ) $logger->info( "Filter Where: ".$filterwhere );
        if( $logger ) $logger->info( "Filter With: ".$filterwith );
        if( $logger ) $logger->info( "Filter Localization: ".$filterlocalization );
        if( $logger ) $logger->info( "Special: ".$special );
        if( $logger ) $logger->info( "Unlimited: ".$unlimited );

        if( $logger ) $logger->info( "Page size: ".$pageSize );
        if( $logger ) $logger->info( "Offset: ".$offset );
*/

			$queryBase = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
				->getSearchQuery( $bsUserSession->getUserServices(), $service, $legalentity,
					$description1, $description2, $name, $limitnum, $limitalpha, $limitalphanum,
					$limitdate, $ordernumber, $budgetcode, $documentnature,
					$documenttype, $closureyear, $destructionyear, $documentnumber, $boxnumber,
					$containernumber, $provider, $filterstatus, $filterwhere, $filterwith,
                    $filterlocalization, $special, $unlimited );

			$queryCount = 'SELECT COUNT(a.id) FROM bsIDPArchiveBundle:IDPArchive a ' . $queryBase;
			$querySelect = 'SELECT a FROM bsIDPArchiveBundle:IDPArchive a ' . $queryBase . ' ORDER BY a.'.$sortColumn.' ' . $sortAsc;

            if( $logger ) $logger->info( $querySelect );

            $query = $this->getDoctrine()->getManager()->createQuery( $queryCount );
			$count = $query->getSingleScalarResult();

			$query = $this->getDoctrine()->getManager()
				->createQuery( $querySelect )
				->setHint( \Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS, true )
				->setMaxResults( $pageSize )
                ->setFirstResult( $offset );

			$idpArchives = $query->getArrayResult();

			$idpDTAArchiveList = array();
			if( $idpArchives ){
				// Retreive linked table objects
				$serviceNames = $this->getDoctrine()
					->getRepository('bsIDPBackofficeBundle:IDPServices')
					->getAllIndexedOnID();
				$legalentities = $this->getDoctrine()
					->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')
					->getAllIndexedOnID();
				$documentnatures = $this->getDoctrine()
					->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
					->getAllIndexedOnID();
				$documenttypes = $this->getDoctrine()
					->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')
					->getAllIndexedOnID();
				$documentdescription1 = $this->getDoctrine()
					->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')
					->getAllIndexedOnID();
				$documentdescription2 = $this->getDoctrine()
					->getRepository('bsIDPBackofficeBundle:IDPDescriptions2')
					->getAllIndexedOnID();
				$documentbudgetcodes = $this->getDoctrine()
					->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')
					->getAllIndexedOnID();
				$providers = $this->getDoctrine()
					->getRepository('bsIDPBackofficeBundle:IDPProviders')
					->getAllIndexedOnID();
				$status = $this->getDoctrine()
					->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')
					->getAllIndexedOnID();
				$localizations = $this->getDoctrine()
                    ->getRepository('bsIDPBackofficeBundle:IDPLocalizations')
                    ->getAllIndexedOnID();
				$deliveraddresses = $this->getDoctrine()
                    ->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')
                    ->getAllIndexedOnID();

				// Parse each one to make JSON array
				foreach( $idpArchives as $dtaElem )
				{
					$service_id = array_key_exists('service_id',$dtaElem)?$dtaElem['service_id']:'-1';
					$legalentity_id = array_key_exists('legalentity_id',$dtaElem)?$dtaElem['legalentity_id']:'-1';
					$budgetcode_id = array_key_exists('budgetcode_id',$dtaElem)?$dtaElem['budgetcode_id']:'-1';
					$documentnature_id = array_key_exists('documentnature_id',$dtaElem)?$dtaElem['documentnature_id']:'-1';
					$documenttype_id = array_key_exists('documenttype_id',$dtaElem)?$dtaElem['documenttype_id']:'-1';
					$description1_id = array_key_exists('description1_id',$dtaElem)?$dtaElem['description1_id']:'-1';
					$description2_id = array_key_exists('description2_id',$dtaElem)?$dtaElem['description2_id']:'-1';
					$provider_id = array_key_exists('provider_id',$dtaElem)?$dtaElem['provider_id']:'-1';

					$adminid = '';
					$adminid .= $service_id . ',' . $legalentity_id . ',' . $budgetcode_id;
					$adminid .= ',' . $documentnature_id . ',' . $documenttype_id;
					$adminid .= ',' . $description1_id . ',' . $description2_id;
					$adminid .= ',' . $provider_id;

					$elemLongStatus = array_key_exists('status_id',$dtaElem)?$status[intval($dtaElem['status_id'])]['longname']:'';
					$elemShortStatus = array_key_exists('status_id',$dtaElem)?$status[intval($dtaElem['status_id'])]['shortname']:'';
					if( $callFrom == 0 )    // DTA
					    $authorized = in_array( $elemShortStatus, $authorizedTransfer );
					else
                        if( $callFrom == 2 )
                            $authorized = in_array( $elemShortStatus, $authorizedReturn );
                        else                                                                                                // v0.4.0 : Modification of test to manage authorized value of Reloc
                            if( $callFrom == 28 )
                                $authorized = in_array( $elemShortStatus, $authorizedReloc );
                            else
                                if( $callFrom == 35 )
                                    $authorized = !in_array( $elemShortStatus, $forbiddenUnlimited );
                                else
                                    if( $callFrom == 4 && $dtaElem['unlimited']==1 )        // Cannot delete unlimited archives
                                        $authorized = false;
                                    else
                                        $authorized = in_array( $elemShortStatus, $authorizedDeliver );

                    $locked = ( $dtaElem['locked'] != null ) ;

					$dtaLine = array(
						'id' => empty($dtaElem['id'])?'-':$dtaElem['id'],
						'name' => empty($dtaElem['name'])?'-':$dtaElem['name'],
						'status' => $elemLongStatus,
                        'statuscaps' => $elemShortStatus,                                                               // v0.4.0 : add sent of CAPS status
						'authorized' => $authorized,
						'locked' => $locked,
						'ordernumber' => empty($dtaElem['ordernumber'])?'-':$dtaElem['ordernumber'],
						'closureyear' => empty($dtaElem['closureyear'])?'-':$dtaElem['closureyear'],
						'destructionyear' => empty($dtaElem['destructionyear'])?'-':$dtaElem['destructionyear'],
						'createdat' => empty($dtaElem['createdat'])?'-':$dtaElem['createdat']->format('d/m/Y h:m:s'),
						'modifiedat' => empty($dtaElem['modifiedat'])?'-':$dtaElem['modifiedat']->format('d/m/Y h:m:s'),
						'limitnummin' => empty($dtaElem['limitnummin'])?'-':$dtaElem['limitnummin'],
						'limitnummax' => empty($dtaElem['limitnummax'])?'-':$dtaElem['limitnummax'],
						'limitdatemin' => empty($dtaElem['limitdatemin'])?'-':$dtaElem['limitdatemin']->format('d/m/Y'),
						'limitdatemax' => empty($dtaElem['limitdatemax'])?'-':$dtaElem['limitdatemax']->format('d/m/Y'),
						'limitalphamin' => empty($dtaElem['limitalphamin'])?'-':$dtaElem['limitalphamin'],
						'limitalphamax' => empty($dtaElem['limitalphamax'])?'-':$dtaElem['limitalphamax'],
						'limitalphanummin' => empty($dtaElem['limitalphanummin'])?'-':$dtaElem['limitalphanummin'],
						'limitalphanummax' => empty($dtaElem['limitalphanummax'])?'-':$dtaElem['limitalphanummax'],
						'service' => array_key_exists('service_id',$dtaElem)?
                            $serviceNames?array_key_exists($dtaElem['service_id'],$serviceNames)?$serviceNames[intval($dtaElem['service_id'])]['longname']:'-':'-':'-',
						'legalentity' => array_key_exists('legalentity_id',$dtaElem)?
                            $legalentities?array_key_exists(intval($dtaElem['legalentity_id']), $legalentities)?$legalentities[intval($dtaElem['legalentity_id'])]['longname']:'-':'-':'-',
						'documentnature' => array_key_exists('documentnature_id',$dtaElem)?
                            $documentnatures?array_key_exists(intval($dtaElem['documentnature_id']), $documentnatures)?$documentnatures[intval($dtaElem['documentnature_id'])]['longname']:'-':'-':'-',
						'documenttype' => array_key_exists('documenttype_id',$dtaElem)?
                            $documenttypes?array_key_exists(intval($dtaElem['documenttype_id']), $documenttypes)?$documenttypes[intval($dtaElem['documenttype_id'])]['longname']:'-':'-':'-',
						'description1' => array_key_exists('description1_id',$dtaElem)?
                            $documentdescription1?array_key_exists(intval($dtaElem['description1_id']), $documentdescription1)?$documentdescription1[intval($dtaElem['description1_id'])]['longname']:'-':'-':'-',
						'description2' => array_key_exists('description2_id',$dtaElem)?
                            $documentdescription2?array_key_exists(intval($dtaElem['description2_id']), $documentdescription2)?$documentdescription2[intval($dtaElem['description2_id'])]['longname']:'-':'-':'-',
						'budgetcode' => array_key_exists('budgetcode_id',$dtaElem)?
                            $documentbudgetcodes?array_key_exists(intval($dtaElem['budgetcode_id']), $documentbudgetcodes)?$documentbudgetcodes[intval($dtaElem['budgetcode_id'])]['longname']:'-':'-':'-',
						'documentnumber' => empty($dtaElem['documentnumber'])?'-':$dtaElem['documentnumber'],
						'boxnumber' => empty($dtaElem['boxnumber'])?'-':$dtaElem['boxnumber'],
						'containernumber' => empty($dtaElem['containernumber'])?'-':$dtaElem['containernumber'],
						'provider' => array_key_exists('provider_id',$dtaElem)?
                            $providers?array_key_exists(intval($dtaElem['provider_id']), $providers)?$providers[intval($dtaElem['provider_id'])]['longname']:'-':'-':'-',
                        'localizationfree' => array_key_exists('localizationfree',$dtaElem)?$dtaElem['localizationfree']:'-',
						'localization' => array_key_exists( 'localization_id', $dtaElem )?
                            $localizations?array_key_exists(intval($dtaElem['localization_id']), $localizations)?$localizations[intval($dtaElem['localization_id'])]['longname']:'-':'-':'-',
                        'oldlocalizationfree' => array_key_exists('oldlocalizationfree',$dtaElem)?$dtaElem['oldlocalizationfree']:'-',
                        'oldlocalization' => array_key_exists( 'oldlocalization_id', $dtaElem )?
                            $localizations?array_key_exists(intval($dtaElem['oldlocalization_id']), $localizations)?$localizations[intval($dtaElem['oldlocalization_id'])]['longname']:'-':'-':'-',
						'precisiondate' => is_null($dtaElem['precisiondate'])?'-':$dtaElem['precisiondate']->format('d/m/Y'),
						'precisionaddress' => array_key_exists('precisionaddress_id',$dtaElem)?
                            $deliveraddresses?array_key_exists(intval($dtaElem['precisionaddress_id']), $deliveraddresses)?$deliveraddresses[intval($dtaElem['precisionaddress_id'])]['longname']:'-':'-':'-',
						'precisionfloor' => empty($dtaElem['precisionfloor'])?'-':$dtaElem['precisionfloor'],
						'precisionoffice' => empty($dtaElem['precisionoffice'])?'-':$dtaElem['precisionoffice'],
						'precisionwho' => empty($dtaElem['precisionwho'])?'-':$dtaElem['precisionwho'],
						'precisioncomment' => empty($dtaElem['precisioncomment'])?'-':$dtaElem['precisioncomment'],
                        'unlimited' => empty($dtaElem['unlimited'])?'inactif':($dtaElem['unlimited']==1?'actif':'inactif'),
                        'unlimitedcomments' => empty($dtaElem['unlimitedcomments'])?'-':$dtaElem['unlimitedcomments'],
						'adminidlist' => $adminid
					);

					array_push( $idpDTAArchiveList, $dtaLine );
				}
			}

			$response = new Response(json_encode( [ "total" => $count, "rows" => $idpDTAArchiveList ] ));
			$response->headers->set('Content-Type', 'application/json');


/*			if( $count > $MAX_COUNT )
				$response->setStatusCode( 206, "Too many results!");
*/
			return $response;
//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}
	}

	public function modifyAction( Request $request )
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged()) {
            $content = json_encode(array('message' => 'System Error : User not logged'));
            return new Response($content, 403);
        }

        // Reconciliation in progress (critical phases)
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;

        $id = $parameters->get('id');
        $ordernumber = $parameters->get('ordernumber');
        $serviceid = $parameters->get('service');
        $legalentityid = $parameters->get('legalentity');
        $budgetcodeid = $parameters->get('budgetcode');
        $documentnatureid = $parameters->get('documentnature');
        $documenttypeid = $parameters->get('documenttype');
        $description1id = $parameters->get('description1');
        $description2id = $parameters->get('description2');
        $providerid = $parameters->get('provider');
        $closureyear = $parameters->get('closureyear');
        $destructionyear = $parameters->get('destructionyear');
        $documentnumber = $parameters->get('documentnumber');
        $boxnumber = $parameters->get('boxnumber');
        $containernumber = $parameters->get('containernumber');
        $name = $parameters->get('name');
        $limitdatemin = $parameters->get('limitdatemin');
        $limitdatemax = $parameters->get('limitdatemax');
        $limitnummin = $parameters->get('limitnummin');
        $limitnummax = $parameters->get('limitnummax');
        $limitalphamin = $parameters->get('limitalphamin');
        $limitalphamax = $parameters->get('limitalphamax');
        $limitalphanummin = $parameters->get('limitalphanummin');
        $limitalphanummax = $parameters->get('limitalphanummax');
        $localizationid = $parameters->get('localization');
        $localizationfree = $parameters->get('localizationfree');
        $unlimited = $parameters->get('unlimited');
        $commentsunlimited = $parameters->get('commentsunlimited');

        // Retreive archive with if
        $archive = $this->getDoctrine()
            ->getRepository('bsIDPArchiveBundle:IDPArchive')
            ->find($id);

        if (!$archive) {
            $content = json_encode(array('message' => 'System Error : this archive doesn\'t exist'));
            return new Response($content, 419);
        }

        // Retreive all "linked" entities
        $service = (intval($serviceid) < 0) ? null : $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServices')
            ->find(intval($serviceid));
        $budgetcode = (intval($budgetcodeid) < 0) ? null : $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')
            ->find(intval($budgetcodeid));
        $legalentity = (intval($legalentityid) < 0) ? null : $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')
            ->find(intval($legalentityid));
        $documentnature = (intval($documentnatureid) < 0) ? null : $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
            ->find(intval($documentnatureid));
        $documenttype = (intval($documenttypeid) < 0) ? null : $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')
            ->find(intval($documenttypeid));
        $description1 = (intval($description1id) < 0) ? null : $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')
            ->find(intval($description1id));
        $description2 = (intval($description2id) < 0) ? null : $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPDescriptions2')
            ->find(intval($description2id));
        $provider = (intval($providerid) < 0) ? null : $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPProviders')
            ->find(intval($providerid));
        $localization = (intval($localizationid) < 0) ? null : $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPLocalizations')
            ->find(intval($localizationid));

        //----------------------------------------------------------
        // Audit & update
        $audit = [];
        if ($service && $service != $archive->getService()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_SERVICE,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => $service->getId(),
                'old_str' => null,
                'old_int' => $archive->getService() === null ? null : $archive->getService()->getId(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setService($service);
        }

        if ($ordernumber != $archive->getOrdernumber()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_ORDERNUMBER,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => (strlen($ordernumber) > 0) ? $ordernumber : null,
                'new_int' => null,
                'old_str' => $archive->getOrdernumber(),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setOrdernumber((strlen($ordernumber) > 0) ? $ordernumber : null);
        }

        if ($legalentity && $legalentity != $archive->getLegalentity()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LEGALENTITY,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => $legalentity->getId(),
                'old_str' => null,
                'old_int' => $archive->getLegalentity() === null ? null : $archive->getLegalentity()->getId(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLegalentity($legalentity);
        }

        if ($budgetcode && $budgetcode != $archive->getBudgetcode()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_BUDGETCODE,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => $budgetcode->getId(),
                'old_str' => null,
                'old_int' => $archive->getBudgetcode() === null ? null : $archive->getBudgetcode()->getId(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setBudgetcode($budgetcode);
        }

        if ($documentnature && $documentnature != $archive->getDocumentnature()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_DOCUMENTNATURE,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => $documentnature->getId(),
                'old_str' => null,
                'old_int' => $archive->getDocumentnature() === null ? null : $archive->getDocumentnature()->getId(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setDocumentnature($documentnature);
        }

        if ($documenttype && $documenttype != $archive->getDocumenttype()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_DOCUMENTTYPE,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => $documenttype->getId(),
                'old_str' => null,
                'old_int' => $archive->getDocumenttype() === null ? null : $archive->getDocumenttype()->getId(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setDocumenttype($documenttype);
        }

        if ($description1 && $description1 != $archive->getDescription1()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_DESCRIPTION1,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => $description1->getId(),
                'old_str' => null,
                'old_int' => $archive->getDescription1() === null ? null : $archive->getDescription1()->getId(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setDescription1($description1);
        }

        if ($description2 && $description2 != $archive->getDescription2()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_DESCRIPTION2,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => $description2->getId(),
                'old_str' => null,
                'old_int' => $archive->getDescription2() === null ? null : $archive->getDescription2()->getId(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setDescription2($description2);
        }

        if ($provider && $provider != $archive->getProvider()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_PROVIDER,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => $provider->getId(),
                'old_str' => null,
                'old_int' => $archive->getProvider() === null ? null : $archive->getProvider()->getId(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setProvider($provider);
        }

        if (intval($closureyear) != $archive->getClosureyear()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_CLOSUREYEAR,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => strlen($closureyear) ? intval($closureyear) : null,
                'old_str' => null,
                'old_int' => $archive->getClosureyear(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setClosureyear(strlen($closureyear) ? intval($closureyear) : null);
        }

        if (intval($destructionyear) != $archive->getDestructionyear()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_DESTRUCTIONYEAR,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => strlen($destructionyear) ? intval($destructionyear) : null,
                'old_str' => null,
                'old_int' => $archive->getDestructionyear(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setDestructionyear(strlen($destructionyear) ? intval($destructionyear) : null);
        }

        if ($documentnumber != $archive->getDocumentnumber()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_DOCUMENTNUMBER,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => strlen($documentnumber) ? $documentnumber : null,
                'new_int' => null,
                'old_str' => $archive->getDocumentnumber(),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setDocumentnumber(strlen($documentnumber) ? $documentnumber : null);
        }

        if ($boxnumber != $archive->getBoxnumber()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_BOXNUMBER,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => strlen($boxnumber) ? $boxnumber : null,
                'new_int' => null,
                'old_str' => $archive->getBoxnumber(),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setBoxnumber(strlen($boxnumber) ? $boxnumber : null);
        }

        if ($containernumber != $archive->getContainernumber()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_CONTAINERNUMBER,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => strlen($containernumber) ? $containernumber : null,
                'new_int' => null,
                'old_str' => $archive->getContainernumber(),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setContainernumber(strlen($containernumber) ? $containernumber : null);
        }

        if ($name != $archive->getName()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_NAME,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => $name,
                'new_int' => null,
                'old_str' => $archive->getName(),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setName($name);
        }

        $tempDate = DateTime::createFromFormat('d/m/Y', $limitdatemin);
        if ($tempDate != $archive->getLimitdatemin()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LIMITDATEMIN,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => $tempDate === false ? null : $limitdatemin,
                'new_int' => null,
                'old_str' => $archive->getLimitdatemin() === null ? null : $archive->getLimitdatemin()->format('d/m/Y'),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLimitdatemin($tempDate === false ? null : $tempDate);
        }

        $tempDate = DateTime::createFromFormat('d/m/Y', $limitdatemax);
        if ($tempDate != $archive->getLimitdatemax()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LIMITDATEMAX,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => $tempDate === false ? null : $limitdatemax,
                'new_int' => null,
                'old_str' => $archive->getLimitdatemax() === null ? null : $archive->getLimitdatemax()->format('d/m/Y'),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLimitdatemax($tempDate === false ? null : $tempDate);
        }

        if ($limitnummin != $archive->getLimitnummin()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LIMITNUMMIN,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => strlen($limitnummin) ? intval($limitnummin) : null,
                'old_str' => null,
                'old_int' => $archive->getLimitnummin(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLimitnummin(strlen($limitnummin) ? intval($limitnummin) : null);
        }

        if ($limitnummax != $archive->getLimitnummax()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LIMITNUMMAX,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => strlen($limitnummax) ? intval($limitnummax) : null,
                'old_str' => null,
                'old_int' => $archive->getLimitnummax(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLimitnummax(strlen($limitnummax) ? intval($limitnummax) : null);
        }

        if ($limitalphamin != $archive->getLimitalphamin()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LIMITALPHAMIN,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => strlen($limitalphamin) ? $limitalphamin : null,
                'new_int' => null,
                'old_str' => $archive->getLimitalphamin(),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLimitalphamin(strlen($limitalphamin) ? $limitalphamin : null);
        }

        if ($limitalphamax != $archive->getLimitalphamax()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LIMITALPHAMAX,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => strlen($limitalphamax) ? $limitalphamax : null,
                'new_int' => null,
                'old_str' => $archive->getLimitalphamax(),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLimitalphamax(strlen($limitalphamax) ? $limitalphamax : null);
        }

        if ($limitalphanummin != $archive->getLimitalphanummin()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LIMITALPHANUMMIN,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => strlen($limitalphanummin) ? $limitalphanummin : null,
                'new_int' => null,
                'old_str' => $archive->getLimitalphanummin(),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLimitalphanummin(strlen($limitalphanummin) ? $limitalphanummin : null);
        }

        if ($limitalphanummax != $archive->getLimitalphanummax()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LIMITALPHANUMMAX,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => strlen($limitalphanummax) ? $limitalphanummax : null,
                'new_int' => null,
                'old_str' => $archive->getLimitalphanummax(),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLimitalphanummax(strlen($limitalphanummax) ? $limitalphanummax : null);
        }

        if ($localization && $localization != $archive->getLocalization()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATION,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'new_int' => $localization->getId(),
                'old_str' => null,
                'old_int' => $archive->getLocalization() === null ? null : $archive->getLocalization()->getId(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLocalization($localization);
        }

        if ($localizationfree != $archive->getLocalizationfree()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => strlen($localizationfree) ? $localizationfree : null,
                'new_int' => null,
                'old_str' => $archive->getLocalizationfree(),
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setLocalizationfree(strlen($localizationfree) ? $localizationfree : null);
        }

        if ($unlimited != $archive->getUnlimited()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_UNLIMITED,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => null,
                'old_str' => null,
                'new_int' => $unlimited,
                'old_int' => $archive->getUnlimited(),
                'complete_ua_id' => null,
                'object_type' => null
            ];
           $archive->setUnlimited($unlimited);
            if (!$unlimited) {
                $commentsunlimited = null;
            }
        }
        if ($commentsunlimited != $archive->getUnlimitedcomments()) {
            $audit[] = [
                'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                'user_id' => $bsUserSession->getUserId(),
                'field' => IDPConstants::FIELD_ARCHIVE_UNLIMITEDCOMMENTS,
                'entity' => IDPConstants::ENTITY_ARCHIVE,
                'entity_id' => $archive->getId(),
                'new_str' => $commentsunlimited,
                'old_str' => $archive->getUnlimitedcomments(),
                'new_int' => null,
                'old_int' => null,
                'complete_ua_id' => null,
                'object_type' => null
            ];
            $archive->setUnlimitedcomments($commentsunlimited);
        }

        $archive->setModifiedat( new DateTime() );

			$em = $this->getDoctrine()->getManager();
			$em->persist($archive);
			$em->flush();

            $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );

			$response = new Response(json_encode(array('message' => 'Success '.$nbAudit)));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}
	}

    // ..........................................................................................................
    // This function returns the detailled informations needed for the Dashboard when the user clicks on the "Current Operation" table
    //
    public function detailledinformationAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            $content = json_encode(array('message' => 'System Error : User not logged'));
            return new Response($content, 403);
        }

        // Reconciliation in progress (critical phases)
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $userRoles = $bsUserSession->getUserRoles();
        $isArchivist = false;
        foreach( $userRoles as $role ){
            if( $role->getScale() < 100 )
                $isArchivist = true;
        }
        $userServices = null;
        $userId = null;
        if( $bsUserSession->hasRole( 'USER' ) ) {
            // User is a simple user
            $userId = $bsUserSession->getUserId();
        } else {
            // User is an admin or an archivist
            $userServices = $bsUserSession->getUserServices();
        }


//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;
        if( $parameters->has( 'which' ) )
            $which = $parameters->get( 'which' );
        else
            $which = 0;

        if( ( $which < 0 ) || ( $which > 5 ) ) {
            $content = json_encode(array('message' => 'System Error : Parameter value is not allowed !'));
            return new Response($content, 400);
        }

        $archives = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
            ->getArchivesWithStatuses( IDPConstants::$COUNT_STATUS[$which], $userId, false, $isArchivist, $userServices );

        $response = new Response( json_encode( $archives ) );
        $response->headers->set( 'Content-Type', 'application/json' );

        return $response;
//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}
    }


    public function shortenString( $string ){
		if (strlen( $string ) > 50 ) {
			return substr( $string, 0, 50 ) + '...';
		}
		return $string;
	}


}
