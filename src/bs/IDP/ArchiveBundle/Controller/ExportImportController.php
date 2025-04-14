<?php

namespace bs\IDP\ArchiveBundle\Controller;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\ArchiveBundle\Entity\IDPImport;
use \DateTime;
use Symfony\Component\Process\Process;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\Core\AdminBundle\Security\IDPArchimageRights;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;

use bs\IDP\ArchiveBundle\Translation\bsTranslationIDsArchive;
use bs\Core\TranslationBundle\Translation\bsTranslationIDsCommon;

use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalSettings;

use bs\IDP\ArchiveBundle\Common\IDPExportTableCommon;

use \xlswriter\XLSXWriter;

class ExportImportController extends Controller
{   //    /archive/exportimport/exportall
	public function doExportAllAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_EXPORT_ALL][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 2 )); }

        $exportType = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPGlobalSettings' )
            ->getExportType();

        $exportCommon = new IDPExportTableCommon();

        // Construct Query with these parameters
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
            ->getExportQuery( $bsUserSession, 0, null, null, null, null, null, null, null ); // $bsUserSession, $fct, $search, $what, $where, $how, $with, state, filter_provider

        // First get back datas in an iterate object
        $iterableResult = $query->iterate();

		switch( $exportType ){
            case IDPGlobalSettings::EXPORT_TYPE_IDP:

                // Second open an handle in php memory to write result into
                $handle = fopen( 'php://memory', 'r+' );
                $header = array();

                // Third, write into $handle csv first line and rows iterably
                fputcsv( $handle, $exportCommon->flatenColumnTitle( null ) );
                while( false != ( $row = $iterableResult->next() )){

                    $rowArray = $exportCommon->flatenArchiveObject( $row[0], null );

                    fputcsv( $handle, $rowArray );
                    $em->detach( $row[0] );
                }

                // Four, send the stream
                rewind( $handle );
                $content = stream_get_contents( $handle );
                return new Response( $content, 200, array (
                    'Content-Type' => 'application/force-download',
                    'Content-Disposition' => 'attachment; filename="export.idp"',
                    'Cache-Control: must-revalidate',
                    'Pragma: public' ));
                break;

            case IDPGlobalSettings::EXPORT_TYPE_XLS:
                $xlsWriter = new \XLSXWriter();
                $xlsWriter->setAuthor('Archimage');

                // Titles
                $xlsWriter->writeSheetRow('Archimage', $exportCommon->flatenColumnTitle( null ) );
                // Rows
                while( false != ( $row = $iterableResult->next() )){

                    $rowArray = $exportCommon->flatenArchiveObject( $row[0], null );

                    $xlsWriter->writeSheetRow('Archimage', $rowArray);

                    $em->detach( $row[0] );
                }

                $content = $xlsWriter->writeToString();
                return new Response( $content, 200, array (
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Content-Disposition' => 'attachment; filename="export.xlsx"',
                    'Content-Transfer-Encoding: binary',
                    'Cache-Control: must-revalidate',
                    'Pragma: public' ));
                break;
        }

        return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
            'error_redirect' => 'bs_idp_dashboard_homepage',
            'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
            'error_title' => null,
            'error_message' => null
        ));
	}

	// Export elements to excel file in http stream
	public function exportAction( Request $request )
	{
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 2 )); }

        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if ($dev_mode) $this->container->get('logger')->info('-> Begin exportAction - Retreive parameters');

		// GET
		$parameters = $request->query;
		// POST
		//$parameters = $request->request;

		// Get parameters of export
		if( $parameters->has( 'xpfct' ) )
			$xpfct = json_decode($parameters->get('xpfct'));
		else {
			// must return error !
			$xpfct = null;
		}
		if( $parameters->has( 'listColumn' ) )
            $listColumn = (array)json_decode( $parameters->get( 'listColumn' ) ) ;
		else
            $listColumn = null;
		if( $parameters->has( 'xpsearch' ) )
			$xpsearch = (array)json_decode( $parameters->get( 'xpsearch' ) );
		else
			$xpsearch = null;

		if( $parameters->has( 'xpwhat' ) )
			$xpwhat = json_decode( $parameters->get( 'xpwhat' ) );
		else
			$xpwhat = null;
		if( $parameters->has( 'xpwhere' ) )
			$xpwhere = json_decode( $parameters->get( 'xpwhere' ) );
		else
			$xpwhere = null;
		if( $parameters->has( 'xphow' ) )
			$xphow = json_decode( $parameters->get( 'xphow' ) );
		else
			$xphow = null;
		if( $parameters->has( 'xpwith' ) )
			$xpwith = json_decode( $parameters->get( 'xpwith' ) );
		else
			$xpwith = null;

        if( $parameters->has( 'xpstate' ) )
            $xpstate = json_decode( $parameters->get('xpstate'));
        else
            $xpstate = null;
        if( $parameters->has( 'filter_provider'))
            $filterprovider = json_decode( $parameters->get('filter_provider'));
        else
            $filterprovider = null;
        if( $parameters->has( 'listId' ) )
            $listId = (array)json_decode( $parameters->get('listId') );
        else
            $listId = null; // All UAs

        if ($dev_mode) $this->container->get('logger')->info(' - xpfct: '.json_encode($xpfct));
        if ($dev_mode) $this->container->get('logger')->info(' - listColumn: '.json_encode($listColumn));
        if ($dev_mode) $this->container->get('logger')->info(' - xpsearch: '.json_encode($xpsearch));
        if ($dev_mode) $this->container->get('logger')->info(' - xpwhat: '.json_encode($xpwhat));
        if ($dev_mode) $this->container->get('logger')->info(' - xpwhere: '.json_encode($xpwhere));
        if ($dev_mode) $this->container->get('logger')->info(' - xphow: '.json_encode($xphow));
        if ($dev_mode) $this->container->get('logger')->info(' - xpwith: '.json_encode($xpwith));
        if ($dev_mode) $this->container->get('logger')->info(' - xpstate: '.json_encode($xpstate));
        if ($dev_mode) $this->container->get('logger')->info(' - filter_provider: '.json_encode($filterprovider));
        if ($dev_mode) $this->container->get('logger')->info(' - listId: '.json_encode($listId));

        $exportType = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPGlobalSettings' )
            ->getExportType();

        $exportCommon = new IDPExportTableCommon();

        //return null;

        return $exportCommon->makeExportFile(
            $this->getDoctrine(),
            $listId,
            $bsUserSession->getUserServices(),
            $xpfct, $xpsearch, $xpwhat, $xpwhere, $xphow, $xpwith, $xpstate, $filterprovider, $listColumn,
            $exportType,
            IDPExportTableCommon::EXPORT_STREAM
        );

    }

    // Export elements to excel file in offline mode, aka in a file on server with can be downloaded via the user space
    public function exportOfflineAction( Request $request ){
	    // TODO:
        $output = array('message' => 'Archimage');

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
            return new JsonResponse( $output, 403 );
        if( $bsUserSession->getUser()->getChangepass() )
            return new JsonResponse( $output, 403 );

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ) {
            $output['message'] = "Un rapprochement est en cours de calcul, il n'est pas possible d'effectuer une impression pendant cette action !";
            return new JsonResponse($output, 409);
        }

        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;

        // Verification if not already in export action
        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );
        if( $userFilesResume['in_progress'] ) {
            $output['message'] = "Une autre impression ou un autre export est en cours, un(e) seul(e) impression/export à la fois est autorisé(e)!";
            return new JsonResponse($output, 409);
        }
        if( $userFilesResume['nb_files'] >= 10 ){
            $output['message'] = "Vous avez déjà 10 fichiers dans votre espace, libérez de la place avant de demander une génération supplémentaire !";
            return new JsonResponse($output, 409);
        }

        // $_GET parameters
        $parameters = $request->query;
        // $_POST parameters
        // $parameters = $request->request;

        if( $parameters->has( 'listId' ) )
            $listId = (array)json_decode( $parameters->get('listId') );
        else
            $listId = null;
        if( $parameters->has( 'listColumn' ) )
            $listColumn = (array)json_decode( $parameters->get('listColumn') );
        else
            $listColumn = null;
        if( $parameters->has( 'xpsearch' ) )
            $xpsearch = (array)json_decode( $parameters->get('xpsearch') );
        else
            $xpsearch = null;
        if( $parameters->has( 'whereAmI' ) )
            $whereAmI = $parameters->get('whereAmI') ;
        else
            $whereAmI = null;

        // Launch command and send response
        // php bin/console app:export-table-offline userId all listId listColumn format xpsearch whereAmI debug

        $processCmd = "php ../bin/console app:export-table-offline ".
            $bsUserSession->getUserId()." 0 ".
            "[". implode(";", $listId ) ."] ".
            "\"[".implode(";",$listColumn)."]\" ".
            " 0 ".
            "\"[".implode( ";", $xpsearch )."]\" ".
            $whereAmI;    // true false mng

        if( $logger ) $logger->info( $processCmd );

        $process = new Process( $processCmd );
        $process->start();

        sleep(1); // wait for process to start

        //check for errors and send them
        if (!$process->isRunning())
            if (!$process->isSuccessful()){
                // $output['uploaded'] = true;
                $output['message'] = "Oops! The process fininished with an error: ".$process->getExitCode();

                return new JsonResponse( $output, 403 );
            }

        //return null;

        return new JsonResponse( $output );
    }

    // Export elements to excel file in offline mode, aka in a file on server with can be downloaded via the user space
    public function exportAllOfflineAction( Request $request ){
        // TODO:
        $output = array('message' => 'Archimage');

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
            return new JsonResponse( $output, 403 );
        if( $bsUserSession->getUser()->getChangepass() )
            return new JsonResponse( $output, 403 );

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ) {
            $output['message'] = "Un rapprochement est en cours de calcul, il n'est pas possible d'effectuer une impression pendant cette action !";
            return new JsonResponse($output, 409);
        }

        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;

        // Verification if not already in export action
        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );
        if( $userFilesResume['in_progress'] ) {
            $output['message'] = "Une autre impression ou un autre export est en cours, un(e) seul(e) impression/export à la fois est autorisé(e)!";
            return new JsonResponse($output, 409);
        }
        if( $userFilesResume['nb_files'] >= 10 ){
            $output['message'] = "Vous avez déjà 10 fichiers dans votre espace, libérez de la place avant de demander une génération supplémentaire !";
            return new JsonResponse($output, 409);
        }

        // Launch command and send response
        $processCmd = "php ../bin/console app:export-table-offline ".
            $bsUserSession->getUserId()." 1 0 0 0 0 0";

        if( $logger ) $logger->info( $processCmd );

        $process = new Process( $processCmd );
        $process->start();

        sleep(1); // wait for process to start

        //check for errors and send them
        if (!$process->isRunning())
            if (!$process->isSuccessful()){
                // $output['uploaded'] = true;
                $output['message'] = "Oops! The process fininished with an error: ".$process->getExitCode();

                return new JsonResponse( $output, 403 );
            }

        //return null;

        return new JsonResponse( $output );
    }

    // IMPORT


	private function makeImportForm( ){
		// No need for an object, an array works fine
		$model = array(
			'attachment' => null
		);

		$builder = $this->createFormBuilder($model);

		$builder->setAction($this->generateUrl('bs_idp_archive_partialimportdo'));
		$builder->setMethod('POST');

		$builder->add('attachment', FileType::class);

		$builder->add('import', SubmitType::class, array(
			'label' => 'Importer le fichier',
			'attr' => array('class' => 'import'),
		));

		return $builder->getForm();

	}

	public function partialimportscreenAction( Request $request )
	{
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_IMPORT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'export_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $settings = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
			->getArraySettings();
		/*
		$translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_MANAGEUSERWANTSSCREEN, $language );
		*/
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

        $globalStatuses = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' )
            ->findOneBy( array( 'id' => 1 ) );

        $importForm = $this->makeImportForm()->createView();

        return $this->render('bsIDPArchiveBundle:Archivist:partialimportscreen.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'settings' => $settings,
            'headTranslations' => $headTranslations,
            'language' => $language,
            'currentMenu' => 25,
            'importForm' => $importForm,
            'globalStatuses' => $globalStatuses ));
	}

	public function partialimportdoAction( Request $request )
	{

		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_IMPORT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 2 )); }

        // GET
		//$parameters = $request->query;
		// POST
		$parameters = $request->request;

		$importForm = $this->makeImportForm();

		$importForm->handleRequest($request);

		if ($importForm->isValid())
		{
			$model = $importForm->getData();
			$file = $model['attachment']; // The file object is built by the form processor

			if (!$file->isValid())
			{
				$error = sprintf("Max file size %d %d Valid: %d, Error: %d<br />\n",
				$file->getMaxFilesize(), // Returns null?
				$file->getClientSize(),
				$file->isValid(),
				$file->getError());
				return new Response( $error );
			}

			$now = new DateTime();
			$newFileName = sprintf( "%s-%d", str_replace(' ', '', $file->getClientOriginalName()), $now->getTimestamp() );
			$dir = __DIR__.'/../../../../../web/import/archimage/';

			$file->move( $dir, $newFileName );

            $process = new Process( "php ../bin/console app:import-file archimage/$newFileName" );
            $process->start();

            sleep(1); // wait for process to start

            // check for errors and output them through flashbag
            if (!$process->isRunning())
                if (!$process->isSuccessful()){
                    // TODO BETTER
                    $this->get('session')->getFlashBag()->add('error', "Oops! The process fininished with an error:".$process->getExitCodeText());
                    return $this->redirect($this->generateUrl('bs_idp_archive_partialimportscreen' ));
                }

            $globalStatuses = $this->getDoctrine()
                ->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')
                ->findOneBy(array('id'=>1));

            return $this->redirect($this->generateUrl('bs_idp_archive_importtreatmentsee', array( 'id' => $globalStatuses->getCurrentImportId() ) ));
		} else
			return new Response( "import invalid" );

	}


	public function importtreatmentscreenAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_IMPORT][IDPArchimageRights::RIGHT_ID] ) ){
			return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
			    'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $settings = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
			->getArraySettings();
		/*
		   $translations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_MANAGEUSERWANTSSCREEN, $language );
		*/
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

		// List files in import directory
		$dir = __DIR__.'/../../../../../web/import/archimage/';
		$fileList = scandir ( $dir );

        return $this->render('bsIDPArchiveBundle:Archivist:importtreatmentscreen.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'settings' => $settings,
            'headTranslations' => $headTranslations,
            'language' => $language,
            'currentMenu' => 25,
            'fileList' => $fileList ));
	}

	public function importtreatmentdoAction( Request $request ){

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        // GET
		//$parameters = $request->query;
		// POST
		$parameters = $request->request;

		if( $parameters->has( 'filename' ) )
			$filename = $parameters->get( 'filename' );
		else
			return null;

        $process = new Process( "php ../app/console app:import-file archimage/$filename" );
        $process->start();

        sleep(1); // wait for process to start

        // check for errors and output them through flashbag
        if (!$process->isRunning())
            if (!$process->isSuccessful()){
                // TODO BETTER
                $this->get('session')->getFlashBag()->add('error', "Oops! The process fininished with an error:".$process->getExitCodeText());
                return $this->redirect($this->generateUrl('bs_idp_archive_partialimportscreen' ));
            }

        $globalStatuses = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')
            ->findOneBy(array('id'=>1));

        return $this->redirect($this->generateUrl('bs_idp_archive_importtreatmentsee', array( 'id' => $globalStatuses->getCurrentImportId() ) ));

	}


	public function importtreatmentseeAction( Request $request, $id ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_IMPORTS_REPORT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $settings = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
			->getArraySettings();

		$import = $this->getDoctrine()
            ->getRepository('bsIDPArchiveBundle:IDPImport')
            ->findOneBy(array('id'=>$id));

		/*
		   $translations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_MANAGEUSERWANTSSCREEN, $language );
		*/
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

        return $this->render('bsIDPArchiveBundle:Archivist:importtreatmentsee.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'settings' => $settings,
            'headTranslations' => $headTranslations,
            'language' => $language,
            'currentMenu' => 25,
            'import' => $import ));
	}

	public function ajaximporttreatmentsurveyAction( Request $request ){

		// Get filename in request
		$parameters = $request->query;

		if( $parameters->has( 'importid' ) )
			$importid = $parameters->get( 'importid' );
		else
			$importid = null;

		if( !$importid ){
			$ret = array( 'percent' => 0, 'status' => 1, 'messages' => array( 'Error: no importid in query' ));
			$response = new Response(json_encode($ret));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}
		$em = $this->getDoctrine()->getManager();

		$import = $em->getRepository( 'bsIDPArchiveBundle:IDPImport' )
            ->findOneBy( array( 'id' => $importid) );
        if( !$import ){	// nothing new
            $ret = array( 'percent' => null, 'status' => 0, 'messages' => null, 'estimated' => null );
            $response = new Response(json_encode($ret));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

		$messages = $em->getRepository( 'bsIDPArchiveBundle:IDPImportComm')
			->findBy( array( 'import_id' => $importid, 'alreadyRead' => 0 ) );
		if( !$messages ){	// nothing new
			$ret = array( 'percent' => null, 'status' => 0, 'messages' => null, 'estimated' => null );
			$response = new Response(json_encode($ret));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}

		$percent = $import->getProgress();
		$status = $import->getStatus();
		$estimated = $import->getEstimatedEnd();
		if( $estimated && $estimated instanceof DateTime )
		    $estimated = $estimated->format( 'd/m/Y H:m:s' );
		else
		    $estimated = null;
		$texts = array();
		foreach( $messages as $message ){
			if( $message->getMessage() != null )
				array_push( $texts, $message->getMessage() );

    		$message->setAlreadyRead( 1 );

			$em->persist( $message );
			$em->flush();
		}

		$ret = array( 'percent' => $percent, 'status' => $status, 'messages' => $texts, 'estimated' => $estimated );

		$response = new Response(json_encode($ret));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

    public function importsrapportseeAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_IMPORTS_REPORT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $settings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->getArraySettings();

        $imports = $this->getDoctrine()
            ->getRepository('bsIDPArchiveBundle:IDPImport')
            ->getAllSortedByBegin( true );

        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

        $globalStatuses = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' )
            ->findOneBy( array( 'id' => 1 ) );

        return $this->render('bsIDPArchiveBundle:Archivist:importsrapportsee.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'settings' => $settings,
            'headTranslations' => $headTranslations,
            'language' => $language,
            'currentMenu' => 25,
            'imports' => $imports,
            'globalStatuses' => $globalStatuses ));
    }

    public function importrapportfileAction( Request $request, $id ){

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_IMPORTS_REPORT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $import = $this->getDoctrine()
            ->getRepository('bsIDPArchiveBundle:IDPImport')
            ->findOneBy(array('id'=>$id));
        $importcomms = $this->getDoctrine()
            ->getRepository('bsIDPArchiveBundle:IDPImportComm')
            ->findBy(array('import_id'=>$id));

        $content = $this->get('templating')->render(
            'bsIDPArchiveBundle:Archivist:importrapportfile.txt.twig',
            [
                'import' => $import,
                'importcomms' => $importcomms
            ]
        );
        $response = new Response($content , 200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport-import.txt"');

        return $response;
    }

    public function importerrorfileAction( Request $request, $id ){

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_IMPORTS_REPORT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $importcomms = $this->getDoctrine()
            ->getRepository('bsIDPArchiveBundle:IDPImportComm')
            ->findBy(array('import_id'=>$id,'status'=>0));

        // strange but works
        foreach ( $importcomms as $importcomm){
            $importcomm->setRawLine( $this->convertText( $importcomm->getRawLine() ) );
        }

        $content = $this->get('templating')->render(
            'bsIDPArchiveBundle:Archivist:importerrorfile.idp.twig',
            [
                'importcomms' => $importcomms
            ]
        );
        $response = new Response($content , 200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="import-error.idp"');

        return $response;
    }

    public function importcancelAction( Request $request, $id ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_IMPORTS_REPORT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $globalStatuses = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' )
            ->findOneBy( array( 'id' => 1 ) );

        $import = $this->getDoctrine()
            ->getRepository( 'bsIDPArchiveBundle:IDPImport' )
            ->findOneBy( array( 'id' => $id) );

        $em = $this->getDoctrine()->getEntityManager();

        //* 1) adjust global status with in cancel mode
        $globalStatuses->setCancelInProgress( true );
        $import->setStatus( IDPImport::IDP_IMPORT_STATUS_CANCEL_IN_PROGRESS );
        $em->persist($globalStatuses);
        $em->persist($import);
        $em->flush();

        //* 2) remove all archives with import_id = id
        $this->getDoctrine()
            ->getRepository('bsIDPArchiveBundle:IDPArchive' )
            ->removeAllWithImportId( $id );

        //* 3) remove all importcomm lines with import_id = id
        $this->getDoctrine()
            ->getRepository('bsIDPArchiveBundle:IDPImportComm' )
            ->removeAllWithImportId( $id );

        //* 4) modify import
        //* 5) adjust global status with iddle mode
        $import->setStatus( IDPImport::IDP_IMPORT_STATUS_CANCELED );
        $globalStatuses->setCancelInProgress( false );
        $em->persist($import);
        $em->persist($globalStatuses);
        $em->flush();

        return $this->redirect( $this->generateUrl('bs_idp_archive_importsrapportsee') );
    }

    public function importvalidateAction( Request $request, $id ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_IMPORTS_REPORT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $globalStatuses = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' )
            ->findOneBy( array( 'id' => 1 ) );

        $import = $this->getDoctrine()
            ->getRepository( 'bsIDPArchiveBundle:IDPImport' )
            ->findOneBy( array( 'id' => $id) );

        $em = $this->getDoctrine()->getEntityManager();

        //* 1) adjust global status with in cancel mode
        $globalStatuses->setCancelInProgress( true );
        $import->setStatus( IDPImport::IDP_IMPORT_STATUS_DEFINITIVE_VALIDATION );
        $em->persist($globalStatuses);
        $em->persist($import);
        $em->flush();

        //* 2) remove all archives with import_id = id
        $this->getDoctrine()
            ->getRepository('bsIDPArchiveBundle:IDPArchive' )
            ->validateAllWithImportId( $id );

        //* 3) remove all importcomm lines with import_id = id
        $this->getDoctrine()
            ->getRepository('bsIDPArchiveBundle:IDPImportComm' )
            ->removeAllWithImportId( $id );

        //* 5) adjust global status with iddle mode
        $globalStatuses->setCancelInProgress( false );
        $em->persist($globalStatuses);
        $em->flush();

        return $this->redirect( $this->generateUrl('bs_idp_archive_importsrapportsee') );
    }

    private function convertText( $text )
    {
        $convertedText = '';
        //try a self encode / decode (avec multiple test with utf8_encode / decode / iconv / ... and found nothing
        for( $ilen = 0; $ilen < strlen( $text ); $ilen++ )
            switch( $text[$ilen] ){
                case 'é': $convertedText .= 'é'; break;
                case 'è': $convertedText .= 'è'; break;
                case 'ç': $convertedText .= 'ç'; break;
                case 'à': $convertedText .= 'à'; break;
                case '~': $convertedText .= '~'; break;
                case '`': $convertedText .= '`'; break;
                case '€': $convertedText .= '€'; break;
                case 'ä': $convertedText .= 'ä'; break;
                case 'ë': $convertedText .= 'ë'; break;
                case 'ü': $convertedText .= 'ü'; break;
                case 'ï': $convertedText .= 'ï'; break;
                case 'ö': $convertedText .= 'ö'; break;
                case 'â': $convertedText .= 'â'; break;
                case 'ê': $convertedText .= 'ê'; break;
                case 'û': $convertedText .= 'û'; break;
                case 'î': $convertedText .= 'î'; break;
                case 'ô': $convertedText .= 'ô'; break;
                default: $convertedText .= $text[$ilen]; break;
            }
        return $convertedText;
    }
}

