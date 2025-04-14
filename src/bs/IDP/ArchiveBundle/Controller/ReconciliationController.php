<?php
// (c) Archimage - IDP Consulting 2016
// Author: Cyril PATRIGEON - BeSecure Labs
//

namespace bs\IDP\ArchiveBundle\Controller;

use bs\IDP\ArchiveBundle\Entity\IDPReconciliation;
use \DateTime;
use Symfony\Component\Process\Process;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\Core\AdminBundle\Security\IDPArchimageRights;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;

use bs\IDP\ArchiveBundle\Translation\bsTranslationIDsArchive;
use bs\Core\TranslationBundle\Translation\bsTranslationIDsCommon;
use bs\IDP\BackofficeBundle\Translation\bsTranslationIDsBackoffice;

use Symfony\Component\HttpFoundation\JsonResponse;

class ReconciliationController extends Controller
{
    // bs_idp_archive_reconciliation_index
    // /reconciliation/index
    public function indexAction( Request $request )
    {
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ? $this->container->get('logger') : null;

        // User logged verification
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged($request->cookies->get('PHPSESSID')))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if ($bsUserSession->getUser()->getChangepass())
            return $this->redirect($this->generateUrl('bs_core_user_change_mdp_screen'));

        // User Right verification
        if (!$bsUserSession->isUserGotRight(IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RECONCILIATION][IDPArchimageRights::RIGHT_ID])) {
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 )); }

        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));
        // Import in progress verification
        if ($globalStatuses && $globalStatuses->getImportInProgress() ) {
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 3 )); }

        // Switch based on reconciliation
        switch($globalStatuses->getReconciliationInProgress()) {
            case 0: // No reconciliation in progress ==> render index
                // Translation texts
                $language = $bsUserSession->getUserExtension()->getLanguage();
                $headTranslations = $this->getDoctrine()
                    ->getRepository('bsCoreTranslationBundle:bsTranslation')
                    ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

                return $this->render('bsIDPArchiveBundle:Reconciliation:index.html.twig', array(
                    'currentMenu' => 38,
                    'userscale' => $bsUserSession->getUserScale(),
                    'userFilesResume' => $this->getDoctrine()
                        ->getRepository('bsCoreUsersBundle:IDPUserFiles')
                        ->getUserFilesResume( $bsUserSession->getUserId() ),
                    'headTranslations' => $headTranslations ));
                break;
            case IDPReconciliation::UPLOAD_IN_PROGRESS:
            case IDPReconciliation::VERIFICATION_IN_PROGRESS:
            case IDPReconciliation::DATABASE_COPY_IN_PROGRESS:
            case IDPReconciliation::TREATMENT_IN_PROGRESS:
            case IDPReconciliation::RESET_IN_PROGRESS:
            case IDPReconciliation::RESULT_FILE_GENERATION_IN_PROGRESS:
                return $this->redirect($this->generateUrl('bs_idp_archive_reconciliation_status'));
                break;
            case IDPReconciliation::RECONCILIATION_READY:
                return $this->redirect($this->generateUrl('bs_idp_archive_reconciliation_result'));
                break;
            default: // Seems to be an error ==> render error
                return $this->redirect($this->generateUrl('bs_idp_archive_reconciliation_error'));
                break;
        }
    }

    // bs_idp_archive_reconciliation_status
    // /reconciliation/status
    public function statusAction( Request $request )
    {
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ? $this->container->get('logger') : null;

        // User logged verification
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged($request->cookies->get('PHPSESSID')))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if ($bsUserSession->getUser()->getChangepass())
            return $this->redirect($this->generateUrl('bs_core_user_change_mdp_screen'));

        // User Right verification
        if (!$bsUserSession->isUserGotRight(IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RECONCILIATION][IDPArchimageRights::RIGHT_ID])) {
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 )); }

        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));
        // Import in progress verification
        if ($globalStatuses && $globalStatuses->getImportInProgress() ) {
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 3 )); }

        // Verify we are in the appropriate reconciliation status to proceed
        if( !in_array( $globalStatuses->getReconciliationInProgress(), [IDPReconciliation::UPLOAD_IN_PROGRESS,
            IDPReconciliation::VERIFICATION_IN_PROGRESS, IDPReconciliation::DATABASE_COPY_IN_PROGRESS,
            IDPReconciliation::TREATMENT_IN_PROGRESS, IDPReconciliation::RESET_IN_PROGRESS,
            IDPReconciliation::RESULT_FILE_GENERATION_IN_PROGRESS ] ) )
            return $this->redirect($this->generateUrl('bs_idp_archive_reconciliation_index'));

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        // Translation texts
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        return $this->render('bsIDPArchiveBundle:Reconciliation:status.html.twig', array(
            'currentMenu' => 38,
            'userscale' => $bsUserSession->getUserScale(),
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations ));
    }

    // bs_idp_archive_reconciliation_result
    // /reconciliation/result
    public function resultAction( Request $request )
    {
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ? $this->container->get('logger') : null;

        // User logged verification
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged($request->cookies->get('PHPSESSID')))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if ($bsUserSession->getUser()->getChangepass())
            return $this->redirect($this->generateUrl('bs_core_user_change_mdp_screen'));

        // User Right verification
        if (!$bsUserSession->isUserGotRight(IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RECONCILIATION][IDPArchimageRights::RIGHT_ID])) {
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 )); }

        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));
        // Import in progress verification
        if ($globalStatuses && $globalStatuses->getImportInProgress() ) {
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 3 )); }

        // Verify we are in the appropriate reconciliation status to proceed
        if( $globalStatuses->getReconciliationInProgress() != IDPReconciliation::RECONCILIATION_READY  )
            return $this->redirect($this->generateUrl('bs_idp_archive_reconciliation_index'));

        $reconciliation = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPReconciliation')->findOneBy(array('id' => $globalStatuses->getCurrentReconciliationId() ));
        $files = [ 'basedir' => 'import/archimage/',
            'step1-csv' => $reconciliation->getResultFilename() . 'result-step1.idp',
            'step1-xlsx' => $reconciliation->getResultFilename() . 'result-step1.xlsx',
            'step2-csv' => $reconciliation->getResultFilename() . 'result-step2.idp',
            'step2-xlsx' => $reconciliation->getResultFilename() . 'result-step2.xlsx'
        ];

        // Translation texts
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        return $this->render('bsIDPArchiveBundle:Reconciliation:result.html.twig', array(
            'currentMenu' => 38,
            'userscale' => $bsUserSession->getUserScale(),
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'files' => $files ));
    }

    // bs_idp_archive_reconciliation_error
    // /reconciliation/error
    public function errorAction( Request $request )
    {
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ? $this->container->get('logger') : null;

        // User logged verification
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged($request->cookies->get('PHPSESSID')))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if ($bsUserSession->getUser()->getChangepass())
            return $this->redirect($this->generateUrl('bs_core_user_change_mdp_screen'));

        // User Right verification
        if (!$bsUserSession->isUserGotRight(IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RECONCILIATION][IDPArchimageRights::RIGHT_ID])) {
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 )); }

        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));
        // Import in progress verification
        if ($globalStatuses && $globalStatuses->getImportInProgress() ) {
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array('error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 3 )); }

        // Verify we are in the appropriate reconciliation status to proceed
        if( in_array( $globalStatuses->getReconciliationInProgress(), [IDPReconciliation::UPLOAD_IN_PROGRESS,
            IDPReconciliation::VERIFICATION_IN_PROGRESS, IDPReconciliation::DATABASE_COPY_IN_PROGRESS,
            IDPReconciliation::TREATMENT_IN_PROGRESS, IDPReconciliation::RECONCILIATION_READY,
            IDPReconciliation::RESET_IN_PROGRESS, IDPReconciliation::RESULT_FILE_GENERATION_IN_PROGRESS ] ) )
            return $this->redirect($this->generateUrl('bs_idp_archive_reconciliation_index'));

        // Translation texts
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $errorMsg = 'Erreur inconnue !';
        if( array_key_exists( $globalStatuses->getReconciliationInProgress(), IDPReconciliation::$ERROR_MESSAGES) )
            $errorMsg = IDPReconciliation::$ERROR_MESSAGES[$globalStatuses->getReconciliationInProgress()];

        return $this->render('bsIDPArchiveBundle:Reconciliation:error.html.twig', array(
            'currentMenu' => 38,
            'userscale' => $bsUserSession->getUserScale(),
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'errormessage' => $errorMsg ));
    }

    // bs_idp_archive_reconciliation_upload
    // /reconciliation/upload
    public function uploadAction( Request $request ){
        $output = array('uploaded' => false);
        // get the file from the request object
        $file = $request->files->get('file');
        // generate a new filename (safer, better approach)
        //$fileName = $file->getClientOriginalName();
        $fileName = md5(uniqid()).'.csv';

        $em = $this->getDoctrine()->getManager();
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));

        // GET
        // $parameters = $request->query;
        // POST
        $parameters = $request->request;

        if( $parameters->has( 'localization' ) )
            $localization = $parameters->get('localization');
        else{
            $output['uploaded'] = false;
            $output['message'] = "Parameter Localization missing ... !";
            $globalStatuses->setReconciliationInProgress( IDPReconciliation::ERROR_LOCALIZATION_MISSING );
            $em->persist( $globalStatuses );
            $em->flush();
            return new JsonResponse( $output, 400 );
        }

        if( $fileName === null ){
            $output['uploaded'] = false;
            $output['message'] = "Erreur with file ... !";
            $globalStatuses->setReconciliationInProgress( IDPReconciliation::ERROR_FILE_NOT_FOUND );
            $em->persist( $globalStatuses );
            $em->flush();
            return new JsonResponse( $output, 403 );
        }

        // set upload directory
        $uploadDir = $this->get('kernel')->getRootDir() . '/../web/import/archimage/';
        if ($file->move($uploadDir, $fileName)) {
            $output['uploaded'] = true;
        }

        // Update Reconciliation entity
        $reconciliation = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPReconciliation')->findOneBy(array('id' => $globalStatuses->getCurrentReconciliationId() ));
        if( !$reconciliation ){
            // BEGIN has not been received yet; so create reconciliation
            $reconciliation = new IDPReconciliation();
            $now = new DateTime();
            $reconciliation->setDateBeginStep1( $now );
            $reconciliation->setPercentStep1( 0 );
            $em->persist( $reconciliation );
            $em->flush();

            $globalStatuses->setReconciliationInProgress( IDPReconciliation::UPLOAD_IN_PROGRESS );
            $globalStatuses->setCurrentReconciliationId( $reconciliation->getId() );
            $em->persist( $globalStatuses );
            $em->flush();
        }

        $reconciliation->setFilename( $fileName );
        $reconciliation->setRealFilename( $file->getClientOriginalName() );
        $reconciliation->setResultFilename( basename( $file->getClientOriginalName(), $file->getClientOriginalExtension() ) ); // Get Filename without extention
        $em->persist( $reconciliation );
        $em->flush();

        // Launch reconciliation command
        $processCmd = "php ../bin/console app:reconciliate-file ".$localization." archimage/$fileName";
        $process = new Process( $processCmd );
        $process->start();

        sleep(1); // wait for process to start

        // check for errors and send them
        if (!$process->isRunning())
            if (!$process->isSuccessful()){
                // $output['uploaded'] = true;
                $output['message'] = "Oops! The process fininished with an error: ".$process->getExitCode();

                $globalStatuses->setReconciliationInProgress( IDPReconciliation::ERROR_PROCESS_ASYNC );
                $em->persist( $globalStatuses );
                $em->flush();

                return new JsonResponse( $output, 403 );
            }

        return new JsonResponse($output);
    }

    // bs_idp_archive_reconciliation_setstatus
    // /reconciliation/setstatus
    public function setStatusAction( Request $request ){
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ?$this->container->get('logger'):null;

        // User logged verification
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged($request->cookies->get('PHPSESSID')) || $bsUserSession->getUser()->getChangepass())
            return $this->jsonResponse( array('message' => 'User not logged !'), 403 );

        // User Right verification
        if (!$bsUserSession->isUserGotRight(IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RECONCILIATION][IDPArchimageRights::RIGHT_ID]))
            return $this->jsonResponse( array('message' => 'Permission denied'), 401 );

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request

        if( $parameters->has( 'status' ) )
            $status = $parameters->get('status');
        else
            return new JsonResponse( array('message' => 'No status parameter found !' ), 400 );

        if( !in_array( $status, [ 'BEGIN' ] ) )
            return new JsonResponse( array('message' => 'Wrong status parameter !' ), 400 );

        if( strcmp( $status, 'BEGIN' == 0 ) ){
            $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));
            // Can only set to BEGIN if we are in OFF
            if( $globalStatuses->getReconciliationInProgress() != 0 )
                return new JsonResponse( array( 'message' => 'Reconciliation already began !'), 400 );

            $now = new DateTime();
            $em = $this->getDoctrine()->getManager();

            // Create a Reconciliation Entity and link to it
            $reconciliation = new IDPReconciliation();
            $reconciliation->setDateBeginStep1( $now );
            $reconciliation->setPercentStep1( 0 );
            $em->persist( $reconciliation );
            $em->flush();

            $globalStatuses->setReconciliationInProgress( IDPReconciliation::UPLOAD_IN_PROGRESS );
            $globalStatuses->setCurrentReconciliationId( $reconciliation->getId() );
            $em->persist( $globalStatuses );
            $em->flush();

            return new JsonResponse( array( 'message' => 'Success' ) );
        }

        return new JsonResponse( array( 'message' => 'Error' ), 400 );
    }

    // bs_idp_archive_reconciliation_setstatus
    // /reconciliation/setstatus
    public function getStatusAction( Request $request ){
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ?$this->container->get('logger'):null;

        // User logged verification
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged($request->cookies->get('PHPSESSID')) || $bsUserSession->getUser()->getChangepass())
            return $this->jsonResponse( array('message' => 'User not logged !'), 403 );

        // User Right verification
        if (!$bsUserSession->isUserGotRight(IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RECONCILIATION][IDPArchimageRights::RIGHT_ID]))
            return $this->jsonResponse( array('message' => 'Permission denied'), 401 );

        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));
        $data = [ 'status' => $globalStatuses->getReconciliationInProgress() ];
        if( $globalStatuses->getReconciliationInProgress() != 0 ){
            $reconciliation = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPReconciliation')->findOneBy(array('id' => $globalStatuses->getCurrentReconciliationId() ));
            if( $reconciliation ) {
                $data['rec'] = 'OK';
                $data['rec_filename'] = $reconciliation->getFilename();
                $data['rec_realfilename'] = $reconciliation->getRealFilename();
                $data['rec_percentstep1'] = $reconciliation->getPercentStep1();
                $datetoexport = $reconciliation->getDateBeginStep1();
                if( $datetoexport && $datetoexport instanceof DateTime )
                    $datetoexport = $datetoexport->format( 'd/m/Y H:m:s' );
                else
                    $datetoexport = null;
                $data['rec_datebeginstep1'] = $datetoexport;
                $datetoexport = $reconciliation->getDateEndStep1();
                if( $datetoexport && $datetoexport instanceof DateTime )
                    $datetoexport = $datetoexport->format( 'd/m/Y H:m:s' );
                else
                    $datetoexport = null;
                $data['rec_dateendstep1'] = $datetoexport;
                $datetoexport = $reconciliation->getEstimatedEndStep1();
                if( $datetoexport && $datetoexport instanceof DateTime )
                    $datetoexport = $datetoexport->format( 'd/m/Y H:m:s' );
                else
                    $datetoexport = null;
                $data['rec_estimatedendstep1'] = $datetoexport;
                $data['rec_nblinesinfile'] = $reconciliation->getNbLinesInFile();
                $data['rec_nblinestreated'] = $reconciliation->getNbLinesTreated();

                $data['rec_percentstep2'] = $reconciliation->getPercentStep2();
                $datetoexport = $reconciliation->getDateBeginStep2();
                if( $datetoexport && $datetoexport instanceof DateTime )
                    $datetoexport = $datetoexport->format( 'd/m/Y H:m:s' );
                else
                    $datetoexport = null;
                $data['rec_datebeginstep2'] = $datetoexport;
                $datetoexport = $reconciliation->getDateEndStep2();
                if( $datetoexport && $datetoexport instanceof DateTime )
                    $datetoexport = $datetoexport->format( 'd/m/Y H:m:s' );
                else
                    $datetoexport = null;
                $data['rec_dateendstep2'] = $datetoexport;
                $datetoexport = $reconciliation->getEstimatedEndStep2();
                if( $datetoexport && $datetoexport instanceof DateTime )
                    $datetoexport = $datetoexport->format( 'd/m/Y H:m:s' );
                else
                    $datetoexport = null;
                $data['rec_estimatedendstep2'] = $datetoexport;
                $data['rec_nbentriesinbdd'] = $reconciliation->getNbEntriesInBdd();
                $data['rec_nbentriestreated'] = $reconciliation->getNbEntriesTreated();

                $data['rec_resultfilename'] = $reconciliation->getResultFilename();
            } else {
                $data['rec'] = 'Error with BDD';
            }
        }

        return new JsonResponse( array( 'message' => 'Success', 'data' => $data ) );
    }

    // bs_idp_archive_reconciliate_reset
    // /reconciliation/reset
    public function resetAction( Request $request ){
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ?$this->container->get('logger'):null;

        // User logged verification
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged($request->cookies->get('PHPSESSID')) || $bsUserSession->getUser()->getChangepass())
            return $this->jsonResponse( array('message' => 'User not logged !'), 403 );

        // User Right verification
        if (!$bsUserSession->isUserGotRight(IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RECONCILIATION][IDPArchimageRights::RIGHT_ID]))
            return $this->jsonResponse( array('message' => 'Permission denied'), 401 );

        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));

        if( in_array( $globalStatuses->getReconciliationInProgress(), [IDPReconciliation::UPLOAD_IN_PROGRESS,
            IDPReconciliation::VERIFICATION_IN_PROGRESS, IDPReconciliation::DATABASE_COPY_IN_PROGRESS,
            IDPReconciliation::TREATMENT_IN_PROGRESS,  IDPReconciliation::RESET_IN_PROGRESS ] ) )
            return new JsonResponse( array( 'message' => 'Error not in adequate state' ), 400 );

        $em = $this->getDoctrine()->getManager();
        $globalStatuses->setReconciliationInProgress( IDPReconciliation::RESET_IN_PROGRESS );
        $em->persist( $globalStatuses );
        $em->flush();
        sleep( 1 );

        $reconciliation = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPReconciliation')->findOneBy(array('id' => $globalStatuses->getCurrentReconciliationId() ));
        if( !$reconciliation )
            return new JsonResponse( array( 'message' => 'Error no reconciliation struct' ), 400 );

        // Only allowed if in Error or Ready State
        // Delete Copy tables
        $sqlReturn = $this->executeSQLQuery( 'SHOW TABLES LIKE "IDPArchiveCopy";', $em );
        if( $sqlReturn && $sqlReturn->rowCount() != 0 )    // Table exist, drop it
            $this->executeSQLQuery( 'DROP TABLE IDPArchiveCopy;', $em );
        $sqlReturn = $this->executeSQLQuery( 'SHOW TABLES LIKE "IDPDeletedArchiveCopy";', $em );
        if( $sqlReturn && $sqlReturn->rowCount() != 0 )    // Table exist, drop it
            $this->executeSQLQuery( 'DROP TABLE IDPDeletedArchiveCopy;', $em );

        // Delete Input File

        //  FOR DEBUG PURPOSE DO NOT DELETE INPUT FILE
        //    TODO: WHEN GOING TO TEST REMOVE COMMENTS
        $fullName = $this->get('kernel')->getRootDir() . '/../web/import/archimage/' . $reconciliation->getFilename();
        if( file_exists( $fullName ) )
            unlink( $fullName );


        // Delete Result Files
        $fullName = $this->get('kernel')->getRootDir() . '/../web/import/archimage/' . $reconciliation->getResultFilename() . 'result-step1.idp';
        if( file_exists( $fullName ) )
            unlink( $fullName );
        $fullName = $this->get('kernel')->getRootDir() . '/../web/import/archimage/' . $reconciliation->getResultFilename() . 'result-step1.xlsx';
        if( file_exists( $fullName ) )
            unlink( $fullName );
        $fullName = $this->get('kernel')->getRootDir() . '/../web/import/archimage/' . $reconciliation->getResultFilename() . 'result-step2.idp';
        if( file_exists( $fullName ) )
            unlink( $fullName );
        $fullName = $this->get('kernel')->getRootDir() . '/../web/import/archimage/' . $reconciliation->getResultFilename() . 'result-step2.xlsx';
        if( file_exists( $fullName ) )
            unlink( $fullName );

        // Remove Reconciliation struct
        $em->remove( $reconciliation );
        $em->flush();

        // Remove all reconciliation comms
        $this->executeSQLQuery( 'TRUNCATE IDPReconciliationComm;', $em );

        // Remove all reconciliation Files Lines (DB)
        $this->executeSQLQuery( 'TRUNCATE IDPReconciliationFile;', $em );

        // Set Global to Reconciliation OFF
        $globalStatuses->setReconciliationInProgress( IDPReconciliation::NO_RECONCILIATION_IN_PROGRESS );
        $globalStatuses->setCurrentReconciliationId( 0 );
        $em->persist( $globalStatuses );
        $em->flush();

        return new JsonResponse( array( 'message' => 'Success' ) );
    }

    private function executeSQLQuery( $rawQuery, $em ){
        $statement = $em->getConnection()->prepare($rawQuery);
        if( !$statement ) return null;
        if( !$statement->execute() ) return null;
        return $statement;
    }

}