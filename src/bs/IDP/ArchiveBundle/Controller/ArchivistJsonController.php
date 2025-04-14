<?php

// Global Error Response Code
// E-XYYZZ where
// X => Type of problem (0: User / 1: Parameters presence / 2: Parameters validity / 3: Intermediate Action
// YY: => Sequence of problem in section (ie incremental number)
// ZZ: => Function called (ie route)

namespace bs\IDP\ArchiveBundle\Controller;

use bs\IDP\ArchiveBundle\Common\IDPManageContainerBox;
use bs\IDP\ArchiveBundle\Entity\IDPAuditCompleteUa;
use bs\IDP\BackofficeBundle\Entity\IDPProviderConnectorBackup;
use \DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPDeletedArchive;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;
use bs\IDP\ArchiveBundle\Form\Type\IDPArchiveType;
use bs\IDP\ArchiveBundle\Form\Type\IDPCustTransferType;
use bs\IDP\DashboardBundle\Entity\bsMessages;

use bs\IDP\BackofficeBundle\Entity\IDPServices;
use bs\IDP\BackofficeBundle\Entity\IDPLegalEntities;

use bs\IDP\ArchiveBundle\Entity\IDPAudit;

use bs\IDP\BackofficeBundle\Entity\IDPTempOpti;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;


define ("DELETE_STATUS", serialize( array(
    'ESDI', 'ESDINT', 'ESDP', 'EDEI', 'EDEINT', 'EDEP'
)));

define ("CANCEL_STATUS", serialize( array(
    // UASTATE = Manage User Wants
	'DTRI' => 'DTA', 'DTRINT' => 'DTA', 'DTRP' => 'DTA',
	'CLAI' => 'DISI', 'CPAI' => 'DISI', 'CLAINT' => 'DISINT', 'CPAINT' => 'DISINT', 'CLAP' => 'DISP', 'CPAP' => 'DISP',
	'CRAI' => 'CONI', 'CRAINT' => 'CONINT', 'CRAP' => 'CONP',
	'CRAPCONRIDISP' => 'CONRIDISP', 'CRAPCONRINTDISP' => 'CONRINTDISP', 'CRAPCONRICONP' => 'CONRICONP', 'CRAPCONRINTCONP' => 'CONRINTCONP',
	'CSAI' => 'DISI', 'CSAINT' => 'DISINT', 'CSAP' => 'DISP',
	'CDAI' => 'DISI', 'CDAINT' => 'DISINT', 'CDAP' => 'DISP',
    'CRLIDAINT' => 'DISI', 'CRLIDAP' => 'DISI', 'CRLIDAI' => 'DISI',
    'CRLINTDAI' => 'DISINT', 'CRLINTDAP' => 'DISINT', 'CRLINTDAINT' => 'DISINT',
    'CRLPDAI' => 'DISP', 'CRLPDAINT' => 'DISP',
    'CRLICAI' => 'CONI', 'CRLICAINT' => 'CONI',
    'CRLINTCAI' => 'CONINT', 'CRLINTCAINT' => 'CONINT',
    'CRLPCAI' => 'CONP', 'CRLPCAINT' => 'CONP',

    // UASTATE = Manage provider
    'GDTRP' => 'DTRP',
    'GDAP' => 'CDAP',
    'GLAP' => 'CLAP', 'GPAP' => 'CPAP',
    'GSAP' => 'CSAP',
    'GRAP' => 'CRAP',
    'GRAPCONRIDISP' => 'CRAPCONRIDISP', 'GRAPCONRINTDISP' => 'CRAPCONRINTDISP', 'GRAPCONRICONP' => 'CRAPCONRICONP', 'GRAPCONRINTCONP' => 'CRAPCONRINTCONP',
    'GRLIDAP' => 'CRLIDAP', 'GRLINTDAP' => 'CRLINTDAP',
    'GRLPDAI' => 'CRLPDAI', 'GRLPDAINT' => 'CRLPDAINT',

    // UASTATE = Close user wants
    'CDTRI' => 'DTRI', 'CDTRINT' => 'DTRINT', 'CDTRP' => 'GDTRP',
    'CDEI' => 'CDAI', 'CDEINT' => 'CDAINT', 'CDEP' => 'GDAP',
    'CLII' => 'CLAI', 'CPRI' => 'CPAI', 'CLIINT' => 'CLAINT', 'CPRINT' => 'CPAINT', 'CLIP' => 'GLAP', 'CPRP' => 'GPAP',
    'CSDI' => 'CSAI', 'CSDINT' => 'CSAINT', 'CSDP' => 'GSAP',
    'CRTI' => 'CRAI', 'CRTINT' => 'CRAINT', 'CRTP' => 'GRAP',
    'CRLIDINT' => 'CRLIDAINT', 'CRLIDI' => 'CRLIDAI', 'CRLIDP' => 'GRLIDAP',
    'CRLINTDI' => 'CRLINTDAI', 'CRLINTDP' => 'GRLINTDAP', 'CRLINTDINT' => 'CRLINTDAINT',
    'CRLPDI' => 'GRLPDAI', 'CRLPDINT' => 'GRLPDAINT',
    'CRLICINT' => 'CRLICAINT', 'CRLICI' => 'GRLICAI',
    'CRLINTCI' => 'CRLINTCAI', 'CRLINTCINT' => 'GRLINTCAINT',
    'CRLPCI' => 'CRLPCAI', 'CRLPCINT' => 'CRLPCAINT',
    'CRTPCONRIDISP' => 'GRAPCONRIDISP', 'CRTPCONRINTDISP' => 'GRAPCONRINTDISP', 'CRTPCONRICONP' => 'GRAPCONRICONP', 'CRTPCONRINTCONP' => 'GRAPCONRINTCONP'
)));

define ("ACTION_STATUS", serialize( array(
    array (
    // UASTATE = Manage User Wants
        // Transfert
        array(  'DTRI' => 'CDTRI', 'DTRINT' => 'CDTRINT', 'DTRP' => 'GDTRP' ) ,
	    // Livrer = Consulter
	    array( 'CLAI' => 'CLII', 'CPAI' => 'CPRI', 'CLAINT' => 'CLIINT', 'CPAINT' => 'CPRINT', 'CLAP' => 'GLAP', 'CPAP' => 'GPAP' ),
	    // Retour
	    array( 'CRAI' => 'CRTI', 'CRAINT' => 'CRTINT', 'CRAP' => 'GRAP',
            'CRAPCONRIDISP' => 'GRAPCONRIDISP', 'CRAPCONRINTDISP' => 'GRAPCONRINTDISP', 'CRAPCONRICONP' => 'GRAPCONRICONP', 'CRAPCONRINTCONP' => 'GRAPCONRINTCONP' ),
	    // Sortir
	    array( 'CSAI' => 'CSDI', 'CSAINT' => 'CSDINT', 'CSAP' => 'GSAP' ),
	    // Destruction
	    array( 'CDAI' => 'CDEI', 'CDAINT' => 'CDEINT', 'CDAP' => 'GDAP' ),
        // Relocalisation
        array( 'CRLIDAINT' => 'CRLIDINT', 'CRLIDAP' => 'GRLIDAP', 'CRLIDAI' => 'CRLIDI',
            'CRLINTDAI' => 'CRLINTDI', 'CRLINTDAP' => 'GRLINTDAP', 'CRLINTDAINT' => 'CRLINTDINT',
            'CRLPDAI' => 'GRLPDAI', 'CRLPDAINT' => 'GRLPDAINT',
            'CRLICAINT' => 'CRLICINT', 'CRLICAI' => 'CRLICI',
            'CRLINTCAI' => 'CRLINTCI', 'CRLINTCAINT' => 'CRLINTCINT',
            'CRLPCAI' => 'CRLPCI', 'CRLPCAINT' => 'CRLPCINT' ) ),
    array(
    // UASTATE = Manage provider
        // Transfert
        array( 'GDTRP' => 'CDTRP' ),
        // Livrer
        array( 'GLAP' => 'CLIP', 'GPAP' => 'CPRP',
            'DISP' => 'CLIP', 'CLAP' => 'CLIP', 'CPAP' => 'CPRP'  ), // For optimization
        // Retour
        array( 'GRAP' => 'CRTP', 'GRAPCONRIDISP' => 'CRTPCONRIDISP', 'GRAPCONRINTDISP' => 'CRTPCONRINTDISP', 'GRAPCONRICONP' => 'CRTPCONRICONP', 'GRAPCONRINTCONP' => 'CRTPCONRINTCONP' ),
        // Sortir
        array( 'GSAP' => 'CSDP' ),
        // Détruire
        array( 'GDAP' => 'CDEP',
            'DISP' => 'CDEP', 'CDAP' => 'CDEP'  ),    // For optimisation
        // Relocalisation
        array( 'GRLIDAP' => 'CRLIDP', 'GRLINTDAP' => 'CRLINTDP',
            'GRLPDAI' => 'CRLPDI', 'GRLPDAINT' => 'CRLPDINT',
            'DISP' => 'CRLPDI', 'CRLPDAI' => 'CRLPDI', 'CRLPDAINT' => 'CRLPDINT'       // For optimisation
        ) ),
    array(
    // UASTATE = Close user wants
        // Transfert
        array( 'CDTRI' => 'DISI', 'CDTRINT' => 'DISINT', 'CDTRP' => 'DISP' ),
        // Livraison
        array( 'CLII' => 'CONI', 'CPRI' => 'CONI', 'CLIINT' => 'CONINT', 'CPRINT' => 'CONINT', 'CLIP' => 'CONP', 'CPRP' => 'CONP' ),
        // Retour
        array( 'CRTI' => 'DISI', 'CRTINT' => 'DISINT', 'CRTP' => 'DISP', 'CRTPCONRIDISP' => 'DISP', 'CRTPCONRINTDISP' => 'DISP', 'CRTPCONRICONP' => 'DISP', 'CRTPCONRINTCONP' => 'DISP'),
        // Sortie
        array( 'CSDI' => 'ESDI', 'CSDINT' => 'ESDINT', 'CSDP' => 'ESDP' ),
        // Destruction
        array( 'CDEI' => 'EDEI', 'CDEINT' => 'EDEINT', 'CDEP' => 'EDEP' ),
        // Relocalisation
        array( 'CRLIDINT' => 'DISINT', 'CRLIDP' => 'DISP', 'CRLIDI' => 'DISI',
            'CRLINTDI' => 'DISI', 'CRLINTDP' => 'DISP', 'CRLINTDINT' => 'DISINT',
            'CRLPDI' => 'CONRIDISP', 'CRLPDINT' => 'CONRINTDISP',
            'CRLICINT' => 'CONINT', 'CRLICI' => 'CONI',
            'CRLINTCI' => 'CONI', 'CRLINTCINT' => 'CONINT',
            'CRLPCI' => 'CONRICONP', 'CRLPCINT' => 'CONRINTCONP' ) )
)));

// On which action status we set Service_Entry_Date of Archive
define( "SERVICE_ENTRY_DATE_STATUS", serialize( array( 'CDTRI', 'CDTRINT', 'CDTRP', 'CRLIDINT', 'CRLIDP', 'CRLIDI', 'CRLINTDI', 'CRLINTDP', 'CRLINTDINT' ) ) );

// Where is 1 if internal, is 2 if intermediate and 3 if provider == useless 0.8.0 ??
define( "WHERE_STATUS", serialize( array(
	'DTRI' => 1, 'DTRINT' => 2, 'DTRP' => 3,
	'CLAI' => 1, 'CPAI' => 1, 'CLAINT' => 2, 'CPAINT' => 2, 'CLAP' => 3, 'CPAP' => 3,
	'CRAI' => 1, 'CRAINT' => 2, 'CRAP' => 3,
	'CSAI' => 1, 'CSAINT' => 2, 'CSAP' => 3,
	'CDAI' => 1, 'CDAINT' => 2, 'CDAP' => 3,
    'CRLIDAINT' => 2, 'CRLIDAP' => 3, 'CRLINTDAI' => 1, 'CRLINTDAP' => 3, 'CRLPDAI' => 1, 'CRLPDAINT' => 2,
    'CRLICAINT' => 2, 'CRLICAP' => 3, 'CRLINTCAI' => 1, 'CRLINTCAP' => 3, 'CRLPCAI' => 1, 'CRLPCAINT' => 2,

    'GDTRP' => 3,
    'GDAP' => 3,
    'GLAP' => 3, 'GPAP' => 3,
    'GSAP' => 3,
    'GRAP' => 3,
    'GRLIDAP' => 3, 'GRLINTDAP' => 3,
    'GRLICAP' => 3, 'GRLINTCAP' => 3,

	'CDTRI' => 1, 'CDTRINT' => 2, 'CDTRP' => 3,
	'CLII' => 1, 'CPRI' => 1, 'CLIINT' => 2, 'CPRINT' => 2, 'CLIP' => 3, 'CPRP' => 3,
	'CRTI' => 1, 'CRTINT' => 2, 'CRTP' => 3,
	'CSDI' => 1, 'CSDINT' => 2, 'CSDP' => 3,
	'CDEI' => 1, 'CDEINT' => 2, 'CDEP' => 3,
    'CRLIDINT' => 2, 'CRLIDP' => 3, 'CRLINTDI' => 1, 'CRLINTDP' => 3, 'CRLPDI' => 1, 'CRLPDINT' => 2,
    'CRLICINT' => 2, 'CRLICP' => 3, 'CRLINTCI' => 1, 'CRLINTCP' => 3, 'CRLPCI' => 1, 'CRLPCINT' => 2
)));

define( "ACTION_UNRELOC", serialize( array(
    'CRTPCONRIDISP', 'CRTPCONRINTDISP', 'CRTPCONRICONP', 'CRTPCONRINTCONP', 'CRLIDINT', 'CRLIDP', 'CRLIDI',
    'CRLINTDI', 'CRLINTDP', 'CRLINTDINT', 'CRLICI', 'CRLICINT', 'CRLINTCINT', 'CRLINTCI'
)));
define( "CANCEL_UNRELOC", serialize( array(
    'CRAPCONRIDISP', 'CRAPCONRINTDISP', 'CRAPCONRICONP', 'CRAPCONRINTCONP'
)));
define( "CANCEL_RELOC", serialize( array(
    'CRLIDAINT', 'CRLIDAP', 'CRLIDAI', 'CRLINTDAI', 'CRLINTDAP', 'CRLINTDAINT', 'CRLPDAI', 'CRLPDAINT',
    'CRLICAINT', 'CRLICAI', 'CRLINTCAI', 'CRLINTCAINT', 'CRLPCAI', 'CRLPCAINT',
)));

// Close status for container / box aglomerate cancelation
// In fact when ACTION will go to DISI, DISINT, DISP
define( "CLOSE_STATUS_AGGLO_CANCEL", serialize( array(
    'CDTRI', 'CDTRINT', 'CDTRP',                        // Transfert
    'CRLIDINT', 'CRLIDP', 'CRLIDI',
    'CRLINTDI', 'CRLINTDP', 'CRLINTDINT',
    'CRTI', 'CRTINT', 'CRTP',                           // Return
    'CRTPCONRIDISP', 'CRTPCONRINTDISP',
    'CRTPCONRICONP', 'CRTPCONRINTCONP',
    'CDEI', 'CDEINT', 'CDEP',                           // Destruction
    'CSDI', 'CSDINT', 'CSDP'                            // Exit
)));

define( "CLOSE_DELIVER_PRESERVE_PRECISION", serialize( array(
    'CLII', 'CPRI', 'CLIINT', 'CPRINT', 'CLIP', 'CPRP',
    'CRLPDI', 'CRLPDINT', 'CRLPCI', 'CRLPCINT', 'CRLICI', 'CRLICINT',
    'CRLINTCINT', 'CRLINTCI'
)));


class ArchivistJsonController extends Controller
{

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

    // Return visible statuses in context
    private function getFilterStatus( $uastate, $uawhat, $uawhere, $uahow ){

        $statusFilter = [];
        switch( $uastate ) {
            case 0: // Manage user wants
                switch ($uawhat) {
                    case 0:    // A Transférer
                    case 5:    // A Relocaliser
                        $statusFilter = IDPConstants::$FILTER_STATUS[$uastate][$uawhat][$uawhere];
                        break;
                    case 1: // A Livrer
                        $statusFilter = IDPConstants::$FILTER_STATUS[$uastate][$uawhat][$uahow];
                        break;
                    default:
                        $statusFilter = IDPConstants::$FILTER_STATUS[$uastate][$uawhat];
                }
                break;
            case 1: // Manage provider
                switch( $uawhat ) {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                        $statusFilter = IDPConstants::$FILTER_STATUS[$uastate][$uawhat];
                        break;
                    case 5:
                        $statusFilter = IDPConstants::$FILTER_STATUS[$uastate][$uawhat][$uawhere];
                        break;
                }
                break;
            case 2: // Validate user wants
                switch ($uawhat) {
                    case 0:    // A Transférer
                    case 5: // A Relocaliser
                        $statusFilter = IDPConstants::$FILTER_STATUS[$uastate][$uawhat][$uawhere];
                        break;
                    case 1: // A Livrer
                        $statusFilter = IDPConstants::$FILTER_STATUS[$uastate][$uawhat][$uahow];
                        break;
                    default:
                        $statusFilter = IDPConstants::$FILTER_STATUS[$uastate][$uawhat];
                }
                break;
        }
        return $statusFilter;
    }

	public function loaddatasAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00101 '), 403 );
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {
            $logger = $this->container->getParameter('kernel.environment') == 'dev' ?$this->container->get('logger'):null;
            if( $logger ) $logger->info( '-> Begin loaddatasAction' );

			$return = [];

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
				$sort = 'id';
			if( $parameters->has( 'order' ) )
				$order = $parameters->get('order');
			else
				$order = 'asc';

            if( $parameters->has( 'uastate' ) )
                $uastate = $parameters->get( 'uastate' );
            else
                $uastate = -1;
			if( $parameters->has( 'uawhat' ) )
				$uawhat = $parameters->get( 'uawhat' );
			else
				$uawhat = -1;
			if( $parameters->has( 'uawhere' ) )
				$uawhere = $parameters->get( 'uawhere' );
			else
				$uawhere = -1;
			if( $parameters->has( 'uahow' ) )
				$uahow = $parameters->get( 'uahow' );
			else
				$uahow = -1;
			//if( $parameters->has( 'uawith' ) )
			//	$uawith = $parameters->get( 'uawith' );
			//else
				$uawith = null;
			if( $uawhat != 0 ) $uawith = null;
            if( $parameters->has( 'filterprovider' ) )
                $filterprovider = $parameters->get( 'filterprovider' );
            else
                $filterprovider = -1;
            if( $parameters->has( 'search' ) )
                $special = $parameters->get( 'search' );
            else
                $special = null;
            if( $logger ) $logger->info( ' > Parameters : ' );
            if( $logger ) $logger->info( ' > limit = '.$limit );
            if( $logger ) $logger->info( ' > offset = '.$offset );
            if( $logger ) $logger->info( ' > sort = '.$sort );
            if( $logger ) $logger->info( ' > order = '.$order );
            if( $logger ) $logger->info( ' > uastate = '.$uastate );
            if( $logger ) $logger->info( ' > uawhat = '.$uawhat );
            if( $logger ) $logger->info( ' > uawhere = '.$uawhere );
            if( $logger ) $logger->info( ' > uahow = '.$uahow );
            if( $logger ) $logger->info( ' > filterprovider = '.$filterprovider );
            if( $logger ) $logger->info( ' > search = '.$special );

			// Allowed viewed services, based on user profile
			$userServices = $bsUserSession->getUserServices();
			$allowedServices = [];
			foreach( $userServices as $userService )
				array_push( $allowedServices, $userService->getService()->getId() );

			// Status to search in, based on screen parameters
			$statusFilter = $this->getFilterStatus( $uastate, $uawhat, $uawhere, $uahow );


			// Ask databse for total archives
			$totalArchives = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive' )->countArchivistDatas(
				$allowedServices,
				$statusFilter,
				$uawith,
                $filterprovider,
                $special,
                $uawhat,
                $uawhere );
			// Ask database for archives
			$archives = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive' )->loadArchivistDatas(
				$allowedServices,
				$statusFilter,
				$uawith,			// Container, box, file or nothing
                $filterprovider,
                $special,
				$sort,
				$order,
				$limit,
				$offset,
                $uawhat,
                $uawhere );

			$response_data = array();

			$status = $this->getDoctrine()
				->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')
				->getAllIndexedOnID();
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
            $localizations = $this->getDoctrine()
                ->getRepository('bsIDPBackofficeBundle:IDPLocalizations')
                ->getAllIndexedOnID();
            $adresses = $this->getDoctrine()
                ->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')
                ->getAllIndexedOnID();

			foreach( $archives as $archive ){

				$service_id = array_key_exists('service_id',$archive)?$archive['service_id']:'-1';
				$legalentity_id = array_key_exists('legalentity_id',$archive)?$archive['legalentity_id']:'-1';
				$budgetcode_id = array_key_exists('budgetcode_id',$archive)?$archive['budgetcode_id']:'-1';
				$documentnature_id = array_key_exists('documentnature_id',$archive)?$archive['documentnature_id']:'-1';
				$documenttype_id = array_key_exists('documenttype_id',$archive)?$archive['documenttype_id']:'-1';
				$description1_id = array_key_exists('description1_id',$archive)?$archive['description1_id']:'-1';
				$description2_id = array_key_exists('description2_id',$archive)?$archive['description2_id']:'-1';
				$provider_id = array_key_exists('provider_id',$archive)?$archive['provider_id']:'-1';
                $localization_id = array_key_exists('localization_id',$archive)?$archive['localization_id']:-1;
                $oldlocalization_id = array_key_exists('oldlocalization_id',$archive)?$archive['oldlocalization_id']:-1;

				$adminid = '';
				$adminid .= $service_id . ',' . $legalentity_id . ',' . $budgetcode_id;
				$adminid .= ',' . $documentnature_id . ',' . $documenttype_id;
				$adminid .= ',' . $description1_id . ',' . $description2_id;
				$adminid .= ',' . $provider_id;
                $adminid .= ',' . $localization_id . ',' . $oldlocalization_id;

                $prov_loc = -2;
                if( array_key_exists( 'provider_id', $archive ) )
                    if( array_key_exists( intval( $archive[ 'provider_id' ] ), $providers ) )
                        if( array_key_exists( 'localization_id', $providers[ intval( $archive[ 'provider_id' ] ) ] ) )
                            $prov_loc = intval( $providers[ intval( $archive[ 'provider_id' ] ) ][ 'localization_id' ] );

				$line = array(
					'id' => empty($archive['id'])?'-':$archive['id'],
					'name' => empty($archive['name'])?'-':$archive['name'],
					'status' => array_key_exists('status_id',$archive)?$status[intval($archive['status_id'])]['longname']:'-',
					'statuscaps' => array_key_exists('status_id',$archive)?$status[intval($archive['status_id'])]['shortname']:'-',
					'ordernumber' => empty($archive['ordernumber'])?'-':$archive['ordernumber'],
					'closureyear' => empty($archive['closureyear'])?'-':$archive['closureyear'],
					'destructionyear' => empty($archive['destructionyear'])?'':$archive['destructionyear'],
				//						'createdat' => is_null($dtaElem['createdat'])?'':$dtaElem['createdat']->format('d/m/Y h:m:s'),
				//						'modifiedat' => is_null($dtaElem['modifiedat'])?'':$dtaElem['modifiedat']->format('d/m/Y h:m:s'),
					'limitnummin' => empty($archive['limitnummin'])?'-':$archive['limitnummin'],
					'limitnummax' => empty($archive['limitnummax'])?'-':$archive['limitnummax'],
					'limitdatemin' => empty($archive['limitdatemin'])?'-':$archive['limitdatemin']->format('d/m/Y'),
					'limitdatemax' => empty($archive['limitdatemax'])?'-':$archive['limitdatemax']->format('d/m/Y'),
					'limitalphamin' => empty($archive['limitalphamin'])?'-':$archive['limitalphamin'],
					'limitalphamax' => empty($archive['limitalphamax'])?'-':$archive['limitalphamax'],
					'limitalphanummin' => empty($archive['limitalphanummin'])?'-':$archive['limitalphanummin'],
					'limitalphanummax' => empty($archive['limitalphanummax'])?'-':$archive['limitalphanummax'],
					'service' => array_key_exists('service_id',$archive)?
                        $serviceNames?array_key_exists(intval($archive['service_id']), $serviceNames)?$serviceNames[intval($archive['service_id'])]['longname']:'-':'-':'-',
					'legalentity' => array_key_exists('legalentity_id',$archive)?
                        $legalentities?array_key_exists(intval($archive['legalentity_id']), $legalentities)?$legalentities[intval($archive['legalentity_id'])]['longname']:'-':'-':'-',
					'documentnature' => array_key_exists('documentnature_id',$archive)?
                        $documentnatures?array_key_exists(intval($archive['documentnature_id']), $documentnatures)?$documentnatures[intval($archive['documentnature_id'])]['longname']:'-':'-':'-',
					'documenttype' => array_key_exists('documenttype_id',$archive)?
                        $documenttypes?array_key_exists(intval($archive['documenttype_id']), $documenttypes)?$documenttypes[intval($archive['documenttype_id'])]['longname']:'-':'-':'-',
					'description1' => array_key_exists('description1_id',$archive)?
                        $documentdescription1?array_key_exists(intval($archive['description1_id']), $documentdescription1)?$documentdescription1[intval($archive['description1_id'])]['longname']:'-':'-':'-',
					'description2' => array_key_exists('description2_id',$archive)?
                        $documentdescription2?array_key_exists(intval($archive['description2_id']), $documentdescription2)?$documentdescription2[intval($archive['description2_id'])]['longname']:'-':'-':'-',
					'budgetcode' => array_key_exists('budgetcode_id',$archive)?
                        $documentbudgetcodes?array_key_exists(intval($archive['budgetcode_id']), $documentbudgetcodes)?$documentbudgetcodes[intval($archive['budgetcode_id'])]['longname']:'-':'-':'-',
					'documentnumber' => empty($archive['documentnumber'])?'-':$archive['documentnumber'],
					'boxnumber' => empty($archive['boxnumber'])?'-':$archive['boxnumber'],
					'containernumber' => empty($archive['containernumber'])?'-':$archive['containernumber'],
					'provider' => array_key_exists('provider_id',$archive)?
                        $providers?array_key_exists(intval($archive['provider_id']), $providers)?$providers[intval($archive['provider_id'])]['longname']:'-':'-':'-',
                    'provider_id' => array_key_exists('provider_id', $archive)?intval($archive['provider_id']):-1,
                    'prov_loc_id' => $prov_loc,
                    'localization' => array_key_exists('localization_id',$archive)?
                        $localizations?array_key_exists(intval($archive['localization_id']), $localizations)?$localizations[intval($archive['localization_id'])]['longname']:'-':'-':'-',
                    'localizationfree' => empty($archive['localizationfree'])?'-':$archive['localizationfree'],
                    'oldlocalization' => array_key_exists('oldlocalization_id',$archive)?
                        $localizations?array_key_exists(intval($archive['oldlocalization_id']), $localizations)?$localizations[intval($archive['oldlocalization_id'])]['longname']:'-':'-':'-',
                    'oldlocalizationfree' => $archive['oldlocalizationfree'],
                    'precisiondate' => array_key_exists('precisiondate',$archive)?(empty($archive['precisiondate'])?'-':$archive['precisiondate']->format('d/m/Y')):'-',
                    'precisionaddress' => array_key_exists('precisionaddress_id',$archive)?
                        $adresses?array_key_exists(intval($archive['precisionaddress_id']), $adresses)?$adresses[intval($archive['precisionaddress_id'])]['longname']:'-':'-':'-',
                    'precisionfloor' => empty($archive['precisionfloor'])?'-':$archive['precisionfloor'],
                    'precisionoffice' => empty($archive['precisionoffice'])?'-':$archive['precisionoffice'],
                    'precisionwho' => empty($archive['precisionwho'])?'-':$archive['precisionwho'],
                    'precisioncomment' => empty($archive['precisioncomment'])?'-':$archive['precisioncomment'],
                    'unlimited' => empty($archive['unlimited'])?'inactif':($archive['unlimited']==1?'actif':'inactif'),
                    'unlimitedcomments' => empty($archive['unlimitedcomments'])?'-':$archive['unlimitedcomments'],

					'adminidlist' => $adminid,
                    'locked' => $archive['locked'],
                    'lockbegintime' => $archive['lockbegintime']
				);
				array_push( $response_data, $line );
			}

			$return = array( 'total' => intval( $totalArchives ), 'rows' => $response_data );

			//return null;
			return $this->jsonResponse( $return );
//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}
	}

	// bs_idp_archivist_json_action
	public function actionAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00102 '), 403 );
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $logger = $this->container->getParameter('kernel.environment') == 'dev' ?$this->container->get('logger'):null;

        // GET
        $parameters = $request->query;

        if( $parameters->has( 'uastate' ) )
            $uastate = $parameters->get('uastate');
        else
            $uastate = -1;
        if( $parameters->has( 'uawhat' ) )
            $uawhat = $parameters->get('uawhat');
        else
            $uawhat = -1;
        if( $parameters->has( 'uawhere' ) )
            $uawhere = $parameters->get('uawhere');
        else
            $uawhere = -1;
        if( $parameters->has( 'uawith' ) )
            $uawith = $parameters->get('uawith');
        else
            $uawith = -1;
        if( $parameters->has( 'uahow' ) )
            $uahow = $parameters->get('uahow');
        else
            $uahow = -1;
        if( $parameters->has( 'ids' ) )
            $ids = json_decode($parameters->get('ids'));
        else
            $ids = null;
        if( $parameters->has( 'objects' ) )
            $objects = json_decode($parameters->get('objects'));
        else
            $objects = null;

        if( $ids == null || $uawhat == -1 || $uastate == -1 ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10102') , 419 );
        }

        $em = $this->getDoctrine()->getManager();
        $ACTION_UNRELOC = unserialize( ACTION_UNRELOC );
        $CLOSE_DELIVER_PRESERVE_PRECISION = unserialize( CLOSE_DELIVER_PRESERVE_PRECISION );

        $now = new DateTime();
        $begin = $now->getTimestamp();

        $audit = [];
        $unlockBasketListIDs = [];

        $ACTION_STATUS_temp = unserialize(ACTION_STATUS);
        $ACTION_STATUS = $ACTION_STATUS_temp[$uastate][$uawhat];

        $SERVICE_ENTRY_DATE_STATUS = unserialize( SERVICE_ENTRY_DATE_STATUS );

        $list_object_computed = null;
        if( $objects != null )
            $list_object_computed = $this->computeObjects($objects);

        foreach( $ids as $id ){
            $archive = $this->getDoctrine()
                ->getRepository('bsIDPArchiveBundle:IDPArchive')
                ->find( $id );

            if( $archive ) {

                if( $logger ) $logger->info( "ARchive [C,B,D]=".$archive->getContainernumber().', '.$archive->getBoxnumber().', '.$archive->getDocumentnumber() );

                if (!array_key_exists($archive->getStatus()->getShortname(), $ACTION_STATUS)) {
                    return $this->jsonResponse(array('message' => 'Une erreur système est survenue : E-20102 '.json_encode($ids) ), 419);
                }

                $oldStatus = $archive->getStatus()->getShortname();
                $newStatusShortName = $ACTION_STATUS[$oldStatus];
                $newStatus = $this->getDoctrine()
                    ->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')
                    ->findBy(array('shortname' => $newStatusShortName))[0];

                if( in_array( $oldStatus, $SERVICE_ENTRY_DATE_STATUS ) ) {
                    $archive->setServiceentrydate($begin);

                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_SERVICEENTRYDATE,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $archive->getId(),
                        'new_str' => null,
                        'new_int' => $begin,
                        'old_str' => null,
                        'old_int' => null,
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];
                }

                // E#292 (CLOSE)
                if( in_array( $oldStatus, IDPConstants::$RESET_ENTRYDATE_STATUS ) ){
                    $archive->setServiceentrydate( $begin );

                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_SERVICEENTRYDATE,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $archive->getId(),
                        'new_str' => null,
                        'new_int' => $begin,
                        'old_str' => null,
                        'old_int' => $archive->getSaveserviceentrydate( ),
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];

                    $archive->setSaveserviceentrydate( null );
                }

                // E#165
                if( in_array( $newStatusShortName, IDPConstants::$UNSET_ACTIONBY_STATUS ) ){
                    $archive->setLastActionBy( null );
                }

                // Just copy the last known object type
                $object_type_during_movement = $archive->getObjecttype();

                switch( $uastate ) {
                    case IDPConstants::UASTATE_MANAGEUSERWANTS:
                        if( $logger ) $logger->info( "UASTATE_MANAGEUSERWANTS" );

                        // Set the object type where we are sure (aka Transfer, Delete, Exit, Reloc as transfer, )
                        if (in_array($newStatusShortName, IDPConstants::$MOVEMENT_WHERE_OBJECT_TYPE_IS_OBVIOUS)) {
                            if( $logger ) $logger->info( "Object type is obvious" );

                            $object_type_during_movement =
                                ( $archive->getContainernumber() != null && strlen( $archive->getContainernumber() ) > 0 ) ? IDPConstants::AUDIT_OBJECT_TYPE_CONTAINER :
                                ( ( $archive->getBoxnumber() != null && strlen( $archive->getBoxnumber() ) > 0 ) ? IDPConstants::AUDIT_OBJECT_TYPE_BOX :
                                ( ( $archive->getDocumentnumber() != null && strlen( $archive->getDocumentnumber() ) > 0 ) ? IDPConstants::AUDIT_OBJECT_TYPE_DOCUMENT :
                                IDPConstants::AUDIT_OBJECT_TYPE_UNKNOWN ) );

                            $archive->setFutureobjecttype( $object_type_during_movement );

                            if( $logger ) $logger->info( "Object type detected = ".$object_type_during_movement );
                        }
                        break;

                    case IDPConstants::UASTATE_MANAGEPROVIDER:
                        // B283: For Uas optimized, precisiondate is set to now, precisionwho is set to bsUserSession
                        if (empty($archive->getPrecisionWho())) {
                            $archive->setPrecisionDate($now);
                            $archive->setPrecisionWho($bsUserSession->getUser()->getFirstname() . ' ' . $bsUserSession->getUser()->getLastname());
                        }

                        // Set the object_type based on movement type (or optimisation window)
                        // For Return, should recompute full containers (moved and in DB) to see if blocks have been remade
                        if (in_array($newStatusShortName, IDPConstants::$MOVEMENT_WHERE_OBJECT_TYPE_IS_BASED_ON_OPTIMISATION)) {
                            $object_type_during_movement = null;

                            // The archive gets a container number, so we must verify if we are not dealing with a under object
                            if( $archive->getContainernumber() != null && strlen( $archive->getContainernumber() ) > 0 ){
                                // Perhaps we are talking about box
                                if(( $archive->getBoxnumber() != null && strlen( $archive->getBoxnumber() ) > 0 )
                                    && in_array( $archive->getBoxNumber(), $list_object_computed['boxes'][$archive->getService()->getId()] ) ) {
                                    $object_type_during_movement = IDPConstant::AUDIT_OBJECT_TYPE_BOX;
                                } else {
                                    // Or about document
                                    if( ( $archive->getDocumentnumber() != null && strlen( $archive->getDocumentnumber() ) > 0 )
                                        && in_array( $archive->getDocumentNumber(), $list_object_computed['documents'][$archive->getService()->getId()] ) ) {
                                        $object_type_during_movement = IDPConstant::AUDIT_OBJECT_TYPE_DOCUMENT;
                                    } else {
                                        // Neither box neither document so it must be container
                                        $object_type_during_movement = IDPConstant::AUDIT_OBJECT_TYPE_CONTAINER;
                                    }
                                }
                            } else {
                                // So the archive does not contain any container information, so it cannot be a container, perhaps a box
                                if( $archive->getBoxnumber() != null && strlen( $archive->getBoxnumber() ) > 0 ){
                                    if( ( $archive->getDocumentnumber() != null && strlen( $archive->getDocumentnumber() ) > 0 )
                                        && in_array( $archive->getDocumentnumber(), $list_object_computed['documents'][$archive->getService()->getId()] ) ) {
                                        $object_type_during_movement = IDPConstant::AUDIT_OBJECT_TYPE_DOCUMENT;
                                    } else {
                                        // It's not a box, so it must be a box
                                        $object_type_during_movement == IDPConstant::AUDIT_OBJECT_TYPE_BOX;
                                    }
                                } else {
                                    // So the archive does not contain any container nor box information, so it cannot be a container nor a box, probably a box
                                    if( $archive->getDocumentnumber() != null && strlen($archive->getDocumentnumber()) > 0)
                                        $object_type_during_movement = IDPConstants::AUDIT_OBJECT_TYPE_DOCUMENT;
                                    else
                                        // It is all 3
                                        $object_type_during_movement = IDPConstants::AUDIT_OBJECT_TYPE_UNKNOWN;
                                }
                            }
                            $archive->setFutureobjecttype( $object_type_during_movement );
                        }
                        break;

                    case IDPConstants::UASTATE_CLOSEUSERWANTS: // Archivist Close
                        // B#209: Close action must remove all precisions
                        // B#285: But not in case of Closing delivery
                        if (!in_array($oldStatus, $CLOSE_DELIVER_PRESERVE_PRECISION)) {
                            $archive->setPrecisiondate(null);
                            $archive->setPrecisionwhere(null);
                            $archive->setPrecisionfloor(null);
                            $archive->setPrecisionoffice(null);
                            $archive->setPrecisionwho(null);
                            $archive->setPrecisioncomment(null);
                        }

                        $archive->setObjecttype( $archive->getFutureobjecttype() );
                        $archive->setFutureobjecttype( null );

                        break;
                }

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $archive->getId(),
                    'new_str' => null,
                    'new_int' => $newStatus->getId(),
                    'old_str' => null,
                    'old_int' => $archive->getStatus()->getId(),
                    'complete_ua_id' => null,
                    'object_type' => $archive->getObjecttype() /// ??
                ];

                $archive->setStatus($newStatus);

                $ACTION_DELETE = unserialize(DELETE_STATUS );

                // Unlock archive ==> TODO ?
                $archive->setLocked(null);
                $archive->setLockbegintime(null);

                // Manage localisation / oldlocalisation
                if (in_array($oldStatus, $ACTION_UNRELOC)) {
                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_OLDLOCALIZATION,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $archive->getId(),
                        'new_str' => null,
                        'new_int' => null,
                        'old_str' => null,
                        'old_int' => $archive->getOldlocalization()===null?null:$archive->getOldlocalization()->getId(),
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];
                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_OLDLOCALIZATIONFREE,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $archive->getId(),
                        'new_str' => null,
                        'new_int' => null,
                        'old_str' => $archive->getOldlocalizationfree(),
                        'old_int' => null,
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];
                    $archive->setOldLocalization( null );
                    $archive->setOldlocalizationfree( null );

                }

                if( $uastate == IDPConstants::UASTATE_CLOSEUSERWANTS ) {
                    // B239 Going to a Status where containerAsked or BoxAsked must be flushed
                    $CLOSE_STATUS_AGGLO_CANCEL = unserialize(CLOSE_STATUS_AGGLO_CANCEL);
                    if (in_array($oldStatus, $CLOSE_STATUS_AGGLO_CANCEL)) {
                        $archive->setContainerAsked(0);
                        $archive->setBoxAsked(0);
                    } else { // we are in close phase for other status ==> Just remove Basket agglo if exist
                        $unlockBasketListIDs[] = $archive->getId();
                    }
                }

                // if archive has to be deleted, backup it and delete it
                if (in_array($newStatusShortName, $ACTION_DELETE)) {
                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_DELETE,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_NA,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $archive->getId(),
                        'new_str' => null,
                        'new_int' => null,
                        'old_str' => null,
                        'old_int' => null,
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];

                    $backupArchive = new IDPDeletedArchive();

                    $backupArchive->setName($archive->getName());
                    $backupArchive->setOrdernumber($archive->getOrdernumber());
                    $backupArchive->setBudgetcode($archive->getBudgetcode() == null ? null : $archive->getBudgetcode()->getLongname());
                    $backupArchive->setLocalization($archive->getLocalization() == null ? null : $archive->getLocalization()->getLongname());
                    $backupArchive->setLocalizationfree($archive->getLocalizationfree());
                    $backupArchive->setClosureyear($archive->getClosureyear());
                    $backupArchive->setDestructionyear($archive->getDestructionyear());
                    $backupArchive->setService($archive->getService() == null ? null : $archive->getService()->getLongname());
                    $backupArchive->setLegalentity($archive->getLegalentity() == null ? null : $archive->getLegalentity()->getLongname());
                    $backupArchive->setDocumentnature($archive->getDocumentnature() == null ? null : $archive->getDocumentnature()->getLongname());
                    $backupArchive->setDocumenttype($archive->getDocumenttype() == null ? null : $archive->getDocumenttype()->getLongname());
                    $backupArchive->setDescription1($archive->getDescription1() == null ? null : $archive->getDescription1()->getLongname());
                    $backupArchive->setDescription2($archive->getDescription2() == null ? null : $archive->getDescription2()->getLongname());
                    $backupArchive->setLimitnummin($archive->getLimitnummin());
                    $backupArchive->setLimitnummax($archive->getLimitnummax());
                    $backupArchive->setLimitdatemin($archive->getLimitdatemin());
                    $backupArchive->setLimitdatemax($archive->getLimitdatemax());
                    $backupArchive->setLimitalphamin($archive->getLimitalphamin());
                    $backupArchive->setLimitalphamax($archive->getLimitalphamax());
                    $backupArchive->setLimitalphanummin($archive->getLimitalphanummin());
                    $backupArchive->setLimitalphanummax($archive->getLimitalphanummax());
                    $backupArchive->setDocumentnumber($archive->getDocumentnumber());
                    $backupArchive->setBoxnumber($archive->getBoxnumber());
                    $backupArchive->setContainernumber($archive->getContainernumber());
                    $backupArchive->setProvider($archive->getProvider() == null ? null : $archive->getProvider()->getLongname());

                    $em->persist($backupArchive);
                    $em->remove($archive);

                } else {
                    // otherwise persist the updated archive

                    $em->persist($archive);
                }
            }
        }

        $em->flush();

        $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );

        // Post treatment in cas of close with only basket remove
        if( !empty($unlockBasketListIDs) ){
            $this->unlockBasket( $unlockBasketListIDs, $logger );
        }

        //return null;
        return $this->jsonResponse( array( 'message', 'Success' ) );
	}

    //.......................................................................................................
    // This function calculate all objects fully asked in optimization modal
    // Object is a list of C_Cnb_Snb and B_Bnb_Snb where C = container, B = Box and S = Service
    //  [] ==> ['containers' ==> [Services]( numbers ...), 'boxes' ==> [Services](numbers ...), 'subboxes' ==> [Services](numbers ...)]
    private function computeObjects( $objects ){
        $listContainers = [];
        $listSubBoxes = [];
        $listBoxes = [];

        foreach( $objects as $object ){
            if( $object[0] == 'C' ){
                $strExplod = explode( '_', $object );
                if( sizeof($strExplod) == 3 ) {
                    $listContainers[intval($strExplod[2])][] = intval($strExplod[1]);
                } else if( sizeof($strExplod) == 5 ){
                    $listSubBoxes[intval($strExplod[2])][] = intval($strExplod[4]);
                }
            } elseif ( $objects[0] == 'B' ){
                $strExplod = explode( '_', $object );
                if(  sizeof($strExplod) >= 3 )
                    $listBoxes[intval($strExplod[2])][] = intval($strExplod[1]);
            }
        }

        return [ 'containers' => $listContainers, 'boxes' => $listBoxes, 'subboxes' => $listSubBoxes ];
    }


    public function cancelAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00103 '), 403 );
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '> Begin cancelAction' );

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request

        if( $parameters->has( 'uastate' ) )
            $uastate = $parameters->get('uastate');
        else
            $uastate = -1;
        if( $parameters->has( 'uawhat' ) )
            $uawhat = $parameters->get('uawhat');
        else
            $uawhat = -1;
        if( $parameters->has( 'ids' ) )
            $ids = json_decode( $parameters->get('ids') );
        else
            $ids = null;

        if( $ids == null || $uawhat == -1 || $uastate == -1 ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10103'), 419 );
        }

        $em = $this->getDoctrine()->getManager();
        $CANCEL_RELOC = unserialize( CANCEL_RELOC );
        $CANCEL_UNRELOC = unserialize( CANCEL_UNRELOC );

        $audit = [];

        foreach( $ids as $id ){
            $archive = $this->getDoctrine()
                ->getRepository('bsIDPArchiveBundle:IDPArchive')
                ->find( $id );

            if( $archive ) {
                $oldStatus = $archive->getStatus();

                // E#292 (BACKUP)
                if( in_array( $oldStatus->getShortname(), IDPConstants::$SAVE_ENTRYDATE_STATUS ) ){
                    $archive->setServiceentrydate( $archive->getSaveserviceentrydate() );
                    $archive->setSaveserviceentrydate( null );
                }

                // Manage localisation / oldlocalisation
                if ( $uawhat == IDPConstants::UAWHAT_RELOC ) {// RELOC
                    if (in_array($oldStatus->getShortname(), $CANCEL_RELOC)) {
                        // Special treatment, copy back oldrelocalization into localization and empty oldrelocalisation
                        $audit[] = [
                            'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                            'user_id' => $bsUserSession->getUserId(),
                            'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATION,
                            'entity' => IDPConstants::ENTITY_ARCHIVE,
                            'entity_id' => $archive->getId(),
                            'new_str' => null,
                            'new_int' => $archive->getOldlocalization() === null ? null : $archive->getOldlocalization()->getId(),
                            'old_str' => null,
                            'old_int' => $archive->getLocalization() === null ? null : $archive->getLocalization()->getId(),
                            'complete_ua_id' => null,
                            'object_type' => null
                        ];
                        $audit[] = [
                            'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                            'user_id' => $bsUserSession->getUserId(),
                            'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                            'entity' => IDPConstants::ENTITY_ARCHIVE,
                            'entity_id' => $archive->getId(),
                            'new_str' => $archive->getOldlocalizationfree(),
                            'new_int' => null,
                            'old_str' => $archive->getLocalizationfree(),
                            'old_int' => null,
                            'complete_ua_id' => null,
                            'object_type' => null
                        ];
                        $audit[] = [
                            'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                            'user_id' => $bsUserSession->getUserId(),
                            'field' => IDPConstants::FIELD_ARCHIVE_OLDLOCALIZATION,
                            'entity' => IDPConstants::ENTITY_ARCHIVE,
                            'entity_id' => $archive->getId(),
                            'new_str' => null,
                            'new_int' => null,
                            'old_str' => null,
                            'old_int' => $archive->getOldlocalization() === null ? null : $archive->getOldlocalization()->getId(),
                            'complete_ua_id' => null,
                            'object_type' => null
                        ];
                        $audit[] = [
                            'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                            'user_id' => $bsUserSession->getUserId(),
                            'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATION,
                            'entity' => IDPConstants::ENTITY_ARCHIVE,
                            'entity_id' => $archive->getId(),
                            'new_str' => null,
                            'new_int' => null,
                            'old_str' => $archive->getOldlocalizationfree(),
                            'old_int' => null,
                            'complete_ua_id' => null,
                            'object_type' => null
                        ];
                        $archive->setLocalization($archive->getOldlocalization());
                        $archive->setLocalizationfree($archive->getOldlocalizationfree());
                        $archive->setOldlocalization(null);
                        $archive->setOldlocalizationfree(null);
                    }
                }
                if( $uawhat == IDPConstants::UAWHAT_RETURN ) { // RETURN (unreloc)
                    if (in_array($oldStatus->getShortname(), $CANCEL_UNRELOC)) {
                        $audit[] = [
                            'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                            'user_id' => $bsUserSession->getUserId(),
                            'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATION,
                            'entity' => IDPConstants::ENTITY_ARCHIVE,
                            'entity_id' => $archive->getId(),
                            'new_str' => null,
                            'new_int' => $archive->getOldlocalization() === null ? null : $archive->getOldlocalization()->getId(),
                            'old_str' => null,
                            'old_int' => $archive->getLocalization() === null ? null : $archive->getLocalization()->getId(),
                            'complete_ua_id' => null,
                            'object_type' => null
                        ];
                        $audit[] = [
                            'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                            'user_id' => $bsUserSession->getUserId(),
                            'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                            'entity' => IDPConstants::ENTITY_ARCHIVE,
                            'entity_id' => $archive->getId(),
                            'new_str' => $archive->getOldlocalizationfree(),
                            'new_int' => null,
                            'old_str' => $archive->getLocalizationfree(),
                            'old_int' => null,
                            'complete_ua_id' => null,
                            'object_type' => null
                        ];
                        $audit[] = [
                            'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                            'user_id' => $bsUserSession->getUserId(),
                            'field' => IDPConstants::FIELD_ARCHIVE_OLDLOCALIZATION,
                            'entity' => IDPConstants::ENTITY_ARCHIVE,
                            'entity_id' => $archive->getId(),
                            'new_str' => null,
                            'new_int' => $archive->getLocalization() === null ? null : $archive->getLocalization()->getId(),
                            'old_str' => null,
                            'old_int' => $archive->getOldlocalization() === null ? null : $archive->getOldlocalization()->getId(),
                            'complete_ua_id' => null,
                            'object_type' => null
                        ];
                        $audit[] = [
                            'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                            'user_id' => $bsUserSession->getUserId(),
                            'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATION,
                            'entity' => IDPConstants::ENTITY_ARCHIVE,
                            'entity_id' => $archive->getId(),
                            'new_str' => $archive->getLocalizationfree(),
                            'new_int' => null,
                            'old_str' => $archive->getOldlocalizationfree(),
                            'old_int' => null,
                            'complete_ua_id' => null,
                            'object_type' => null
                        ];
                        $temploc = $archive->getLocalization();
                        $templocfree = $archive->getLocalizationfree();
                        $archive->setLocalization($archive->getOldlocalization());
                        $archive->setLocalizationfree($archive->getOldlocalizationfree());
                        $archive->setOldlocalization($temploc);
                        $archive->setOldlocalizationfree($templocfree);
                    }
                }

                $CANCEL_STATUS = unserialize(CANCEL_STATUS);
                if (!array_key_exists( $oldStatus->getShortname(), $CANCEL_STATUS )) {
                    return $this->jsonResponse(array('message' => 'Une erreur système est survenue : E-20103' ), 419);
                }

                $newStatusShortName = $CANCEL_STATUS[ $oldStatus->getShortname() ];
                $newStatus = $this->getDoctrine()
                    ->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')
                    ->findBy(array('shortname' => $newStatusShortName))[0];

                // E#165
                if( in_array( $newStatusShortName, IDPConstants::$UNSET_ACTIONBY_STATUS ) ){
                    $archive->setLastActionBy( null );
                }

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $archive->getId(),
                    'new_str' => null,
                    'new_int' => $newStatus->getId(),
                    'old_str' => null,
                    'old_int' => $oldStatus->getId(),
                    'complete_ua_id' => null,
                    'object_type' => $archive->getObjecttype()
                ];
                $archive->setStatus($newStatus);

                // Unlock archive
                $archive->setLocked(null);
                $archive->setLockbegintime(null);

                $em->persist($archive);
            }
        }

        $em->flush();

        $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );

        //return null; // debug purpose

        return $this->jsonResponse( array( 'message' => 'Success' ) );
	}

    public function updatecontainerAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00104 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

            // GET
            $parameters = $request->query;
            // POST
            //$parameters = $request->request;

            if( $parameters->has( 'idlist' ) )
                $idlist = $parameters->get('idlist');
            else
                $idlist = null;
            if( $parameters->has( 'containernumber' ) )
                $containername = $parameters->get( 'containernumber' );
            else
                $containername = null;

            if( $idlist == null || $containername == null )
                return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10104'), 419 );

            $em = $this->getDoctrine()->getManager();
            $idslist = explode( ',', $idlist );
            $audit = [];
            foreach( $idslist as $id ){
                $archive = $this->getDoctrine()
                    ->getRepository('bsIDPArchiveBundle:IDPArchive')
                    ->find( $id );

                if( $archive ) {
                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_CONTAINERNUMBER,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $archive->getId(),
                        'new_str' => $containername,
                        'new_int' => null,
                        'old_str' => $archive->getContainernumber(),
                        'old_int' => null,
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];

                    $archive->setContainernumber($containername);

                    $em->persist($archive);
                }

            }
            $em->flush();

            $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );

            return $this->jsonResponse( array( 'message', 'Success' ) );
//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}

    }

    public function updateboxAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00105 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        //$parameters = $request->request;

        if( $parameters->has( 'idlist' ) )
            $idlist = $parameters->get('idlist');
        else
            $idlist = null;
        if( $parameters->has( 'boxnumber' ) )
            $boxnumber = $parameters->get( 'boxnumber' );
        else
            $boxnumber = null;

        if( $idlist == null || $boxnumber == null )
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10105'), 419 );

        $em = $this->getDoctrine()->getManager();
        $idslist = explode( ',', $idlist );
        $audit = [];
        foreach( $idslist as $id ){
            $archive = $this->getDoctrine()
                ->getRepository('bsIDPArchiveBundle:IDPArchive')
                ->find( $id );

            if( $archive ) {

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_BOXNUMBER,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $archive->getId(),
                    'new_str' => $boxnumber,
                    'new_int' => null,
                    'old_str' => $archive->getBoxnumber(),
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];

                $archive->setBoxnumber($boxnumber);

                $em->persist($archive);
            }

        }

        $em->flush();

        $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );

        return $this->jsonResponse( array( 'message', 'Success' ) );
//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}

    }

    public function updatelocalizationAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00106 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        //$parameters = $request->request;

        if( $parameters->has( 'idlist' ) )
            $idlist = $parameters->get('idlist');
        else
            $idlist = null;
        if( $parameters->has( 'uawhere' ) )
            $uawhere = $parameters->get('uawhere');
        else
            $uawhere = null;
        if( $parameters->has( 'provider_id' ) )
            $provider_id = $parameters->get( 'provider_id' );
        else
            $provider_id = null;
        if( $parameters->has( 'localization_id' ) )
            $localization_id = $parameters->get( 'localization_id' );
        else
            $localization_id = null;
        if( $parameters->has( 'localizationfree' ) )
            $localizationfree = $parameters->get( 'localizationfree' );
        else
            $localizationfree = null;

        if( $idlist == null || $uawhere == null )
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10106'), 419 );

        $em = $this->getDoctrine()->getManager();

        if( $uawhere == 0 ) {
            $localization = $this->getDoctrine()
                ->getRepository('bsIDPBackofficeBundle:IDPLocalizations')
                ->find($localization_id);
            if (!$localization) {
                return $this->jsonResponse(array('message' => 'Une erreur système est survenue : E-20106'), 419);
            }

            $provider = $this->getDoctrine()
                ->getRepository( 'bsIDPBackofficeBundle:IDPProviders' )
                ->find( $provider_id );
            if( !$provider ){
                return $this->jsonResponse( array( 'message' => 'Une erreur système est survenue : E-20206' ), 419 );
            }
        }

        $idslist = explode( ',', $idlist );
        $audit = [];

        foreach( $idslist as $id ){
            $archive = $this->getDoctrine()
                ->getRepository('bsIDPArchiveBundle:IDPArchive')
                ->find( $id );

            if( $archive ) {
                if ($uawhere == 0) { // Provider
                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_PROVIDER,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $archive->getId(),
                        'new_str' => null,
                        'new_int' => $provider === null ? null : $provider->getId(),
                        'old_str' => null,
                        'old_int' => $archive->getProvider() === null ? null : $archive->getProvider()->getId(),
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];
                    $archive->setProvider($provider);
                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATION,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $archive->getId(),
                        'new_str' => null,
                        'new_int' => $localization === null ? null : $localization->getId(),
                        'old_str' => null,
                        'old_int' => $archive->getLocalization() === null ? null : $archive->getLocalization()->getId(),
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];
                    $archive->setLocalization($localization);

                    $archive->setLocalizationfree(null);
                } else {
                    $archive->setLocalization(null);

                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $archive->getId(),
                        'new_str' => $localizationfree,
                        'new_int' => null,
                        'old_str' => $archive->getLocalizationfree(),
                        'old_int' => null,
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];
                    $archive->setLocalizationfree($localizationfree);
                }
                $em->persist($archive);
            }

        }

        $em->flush();

        $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );
        return $this->jsonResponse( array( 'message', 'Success' ) );
//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}

    }

    public function getProviderConnectorBackupAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00107 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        //$parameters = $request->request;

        $userid = $bsUserSession->getUserId();

        $pcb = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPProviderConnectorBackup')
            ->getArray( $userid );

        if( !$pcb || sizeof( $pcb ) <= 0 ){ // If doesn't exist yet, create an ampty one and send it
            $em = $this->getDoctrine()->getManager();
            $pcb = new IDPProviderConnectorBackup();
            $pcb->setUserid( $userid );
            $em->persist( $pcb );
            $em->flush();

            $pcb = $this->getDoctrine()
                ->getRepository('bsIDPBackofficeBundle:IDPProviderConnectorBackup')
                ->getArray( $userid );
        }

        return $this->jsonResponse( array( 'message' => 'Success', 'datas' => $pcb  ) );

//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}
    }

    public function setProviderConnectorBackupAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00108 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        //$parameters = $request->request;

        $userid = $bsUserSession->getUserId();
        if( $parameters->has( 'contact' ) )
            $contact = $parameters->get('contact');
        else
            $contact = null;
        if( $parameters->has( 'phone' ) )
            $phone = $parameters->get('phone');
        else
            $phone = null;
        if( $parameters->has( 'address' ) )
            $address = $parameters->get('address');
        else
            $address = null;
        if( $parameters->has( 'deliver' ) )
            $deliver = $parameters->get('deliver');
        else
            $deliver = null;
        if( $parameters->has( 'disposal' ) )
            $disposal = $parameters->get('disposal');
        else
            $disposal = null;
        if( $parameters->has( 'type' ) )
            $type = $parameters->get('type');
        else
            $type = null;
        if( $parameters->has( 'type2' ) )
            $type2 = $parameters->get('type2');
        else
            $type2 = null;
        if( $parameters->has( 'remark' ) )
            $remark = $parameters->get('remark');
        else
            $remark = null;
        if( $parameters->has( 'name' ) )
            $name = $parameters->get('name');
        else
            $name = null;
        if( $parameters->has( 'firstname' ) )
            $firstname = $parameters->get('firstname');
        else
            $firstname = null;
        if( $parameters->has( 'function' ) )
            $function = $parameters->get('function');
        else
            $function = null;

        $em = $this->getDoctrine()->getManager();

        $pcb = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPProviderConnectorBackup')
            ->findOneBy( array( 'userid' => $userid ) );

        if( !$pcb ) {
            $pcb = new IDPProviderConnectorBackup();
            $pcb->setUserid($userid);
        }

        $pcb->setContact( $contact );
        $pcb->setPhone( $phone );
        $pcb->setAddress( $address );
        $pcb->setDeliver( intval( $deliver ) );
        $pcb->setType( intval( $type ) );
        $pcb->setType2( intval( $type2 ) );
        $pcb->setDisposal( intval( $disposal ) );
        $pcb->setRemark( $remark );
        $pcb->setName( $name );
        $pcb->setFirstname( $firstname );
        $pcb->setFunction( $function );

        $em->persist( $pcb );
        $em->flush();

        return $this->jsonResponse( array( 'message' => 'Success' ) );

//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}
    }

    // TODO modify les em / flush du tempOpti avec
    // bs_idp_archivist_provider_connector_optimisation
    // $connexion = $this->em->getConnexion();
    // $sql = 'INSERT INTO... VALUES ...';
    // $connexion->exec($sql);;
    //
    public function providerConnectorOptimisationAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00109 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $userId = $bsUserSession->getUserId();

        $session = $this->get('session');
        $session->save();
        session_write_close();

        $logger = ( $this->container->getParameter('kernel.environment') == 'dev' ) ? $this->container->get('logger') : null;
        if( $logger ) $logger->info( '> Begin providerConnectorOptimisationAction' );

        $em = $this->getDoctrine()->getManager();

//		if($request->isXmlHttpRequest()) {

        // GET
         $parameters = $request->query;
        // POST
        // $parameters = $request->request;

        if( $parameters->has( 'idlist' ) )
            $tempidlist = $parameters->get('idlist');
        else
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10109'), 419 );
        if( $parameters->has( 'uawhat' ) )
            $uawhat = $parameters->get('uawhat');
        else
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10109'), 419 );

        $tempOpti = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPTempOpti' )
            ->findOneBy( array( 'user_id' => $userId ) );
        if( !$tempOpti ){
            $tempOpti = new IDPTempOpti();
            $tempOpti->setUserId( $userId );
        }
        $tempOpti->setPercent( 10 );
        $tempOpti->setMessage( 'Début du calcul d\'optimisation.' );
        $em->persist( $tempOpti );
        $em->flush();

        $idlist = json_decode( $tempidlist );
        if( $logger ) $logger->info( '> idlist : '. json_encode( $idlist ) );

        if( $logger ) $logger->info( '> Lock all archive asked ' );

        $this->getDoctrine()
            ->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
            ->blockAllArchiveInList( $idlist, true );

        $tempOpti->setPercent( 10 );
        $tempOpti->setMessage( 'Vérification des contenants.' );
        $em->persist( $tempOpti );
        $em->flush();

        $tempOpti->setPercent( 20 );
        $tempOpti->setMessage( 'Récupération des archives pour le calcul d\'optimisation.' );
        $em->persist( $tempOpti );
        $em->flush();

        if( $logger ) $logger->info( '> Ask for archive to optimize ' );
        $uas = $this->getDoctrine()
            ->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
            ->getAllInListForConnectorProvider( $idlist );

        $provider_id = null;
        $providers = [];
        $containerlist = [];
        $boxlist = [];
        $idlist2 = [];

        if( $logger ) $logger->info( '> to be optimized $uas : '. json_encode( $uas ) );

        $max = sizeof( $uas );
        $current = 0;

        foreach( $uas as $ua ){
            if( $logger ) $logger->info( ' > Treat UA '. json_encode( $ua ) );
            if( $ua['puid'] != null && $provider_id == null ) {
                if( $logger ) $logger->info( ' - set provider to '.$ua['puid'] );
                $provider_id = $ua['puid'];
            }
            if( $ua['puid'] != $provider_id ){
                $providers[$provider_id] = [ $containerlist, $boxlist, $idlist2 ];
                $containerlist = [];
                $boxlist = [];
                $idlist2 = [];
                $provider_id = $ua['puid'];
                if( $logger ) $logger->info( ' - changed provider, so add to array : '.json_encode($providers) );
            }
            if( $logger ) $logger->info( ' - Push UA in the right list ' );
            if ((!empty($ua['containernumber'])) && (!empty($ua['suid'])) && (!in_array(array($ua['containernumber'], $ua['suid']), $containerlist))) {
                $containerlist[] = array($ua['containernumber'], $ua['suid']);
            } else {
                if ((!empty($ua['boxnumber'])) && (!empty($ua['suid'])) && (!in_array(array($ua['boxnumber'], $ua['suid']), $boxlist))) {
                    $boxlist[] = array($ua['boxnumber'], $ua['suid']);
                } else {
                    $idlist2[] = $ua['id'];
                }
            }

            $tempOpti->setPercent( 20 + 70 * ++$current / $max );
            $tempOpti->setMessage( 'Optimisation...' );
            $em->persist( $tempOpti );
            $em->flush();
            //sleep(1);
        }

        if( !empty($containerlist) || !empty($boxlist) || !empty($idlist2) )
            $providers[$provider_id] = [ $containerlist, $boxlist, $idlist2 ];
        if( $logger ) $logger->info( ' > End of Treatment providers= '. json_encode( $providers ) );

        if( $logger ) $logger->info( '> Ask for optimization ' );

        $tempOpti->setPercent( 90 );
        $tempOpti->setMessage( 'Mise à jour des données d\'optimisation.' );
        $em->persist( $tempOpti );
        $em->flush();

        $uasoptimized = [];
        foreach( $providers as $key => $provider ) {
            if( $logger ) $logger->info( ' - ask for optimization ('. $key .', '.json_encode($provider[0]).', '.json_encode($provider[1]).', '.json_encode($provider[2]).')' );
            $tempuasoptimized = $this->getDoctrine()
                ->getRepository('bsIDPArchiveBundle:IDPArchive')
                ->getAllOptimizedConnectorProvider($key, $provider[0], $provider[1], $provider[2]);

            if( $logger ) $logger->info( ' - result for optimization ='. json_encode($tempuasoptimized));

            $uasoptimized = array_merge( $uasoptimized, $tempuasoptimized);
        }
        if( $logger ) $logger->info( '> optimized $uasoptimized : '. json_encode( $uasoptimized ) );

        if( $logger ) $logger->info( '> Lock all archive optimized ' );
        $idlist3 = [];
        foreach( $uasoptimized as $ua ){
            $idlist3[] = $ua['id'];
        }

        $this->getDoctrine()
            ->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
            ->blockAllArchiveInList( $idlist3, true );

        if( $logger ) $logger->info( '< End providerConnectorOptimisationAction' );

        $tempOpti->setPercent( 100 );
        $tempOpti->setMessage( 'Fin de l\'optimisation.' );
        $em->persist( $tempOpti );
        $em->flush();

        if( $logger ) $logger->info( '> return : '.json_encode($uasoptimized) );
        //return null;

        return $this->jsonResponse( array( 'message' => 'Success', 'optimizedlist' =>$uasoptimized ) );

//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}
    }

    public function providerConnectorUngrayAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00110 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '> Begin providerConnectorOptimisationAction' );

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;

        if( $parameters->has( 'idoptimizedlist' ) )
            $tempidlist = $parameters->get('idoptimizedlist');
        else
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10110'), 419 );
        $idlist = json_decode( $tempidlist );
        if( $dev_mode ) $this->container->get('logger')->info( '> idlist : '. json_encode( $idlist ) );

        if( $dev_mode ) $this->container->get('logger')->info( '> Ungray all archive asked ' );

        $this->getDoctrine()
            ->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
            ->blockAllArchiveInList( $idlist, false );

        return $this->jsonResponse( array( 'message' => 'Success' ) );
    }

    public function getOptimisationStatusAction( Request $request )
    {

        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged()) {
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10111 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $userId = 1; // $bsUserSession->getUserId();

        $tempOpti = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPTempOpti')
            ->findOneBy(array( 'user_id' => $userId ));
        if (!$tempOpti) {
            return $this->jsonResponse(array('message' => 'Une erreur système est survenue : E-20111'), 204 );
        }
        $message = $tempOpti->getMessage();
        $percent = $tempOpti->getPercent();

        return $this->jsonResponse(array('percent' => $percent, 'message' => $message));
    }

    //-----------------------------------------------------------------------------------------------------------------
    // bs_idp_archivist_json_is_action_coherency_verified ==> /action_coherency_verification/
    // Verify coherency of demand, as specified in #B126
    public function isActionCoherencyVerifiedAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00112 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '> Begin isActionCoherencyVerifiedAction' );

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;

        if( $parameters->has( 'idslist' ) )
            $tempidslist = $parameters->get('idslist');
        else
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10112'), 419 );
        $idslist = json_decode( $tempidslist );
        if( $parameters->has( 'uastate' ) )
            $uastate = $parameters->get('uastate');
        else
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10212'), 419 );
        if( $parameters->has( 'uawhat' ) )
            $uawhat = $parameters->get('uawhat');
        else
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10312'), 419 );
        if( $parameters->has( 'uawhere' ) )
            $uawhere = $parameters->get('uawhere');
        else
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10412'), 419 );
        if( $dev_mode ){
            $this->container->get('logger')->info( '> idslist : '. $tempidslist );
            $this->container->get('logger')->info( '> uastate : '. $uastate );
            $this->container->get('logger')->info( '> uawhat : '. $uawhat );
            $this->container->get('logger')->info( '> uawhere : '. $uawhere );
        }

        // Get all archives from list id
        if( $dev_mode ) $this->container->get('logger')->info( '> Ask for archives to verify ' );
        $uas = $this->getDoctrine()
            ->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
            ->getAllInListForArchivistVerification( $idslist );

        if( $dev_mode ) $this->container->get('logger')->info( '> First pass to analyse list and count ' );
        // Set 2 arrays, first for container analysis, second for box analysis
        // array[ container_number+service_id+serviceentrydate ] = { number_in_list, number_in_db, number_without_more, number_without_more_in_db, container, service, serviceentrydate, status }
        // ==> number_in_list : counts how many time this container is present in list
        // ==> number_in_db: counts how many time this container is present in database, allows to determine if list is complete
        // ==> number_without_more : counts how many time this container is present without boxnumber and filenumber (only container)
        // ==> number_without_more_in_db : counts  the same as number_without_more but in db, allows to determine if we have all of these in list
        // Same logic for Box
        $listContainerAnalysis = [];
        $listBoxAnalysis = [];
        $listErrors = []; // List of archives in error

        foreach( $uas as $ua ){
            if( $dev_mode ) $this->container->get('logger')->info( '-> Analyse UA:'.$ua['id'].
                ' {container:'.$ua['containernumber'].
                ', box:'. $ua['boxnumber'].
                ', document:'.$ua['documentnumber'].'}' );

            if( $ua['containernumber'] != null && strlen(trim($ua['containernumber'])) > 0 ){ // There is a containernumber
                $withoutmore = (( $ua['boxnumber'] == null || strlen(trim($ua['boxnumber'])) <= 0 )
                    && ( $ua['documentnumber'] == null || strlen(trim($ua['documentnumber'])) <= 0 ));

                $containerindex = ''.trim($ua['containernumber']).'|'.$ua['service'].'|'.$ua['serviceentrydate'];

                if( array_key_exists($containerindex, $listContainerAnalysis ) ){
                    $listContainerAnalysis[$containerindex]['number_in_list']++;
                    if( $withoutmore )
                        $listContainerAnalysis[$containerindex]['number_without_more']++;
                    if( $dev_mode ) $this->container->get('logger')->info( '  * Increase container['.$containerindex.'] number_in_list '
                        .($withoutmore?'and number_without_more':''));
                } else {
                    $listContainerAnalysis[$containerindex] = array(
                        'number_in_list' => 1,
                        'number_without_more' => ($withoutmore?1:0),
                        'number_in_db' => 0,
                        'number_without_more_in_db' => 0,
                        'container' => trim($ua['containernumber']),
                        'service' => $ua['service'],
                        'serviceentrydate' => $ua['serviceentrydate'],
                        'status' => $ua['status'] );
                    if( $dev_mode ) $this->container->get('logger')->info( '  * Create container['.$containerindex.'] number_in_list=1 and number_without_more='.($withoutmore?1:0) );
                }

            } else { // There is no containernumber
                if( $ua['boxnumber'] != null && strlen(trim($ua['boxnumber'])) > 0 ){ // There is a box number
                    $withoutmore = ( $ua['documentnumber'] == null || strlen(trim($ua['documentnumber'])) <= 0 );

                    $boxindex = ''.trim($ua['boxnumber']).'|'.$ua['service'].'|'.$ua['serviceentrydate'];

                    if( array_key_exists( $boxindex, $listBoxAnalysis ) ) {
                        $listBoxAnalysis[$boxindex]['number_in_list']++;
                        if( $withoutmore )
                            $listBoxAnalysis[$boxindex]['number_without_more']++;
                        if( $dev_mode ) $this->container->get('logger')->info( '  * Increase box['.$boxindex.'] number_in_list '
                            .($withoutmore?'and number_without_more':''));
                    } else {
                        $listBoxAnalysis[$boxindex] = array(
                            'number_in_list' => 1,
                            'number_without_more' => ($withoutmore?1:0),
                            'number_in_db' => 0,
                            'number_without_more_in_db' => 0,
                            'box' => trim($ua['boxnumber']),
                            'service' => $ua['service'],
                            'serviceentrydate' => $ua['serviceentrydate'],
                            'status' => $ua['status'] );
                        if( $dev_mode ) $this->container->get('logger')->info( '  * Create box['.$boxindex.'] number_in_list=1 and number_without_more='.($withoutmore?1:0) );
                    }

                } else { // There is no box number
                    if( $ua['documentnumber'] != null && strlen(trim($ua['documentnumber'])) > 0 ){ // There is a document number
                        if( $dev_mode ) $this->container->get('logger')->info( '  * Nothing to count it is only a document' );
                    } else { // There is no document number
                        $listErrors[] = $ua['id'];
                        if( $dev_mode ) $this->container->get('logger')->info( '  */!\ Something went wrong we have no number !' );
                    }
                }
            }
        }

        // For each line in arrays, we have to determine if there is more uas with same 'index' in database, and if so if they are well described
        // update number_in_db and number_without_more_in_db of each line
        if( $dev_mode ) $this->container->get('logger')->info( '> Analysing ContainerList with DB' );
        foreach( $listContainerAnalysis as $key => &$container ){
            if( $dev_mode ) $this->container->get('logger')->info( ' > Searching info in DB for container '.$key );
            $uas = $this->getDoctrine()
                ->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->getAllArchivesContainerServiceDate( $container['container'], $container['service'], $container['serviceentrydate'] );

            foreach( $uas as $ua ){
                if( $dev_mode ) $this->container->get('logger')->info( '  * Found and analyse archive id='.$ua['id'].' | C='.$ua['containernumber'].' | B='.$ua['boxnumber'].' | D='.$ua['documentnumber'] );

                $withoutmore = (( $ua['boxnumber'] == null || strlen(trim($ua['boxnumber'])) <= 0 )
                    && ( $ua['documentnumber'] == null || strlen(trim($ua['documentnumber'])) <= 0 ));

                $container['number_in_db']++;
                if( $withoutmore )
                    $container['number_without_more_in_db']++;
            }
            if( $dev_mode ) $this->container->get('logger')->info( '  * Result is: number_in_list='.$container['number_in_list']
                .' ,number_without_more='.$container['number_without_more']
                .' ,number_in_db='.$container['number_in_db']
                .' ,number_without_more_in_db='.$container['number_without_more_in_db'] );
        }

        if( $dev_mode ) $this->container->get('logger')->info( '> Analysing BoxList with DB' );
        foreach( $listBoxAnalysis as $key => &$box ){
            if( $dev_mode ) $this->container->get('logger')->info( ' > Searching info in DB for box '.$key );
            $uas = $this->getDoctrine()
                ->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->getAllArchivesBoxServiceDate( $box['box'], $box['service'], $box['serviceentrydate'] );

            foreach( $uas as $ua ){
                if( $dev_mode ) $this->container->get('logger')->info( '  * Found and analyse archive id='.$ua['id'].' | C='.$ua['containernumber'].' | B='.$ua['boxnumber'].' | D='.$ua['documentnumber'] );
                $withoutmore = ( $ua['documentnumber'] == null || strlen(trim($ua['documentnumber'])) <= 0 );

                $box['number_in_db']++;
                if( $withoutmore )
                    $box['number_without_more_in_db']++;
            }
            if( $dev_mode ) $this->container->get('logger')->info( '  * Result is: number_in_list='.$box['number_in_list']
                .' ,number_without_more='.$box['number_without_more']
                .' ,number_in_db='.$box['number_in_db']
                .' ,number_without_more_in_db='.$box['number_without_more_in_db'] );
        }

        // For each case (ua_state), ua_where, ua_what, the test is specific, and the response depends on this test result
        if( $dev_mode ) $this->container->get('logger')->info( '> Analysing results depending on what called us' );
        $response = true;
        if( $uastate == IDPConstants::UASTATE_MANAGEUSERWANTS || $uastate == IDPConstants::UASTATE_MANAGEPROVIDER || $uastate == IDPConstants::UASTATE_CLOSEUSERWANTS )
            switch( $uawhat ){
                case IDPConstants::UAWHAT_TRANSFER:
                case IDPConstants::UAWHAT_DESTROY:
                    if( $dev_mode ) $this->container->get('logger')->info( ' > TRANSFER or DESTROY ==> Mode 1' );
                    if( !$this->verifyContainerAnalysisList_mode1( $listContainerAnalysis )
                        || !$this->verifyBoxAnalysisList_mode1( $listBoxAnalysis ) )
                        $response = false;
                    break;
                case IDPConstants::UAWHAT_CONSULT:
                case IDPConstants::UAWHAT_RETURN:
                case IDPConstants::UAWHAT_EXIT:
                    if( $dev_mode ) $this->container->get('logger')->info( ' > DELIVER or RETURN or EXIT ==> Mode 2' );
                    if( !$this->verifyContainerAnalysisList_mode2( $listContainerAnalysis )
                        || !$this->verifyBoxAnalysisList_mode2( $listBoxAnalysis ) )
                        $response = false;
                    break;

                case IDPConstants::UAWHAT_RELOC: // Test depends on archive (in list) Status
                    if( $dev_mode ) $this->container->get('logger')->info( ' > RELOC ==> Mode 3' );
                    if( !$this->verifyContainerAnalysisList_mode3( $listContainerAnalysis )
                        || !$this->verifyBoxAnalysisList_mode3( $listBoxAnalysis ) )
                        $response = false;
                    break;
            }

        // Just for debugging view purpose
        //return null; // Just for debug purpose

        return $this->jsonResponse( array('message' => $response?'OK':'NOK'), 200 );
    }

    // All container must have same amount of number_in_list than number_in_db
    private function verifyContainerAnalysisList_mode1( $containerAnalysisList ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '> Mode 1 Container Analysis' );

        foreach( $containerAnalysisList as $key => $container ){
            if( $container['number_in_list'] != $container['number_in_db'] ) {
                if( $dev_mode ) $this->container->get('logger')->info( ' > container ['.$key.'] in error: list='.$container['number_in_list'].' db='.$container['number_in_db'] );
                return false;
            }
        }
        if( $dev_mode ) $this->container->get('logger')->info( ' > OK with container analysis' );
        return true;
    }

    // All box must have same amount of number_in_list than number_in_db
    private function verifyBoxAnalysisList_mode1( $boxAnalysisList ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '> Mode 1 Box Analysis' );

        foreach( $boxAnalysisList as $key => $box ){
            if( $box['number_in_list'] != $box['number_in_db'] ) {
                if( $dev_mode ) $this->container->get('logger')->info( ' > box ['.$key.'] in error: list='.$box['number_in_list'].' db='.$box['number_in_db'] );
                return false;
            }
        }
        if( $dev_mode ) $this->container->get('logger')->info( ' > OK with box analysis' );
        return true;
    }

    // All container must have same amount of number_in_list than number_in_db only if number_without_more_in_db > 0
    private function verifyContainerAnalysisList_mode2( $containerAnalysisList ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '> Mode 2 Container Analysis' );

        foreach( $containerAnalysisList as $key => $container ){
            if( $container['number_without_more_in_db'] > 0 && ( $container['number_in_list'] != $container['number_in_db'] ) ) {
                if( $dev_mode ) $this->container->get('logger')->info( ' > container ['.$key.'] in error: list='.$container['number_in_list'].' db='.$container['number_in_db'].' and without_more_in_db='.$container['number_without_more_in_db'] );
                return false;
            }
        }
        if( $dev_mode ) $this->container->get('logger')->info( ' > OK with container analysis' );
        return true;
    }

    // All box must have same amount of number_in_list than number_in_db only if number_without_more_in_db > 0
    private function verifyBoxAnalysisList_mode2( $boxAnalysisList ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '> Mode 2 Box Analysis' );

        foreach( $boxAnalysisList as $key => $box ){
            if( $box['number_without_more_in_db'] > 0 && ( $box['number_in_list'] != $box['number_in_db'] ) ) {
                if( $dev_mode ) $this->container->get('logger')->info( ' > box ['.$key.'] in error: list='.$box['number_in_list'].' db='.$box['number_in_db'].' and without_more_in_db='.$box['number_without_more_in_db'] );
                return false;
            }
        }
        if( $dev_mode ) $this->container->get('logger')->info( ' > OK with box analysis' );
        return true;
    }

    // test with mode1 like if status is GRLIDAP or GRLINTDAP, mode2 otherwise
    private function verifyContainerAnalysisList_mode3( $containerAnalysisList ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '> Mode 3 Container Analysis' );

        foreach( $containerAnalysisList as $key => $container ){
            if( $container['status'] == 'GRLIDAP' || $container['status'] == 'GRLINTDAP' ){
                if( $container['number_in_list'] != $container['number_in_db'] ) {
                    if( $dev_mode ) $this->container->get('logger')->info( ' > container ['.$key.'] in error: list='.$container['number_in_list'].' db='.$container['number_in_db'].' and status is '.$container['status'] );
                    return false;
                }
            } else
                if( $container['number_without_more_in_db'] > 0 && ( $container['number_in_list'] != $container['number_in_db'] ) ) {
                    if( $dev_mode ) $this->container->get('logger')->info( ' > container ['.$key.'] in error: list='.$container['number_in_list'].' db='.$container['number_in_db'].' and without_more_in_db='.$container['number_without_more_in_db']. ' and status is '.$container['status'] );
                    return false;
                }
        }
        if( $dev_mode ) $this->container->get('logger')->info( ' > OK with container analysis' );
        return true;
    }

    // test with mode1 like if status is GRLIDAP or GRLINTDAP, mode2 otherwise
    private function verifyBoxAnalysisList_mode3( $boxAnalysisList ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '> Mode 3 Box Analysis' );

        foreach( $boxAnalysisList as $key => $box ){
            if( $box['status'] == 'GRLIDAP' || $box['status'] == 'GRLINTDAP' ){
                if( $box['number_in_list'] != $box['number_in_db'] ) {
                    if( $dev_mode ) $this->container->get('logger')->info( ' > box ['.$key.'] in error: list='.$box['number_in_list'].' db='.$box['number_in_db'].' and status is '.$box['status'] );
                    return false;
                }
            } else
                if( $box['number_without_more_in_db'] > 0 && ( $box['number_in_list'] != $box['number_in_db'] ) ) {
                    if( $dev_mode ) $this->container->get('logger')->info( ' > box ['.$key.'] in error: list='.$box['number_in_list'].' db='.$box['number_in_db'].' and without_more_in_db='.$box['number_without_more_in_db']. ' and status is '.$box['status'] );
                    return false;
                }
        }
        if( $dev_mode ) $this->container->get('logger')->info( ' > OK with box analysis' );
        return true;
    }







    //-----------------------------------------------------------------------------------------------------------------
    // E#140: Container Box Lock Verification
    public function getAllowedProvidersAction( Request $request )
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged()) {
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00113 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if ($dev_mode) $this->container->get('logger')->info('> Begin getAllowedProvidersAction');

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;

        if ($parameters->has('idslist'))
            $tempidslist = $parameters->get('idslist');
        else
            return $this->jsonResponse(array('message' => 'Une erreur système est survenue : E-10113'), 400);
        $idslist = explode( ',', $tempidslist );
        if ($dev_mode) $this->container->get('logger')->info(' - UAs selected: '. json_encode($idslist));

        $bFirst = true;
        $tempAllowedProviders = [];
        foreach( $idslist as $uaID ){
            if ($dev_mode) $this->container->get('logger')->info(' - Analyse UA '. $uaID );
            $archive = $this->getDoctrine()
                ->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->find( $uaID );
            if( $bFirst ){
                // For first archive, construct an array of allowed providers (based on service)
                $bFirst = false;
                foreach( $archive->getService()->getProviders() as $provider ){
                    $tempAllowedProviders[$provider->getId()] = $provider;
                }
                if ($dev_mode) $this->container->get('logger')->info(' - First one, so copy all providers :' . json_encode($tempAllowedProviders) );
            } else {
                // For the other ones, make a temp list of providers allowed (based on service) and check if it match, otherwise remove provider
                $temp = [];
                if ($dev_mode) $this->container->get('logger')->info(' - Not first one, get all providers id for this one' );
                foreach( $archive->getService()->getProviders() as $provider )
                    $temp[]=''.$provider->getId();
                if ($dev_mode) $this->container->get('logger')->info(' - get : '.json_encode($temp) );

                foreach( $tempAllowedProviders as $key => $allowedProvider ){
                    if ($dev_mode) $this->container->get('logger')->info(' - Analyse key: '.$key.' => provider: '.$allowedProvider->getId() );
                    if( !in_array( $key, $temp ) ) {
                        if ($dev_mode) $this->container->get('logger')->info(' - Not in this UA allowed providers, so remove it from array' );
                        unset($tempAllowedProviders[$key]);
                    }
                }
                if ($dev_mode) $this->container->get('logger')->info(' - After this UA we have new allowed provider array :' . json_encode($tempAllowedProviders) );
            }
        }
        $listAllowedProviders = [];
        foreach ( $tempAllowedProviders as $key => $allowedProvider ){
            $listAllowedProviders[] = [ 'id' => $key, 'name' => $allowedProvider->getLongname(), 'localization_id' => $allowedProvider->getLocalization()->getId() ];
        }

        //return null;
        $return = array( 'commonProviders' => empty($tempAllowedProviders)?null:$listAllowedProviders );
        return $this->jsonResponse( $return );
    }

    //-----------------------------------------------------------------------------------------------------------------
    // E#177: Cancel Verification
    // bs_idp_archivist_json_is_containerbox_verified_cancel
    public function isCancelCoherencyVerifiedAction( Request $request )
    {
        return $this->jsonResponse( array('message' => 'OK'), 200 );
    }

    //.............................................................................................................
    // B#26 ajax action to set (or unset) unlimited field
    public function updateunlimitedAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00115 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        //$parameters = $request->request;

        if( $parameters->has( 'idlist' ) )
            $idlist = $parameters->get('idlist');
        else
            $idlist = null;
        if( $parameters->has( 'unlimited' ) )
            $unlimited = $parameters->get( 'unlimited' );
        else
            $unlimited = null;
        if( $parameters->has( 'comments' ) )
            $comments = $parameters->get( 'comments' );
        else
            $comments = null;

        if( $idlist == null || $unlimited == null )
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10115'), 400 );

        $em = $this->getDoctrine()->getManager();
        $idslist = explode( ',', $idlist );
        $audit = [];
        foreach( $idslist as $id ){
            $archive = $this->getDoctrine()
                ->getRepository('bsIDPArchiveBundle:IDPArchive')
                ->find( $id );

            if( $archive ) {
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_UNLIMITED,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $archive->getId(),
                    'new_str' => null,
                    'new_int' => $unlimited,
                    'old_str' => null,
                    'old_int' => $archive->getUnlimited(),
                    'complete_ua_id' => null,
                    'object_type' => null // ???
                ];
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_UNLIMITEDCOMMENTS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $archive->getId(),
                    'new_str' => $comments,
                    'new_int' => null,
                    'old_str' => $archive->getUnlimitedcomments(),
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];

                $archive->setUnlimited( $unlimited );
                $archive->setUnlimitedcomments( $comments );

                $em->persist($archive);
            }

        }
        $em->flush();

        $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );

        return $this->jsonResponse( array( 'message', 'Success' ) );
//		}else{
//			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//			return new Response($content, 419);
//		}
    }


    //-----------------------------------------------------------------------------------------------------------------
    // bs_idp_archivist_json_is_basket_verified
    // Depending of uastate, uawhat, uawhere and action verify basket with all exceptions
    // Exception E02: IDPManageContainerBox->isUAMissingInBasket
    // /archive/archivist/json/containerbox_verification
    public function isBasketVerifiedAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00116 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;

        if( $parameters->has( 'idslist' ) ) $tempidslist = $parameters->get('idslist'); else
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10116'), 400 );
        $idslist = json_decode( $tempidslist );

        if( $parameters->has( 'uastate' ) ) $uastate = $parameters->get('uastate'); else $uastate = 0;
        if( $parameters->has( 'uawhat' ) ) $uawhat = $parameters->get('uawhat'); else $uawhat = 0;
        if( $parameters->has( 'uawhere' ) ) $uawhere = $parameters->get('uawhere'); else $uawhere = 0;

        // Debug purpose
        if( $logger ) {
            $logger->info('-> isContainerBoxVerifiedAction ');
            $logger->info(' > $idslist : ' . $tempidslist);
            $logger->info(' > uastate : ' . $uastate);
            $logger->info(' > uawhat : ' . $uawhat);
            $logger->info(' > uawhere : ' . $uawhere);
        }

        $manageContainerBox = new IDPManageContainerBox( );

        if( $logger ) $logger->info( '= Step A : Retreive Datas ======================================================================' );
        if( !$manageContainerBox->retreive_AllDatasFor_IDsInList( $idslist, $this->getDoctrine(), $logger  ) ) {
            return $this->jsonResponse(array('status' => 'NOK', 'message' => 'Database Error', 'test' => 'None'), 200);
        }

        if( $logger ) $logger->info( '= Step B : Get Schema ==========================================================================' );
        // Server Side verification Schema depending on state, what, where and action, but order is still :
        // E05 -> E06 ->  E08 -> E07 -> E02
        $testSchema = $manageContainerBox->get_Exceptions_Schema( $uastate, $uawhat, $uawhere );
        if( $logger ) $logger->info( ' > Test Schema [state:'.$uastate.', what:'.$uawhat.', where:'.$uawhere.'] = '. json_encode($testSchema) );

        if( $logger ) $logger->info( '= Step C : Verify Exception 05 =================================================================' );
        // Test E05, is not locking test, continue but keep result
        $result05 = 'OK';
        $message05 = null;
        if( in_array( 'E05', $testSchema ) ){
            if( $manageContainerBox->is_OneUAIdentification_Missing( $logger ) ){
                $message05 = $manageContainerBox->get_Exception05_ErrorMessage( $uastate, $uawhat, $uawhere );
                $result05 = 'NOK';
            }
        }
        //if( $logger ) $logger->info( ' > Test 05 result : '. $result05 . ' => '. $message05 );
        //return null;

        if( $logger ) $logger->info( '= Step D : Create ContainerBox Struct ==========================================================' );

        $listStatus = $manageContainerBox->get_Exceptions_Perimeter( $uastate, $uawhat, $uawhere );
        $manageContainerBox->create_ContainerBox_Structure( $idslist, $listStatus, $this->getDoctrine(), $logger );

        // Test E06
        if( in_array( 'E06', $testSchema ) ) {
            if( $logger ) $logger->info( '= Step E : Verify Exception 06 =============================================================' );
            if( $manageContainerBox->is_OneUAIdentification_Wrong( $logger ) ){
                $message = $manageContainerBox->get_Exception06_ErrorMessage( $uastate, $uawhat, $uawhere );
                //return null;
                return $this->jsonResponse( array('status' => 'NOK', 'message' => $message, 'test' => 'E06', 'result05' => $result05, 'message05' => $message05 ), 200 );
            }
        }

        // Test E07
        if( in_array( 'E07', $testSchema ) ) {
            if( $logger ) $logger->info( '= Step F : Verify Exception 07 =============================================================' );
            if( $manageContainerBox->is_OneUAMissing_FullPreviousDemand( $logger ) ){
                $message = $manageContainerBox->get_Exception07_ErrorMessage( $uastate, $uawhat, $uawhere );
                return $this->jsonResponse( array('status' => 'NOK', 'message' => $message, 'test' => 'E07', 'result05' => $result05, 'message05' => $message05 ), 200 );
            }
        }

        // Test E08
        if( in_array( 'E08', $testSchema ) ) {
            if( $logger ) $logger->info( '= Step G : Verify Exception 08 =============================================================' );
            if( $manageContainerBox->is_OneUAMissing_InOrderedDemand( $logger ) ){
                $message = $manageContainerBox->get_Exception08_ErrorMessage( $uastate, $uawhat, $uawhere );
                return $this->jsonResponse( array('status' => 'NOK', 'message' => $message, 'test' => 'E08', 'result05' => $result05, 'message05' => $message05 ), 200 );
            }
        }

        // Test E02
        // In some case, Test02 should apply only on part of basket, so reconstruct containerBox in those cases
        if( in_array( 'E02', $testSchema ) ) {
            if( $logger ) $logger->info( '= Step H : Verify Exception 02 =============================================================' );
            if(( $uastate == IDPConstants::UASTATE_MANAGEUSERWANTS && $uawhat == IDPConstants::UAWHAT_DESTROY )||
                ( $uastate == IDPConstants::UASTATE_CLOSEUSERWANTS && $uawhat == IDPConstants::UAWHAT_DESTROY )||
                ( $uawhat == IDPConstants::UAWHAT_RELOC )) {

                if( $logger ) $logger->info( '- Step H1 : Restrict Exception Perimeter Due to Statuses -----------' );

                $keepStatus = $manageContainerBox->getKeepStatusForBasketException02( $uastate, $uawhat, $uawhere );
                $restrictedIdsList = $manageContainerBox->keepOnlyTheseInBasket( $idslist, $keepStatus );
                if( $restrictedIdsList == null )
                    return $this->jsonResponse( array( 'status' => 'OK', 'message' => 'Basket verified', 'test' => 'E02', 'result05' => $result05, 'message05' => $message05 ), 200 );
                $manageContainerBox->resetDatas( );

                if( $logger ) $logger->info( '- Step H2 : Get Back new Datas -----------' );

                if( !$manageContainerBox->retreive_AllDatasFor_IDsInList( $restrictedIdsList, $this->getDoctrine(), $logger  ) )
                    return $this->jsonResponse( array( 'status' => 'NOK', 'message' => 'Database Error', 'test' => 'E02' ), 200 );

                if( $logger ) $logger->info( '- Step H2 : Reconstruct Container Box -----------' );

                $listStatus = $manageContainerBox->get_Exception02_Perimeter( $uastate, $uawhat, $uawhere );
                $manageContainerBox->create_ContainerBox_Structure($restrictedIdsList, $listStatus, $this->getDoctrine(), $logger);

                if( $logger ) $logger->info( '- Step H3 : ContainerBox after reconstruction : '.json_encode($manageContainerBox->containerBoxStruct) );
            }

            if( $logger ) $logger->info( '- Step H4 : Test -----------' );

            if( $manageContainerBox->is_OneUA_Missing_InBasket( $logger ) ){
                $message = $manageContainerBox->get_Exception02_ErrorMessage( $uastate, $uawhat, $uawhere );
                // return null;
                return $this->jsonResponse( array('status' => 'NOK', 'message' => $message, 'test' => 'E02', 'result05' => $result05, 'message05' => $message05 ), 200 );
            }
        }

        //return null;

        return $this->jsonResponse( array('status' => 'OK', 'message' => 'Basket verified', 'test' => 'All', 'result05' => $result05, 'message05' => $message05 ), 200 );
    }

    //-----------------------------------------------------------------------------------------------------------------
    // bs_idp_archivist_provider_connector_lock_basket
    // This function is called to lock all UAs selected in Basket (called in lock_Basket of ManageProvider state
    // Set ContainerAsked (or BoxAsked) to 1 (or 3 if it is already at 2)
    public function lockBasketAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00117 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;

        //.........................................................................................................
        // Get Parameters
        $parameters = $request->query;
        if( $parameters->has( 'ids' ) ) $ids = json_decode( $parameters->get('ids'));
        else return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10117'), 400 );

        //.........................................................................................................
        // Initialize manageContainerBox
        $manageContainerBox = new IDPManageContainerBox();
        if( !$manageContainerBox->retreive_AllDatasFor_IDsInList($ids, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30117'), 417 );
        if( !$manageContainerBox->create_ContainerBox_Structure($ids, null, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30217'), 417 );

        //.........................................................................................................
        // Do the job
        if( !$manageContainerBox->lockUnlock_FullContainerBox_InBasket( true, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30317'), 417 );


        return $this->jsonResponse( array('message' => 'OK'), 200 );
    }

    //-----------------------------------------------------------------------------------------------------------------
    // bs_idp_archivist_provider_connector_manage_optimization_choices
    // This function is called to remeber the order asked in optimization (eventually unset lock_basket if it has been unselected in opti)
    // called in manage_OptimizationChoices of ManageProvider state
    public function manageOptimizationChoicesAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00118 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;
        if($logger) $logger->info('--- manageOptimizationChoicesAction ---');

        //.........................................................................................................
        // Get Parameters
        $parameters = $request->query;
        if( $parameters->has( 'ids' ) ) $ids = json_decode( $parameters->get('ids') );
        else return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10118'), 400 );
        if( $parameters->has( 'basketids' ) ) $basketids = json_decode( $parameters->get('basketids'));
        else return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10218'), 400 );
        if( $parameters->has( 'objects' ) ) $objects = json_decode( $parameters->get('objects'));
        else return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10318'), 400 );

        if($logger) $logger->info('--- ids: '.json_encode($ids));
        if($logger) $logger->info('--- basketids: '.json_encode($basketids));
        if($logger) $logger->info('--- objects: '.json_encode($objects));

        //.........................................................................................................
        // Initialize manageContainerBox with old basket
        $manageContainerBox = new IDPManageContainerBox();
        if( !$manageContainerBox->retreive_AllDatasFor_IDsInList($basketids, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30118'), 417 );
        if( !$manageContainerBox->create_ContainerBox_Structure($basketids, null, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30218'), 417 );

        //.........................................................................................................
        // First step manage lost full container from basket
        if( !$manageContainerBox->manage_Lost_FullContainerBox($ids, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30318'), 417 );

        //........................................................................................................
        // Reset datas, and redo manageContainerBox but with ids this time
        $manageContainerBox->resetDatas();
        if( !$manageContainerBox->retreive_AllDatasFor_IDsInList($ids, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30418'), 417 );
        if( !$manageContainerBox->create_ContainerBox_Structure($ids, null, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30518'), 417 );

        //.........................................................................................................
        // Second step manage new full container from optimization
        if( !$manageContainerBox->manageUnmanage_FullContainerBox_InOptimization( true, $objects, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30318'), 417 );

        // return null;
        return $this->jsonResponse( array('message' => 'OK'), 200 );
    }

    //-----------------------------------------------------------------------------------------------------------------
    // bs_idp_archivist_provider_connector_unlock_basket
    // This function is called to unlock all UAs selected in Basket (when an error occured during normal process)
    public function unlockBasketAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00119'), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;

        //.........................................................................................................
        // Get Parameters
        $parameters = $request->query;
        if( $parameters->has( 'ids' ) ) $ids = json_decode( $parameters->get('ids'));
        else return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10119'), 400 );

        $errorCode = $this->unlockBasket( $ids, $logger );
        if( $errorCode != null )
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : '.$errorCode), 417 );

        return $this->jsonResponse( array('message' => 'OK'), 200 );
    }

    //-----------------------------------------------------------------------------------------------------------------
    // This function is called to unlock all UAs selected in Basket (when an error occured during normal process) or at close
    private function unlockBasket( $ids, $logger ){
	    if( empty($ids) )
	        return 'E-10120';

        //.........................................................................................................
        // Initialize manageContainerBox
        $manageContainerBox = new IDPManageContainerBox();
        if( !$manageContainerBox->retreive_AllDatasFor_IDsInList($ids, $this->getDoctrine(), $logger))
            return 'E-30120';
        if( !$manageContainerBox->create_ContainerBox_Structure($ids, null, $this->getDoctrine(), $logger))
            return 'E-30220';

        //.........................................................................................................
        // Do the job
        if( !$manageContainerBox->lockUnlock_FullContainerBox_InBasket( false, $this->getDoctrine(), $logger))
            return 'E-30319';

        return null;
    }

    //-----------------------------------------------------------------------------------------------------------------
    // bs_idp_archivist_provider_connector_unmanage_optimization_choices
    // This function is called to deconstruct ordered remembers made in manage_optimization_choices in case of errors during process
    public function unmanageOptimizationChoicesAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-00118 '), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;

        //.........................................................................................................
        // Get Parameters
        $parameters = $request->query;
        if( $parameters->has( 'ids' ) ) $ids = json_decode( $parameters->get('ids') );
        else return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10118'), 400 );
        if( $parameters->has( 'objects' ) ) $objects = json_decode( $parameters->get('objects'));
        else return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-10218'), 400 );

        //........................................................................................................
        $manageContainerBox = new IDPManageContainerBox();
        if( !$manageContainerBox->retreive_AllDatasFor_IDsInList($ids, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30418'), 417 );
        if( !$manageContainerBox->create_ContainerBox_Structure($ids, null, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30518'), 417 );

        //.........................................................................................................
        // Second step manage new full container from optimization
        if( !$manageContainerBox->manageUnmanage_FullContainerBox_InOptimization( false, $objects, $this->getDoctrine(), $logger))
            return $this->jsonResponse( array('message' => 'Une erreur système est survenue : E-30318'), 417 );

        return $this->jsonResponse( array('message' => 'OK'), 200 );
    }
}
