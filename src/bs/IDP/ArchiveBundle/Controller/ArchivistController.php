<?php

namespace bs\IDP\ArchiveBundle\Controller;

use \DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\Core\AdminBundle\Security\IDPArchimageRights;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;
use bs\IDP\ArchiveBundle\Form\Type\IDPArchiveType;
use bs\IDP\ArchiveBundle\Form\Type\IDPCustTransferType;
use bs\IDP\DashboardBundle\Entity\bsMessages;

use bs\IDP\ArchiveBundle\Translation\bsTranslationIDsArchive;
use bs\Core\TranslationBundle\Translation\bsTranslationIDsCommon;
use bs\IDP\BackofficeBundle\Translation\bsTranslationIDsBackoffice;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class ArchivistController extends Controller
{
    protected function sendMessage($text, $status, $to)
    {
        $msg = new bsMessages();
        $msg->setText($text);
        $msg->setSentDate(new DateTime());
        $msg->setBsStatus($status);
        $msg->setBsViewed(false);
        //	$msg->setBsFrom( null );	// null = system
        $msg->setBsTo($to);
        $em = $this->getDoctrine()->getManager();
        $em->persist($msg);
        $em->flush();
    }

    public function manageuserwantsAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_VALIDATE_USER_ASKS][IDPArchimageRights::RIGHT_ID] )) {
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

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $commonsettings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->getCommonSettings($bsUserSession);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEUSERWANTSSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);
        $overlay = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_OVERLAY_VIEWARCHIVE, $language);
        $tableTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsBackoffice::T_ARRAY_FIELD_NAMES, $language);

        return $this->render('bsIDPArchiveBundle:Archivist:manageuserwants.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'commonsettings' => $commonsettings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'language' => $language,
            'overlay' => $overlay,
            'tableTranslations' => $tableTranslations,
            'currentMenu' => 21));
    }

    public function manageproviderwantsAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_PROVIDER_WANTS][IDPArchimageRights::RIGHT_ID] )) {
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

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $commonsettings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->getCommonSettings($bsUserSession);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEPROVIDERWANTSSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);
        $overlay = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_OVERLAY_VIEWARCHIVE, $language);
        $tableTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsBackoffice::T_ARRAY_FIELD_NAMES, $language);

        return $this->render('bsIDPArchiveBundle:Archivist:manageproviderwants.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'commonsettings' => $commonsettings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'language' => $language,
            'overlay' => $overlay,
            'tableTranslations' => $tableTranslations,
            'currentMenu' => 26));
    }

    public function closeuserwantsAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_CLOSE_USER_WANTS][IDPArchimageRights::RIGHT_ID] )) {
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

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $commonsettings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->getCommonSettings($bsUserSession);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_CLOSEUSERWANTSSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);
        $overlay = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_OVERLAY_VIEWARCHIVE, $language);
        $tableTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsBackoffice::T_ARRAY_FIELD_NAMES, $language);

        return $this->render('bsIDPArchiveBundle:Archivist:closeuserwants.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'commonsettings' => $commonsettings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'language' => $language,
            'overlay' => $overlay,
            'tableTranslations' => $tableTranslations,
            'currentMenu' => 27));
    }

    public function managedbinputservicesAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBINPUTSERVICESSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_services.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22));
    }

    public function managedbinputlegalentitiesAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBLEGALENTITIESSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // GET parameters
        $parameters = $request->query;
        if( $parameters->has( 'offset' ) )
            $offset = $parameters->get('offset');
        else
            $offset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = '1';

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_legalentities.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'offset' => $offset,
            'sortASC' => $sortASC));
    }

    public function managedbinputlegalentitiesfinetuneAction(Request $request)
    {
        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;
        if( $logger ) $logger->info( '-> Enter managedbinputlegalentitiesfinetuneAction');

        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBLEGALENTITIESFINETUNESCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // $_GET parameters
        $parameters = $request->query;

        if( $parameters->has( 'legalEntityId' ) )
            $legalEntityId = intval($parameters->get('legalEntityId'));
        else
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_legalentities'));
        if( $parameters->has( 'pageOffset' ) )
            $pageOffset = intval($parameters->get('pageOffset'));
        else
            $pageOffset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = 1;

        $legalEntity = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')->find($legalEntityId);
        if (!$legalEntity) {
            $this->sendMessage('System Error: No legalEntityID for finetune modification', 1, $bsUserSession->getUserId());
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_legalentities'));
        }
        // Get previous and next in table ordered on longname with sort
        $prevNext = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')->findPrevNext($legalEntityId, $sortASC);

        if($logger) $logger->info( json_encode($prevNext) );
        //return null;

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_legalentities_finetune.html.twig', array(
            'legalEntity' => $legalEntity,
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'pageOffset'=> $pageOffset,
            'sortASC' => $sortASC,
            'pagePrevNext' => $prevNext ));
    }

    public function managedbinputbudgetcodesAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBBUDGETCODESSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // GET parameters
        $parameters = $request->query;
        if( $parameters->has( 'offset' ) )
            $offset = $parameters->get('offset');
        else
            $offset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = '1';

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_budgetcodes.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'offset' => $offset,
            'sortASC' => $sortASC));
    }

    public function managedbinputbudgetcodesfinetuneAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBBUDGETCODESFINETUNESCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // $_GET parameters
        $parameters = $request->query;

        if( $parameters->has( 'budgetCodeId' ) )
            $budgetCodeId = intval($parameters->get('budgetCodeId'));
        else
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_budgetcodes'));
        if( $parameters->has( 'pageOffset' ) )
            $pageOffset = intval($parameters->get('pageOffset'));
        else
            $pageOffset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = 1;

        $budgetCode = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')->find($budgetCodeId);
        if (!$budgetCode) {
            $this->sendMessage('System Error: No budgetCodeId for finetune modification', 1, $bsUserSession->getUserId());
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_budgetcodes'));
        }

        // Get previous and next in table ordered on longname with sort
        $prevNext = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')->findPrevNext($budgetCodeId, $sortASC);

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_budgetcodes_finetune.html.twig', array(
            'budgetCode' => $budgetCode,
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'pageOffset'=> $pageOffset,
            'sortASC' => $sortASC,
            'pagePrevNext' => $prevNext ));
    }

    public function managedbinputdocumentnaturesAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBDOCNATURESSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // GET parameters
        $parameters = $request->query;
        if( $parameters->has( 'offset' ) )
            $offset = $parameters->get('offset');
        else
            $offset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = '1';

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_documentnatures.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'offset' => $offset,
            'sortASC' => $sortASC));
    }

    public function managedbinputdocumentnaturesfinetuneAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBDOCNATURESFINETUNESCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // $_GET parameters
        $parameters = $request->query;

        if( $parameters->has( 'documentNatureId' ) )
            $documentNatureId = intval($parameters->get('documentNatureId'));
        else
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_documentnatures'));
        if( $parameters->has( 'pageOffset' ) )
            $pageOffset = intval($parameters->get('pageOffset'));
        else
            $pageOffset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = 1;

        $documentNature = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')->find($documentNatureId);
        if (!$documentNature) {
            $this->sendMessage('System Error: No $documentNatureId for finetune modification', 1, $bsUserSession->getUserId());
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_documentnatures'));
        }

        // Get previous and next in table ordered on longname with sort
        $prevNext = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')->findPrevNext($documentNatureId, $sortASC);

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_documentnatures_finetune.html.twig', array(
            'documentNature' => $documentNature,
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'pageOffset'=> $pageOffset,
            'sortASC' => $sortASC,
            'pagePrevNext' => $prevNext));
    }

    public function managedbinputdescriptions1Action(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBDESCRIPTION1SCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // GET parameters
        $parameters = $request->query;
        if( $parameters->has( 'offset' ) )
            $offset = $parameters->get('offset');
        else
            $offset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = '1';

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_descriptions1.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'offset' => $offset,
            'sortASC' => $sortASC));
    }

    public function managedbinputdescriptions1finetuneAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBDESCRIPTION1FINETUNESCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // $_GET parameters
        $parameters = $request->query;

        if( $parameters->has( 'description1Id' ) )
            $description1Id = intval($parameters->get('description1Id'));
        else
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_descriptions1'));
        if( $parameters->has( 'pageOffset' ) )
            $pageOffset = intval($parameters->get('pageOffset'));
        else
            $pageOffset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = 1;

        $description1 = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')->find($description1Id);
        if (!$description1) {
            $this->sendMessage('System Error: No $description1Id for finetune modification', 1, $bsUserSession->getUserId());
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_descriptions1'));
        }

        // Get previous and next in table ordered on longname with sort
        $prevNext = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')->findPrevNext($description1Id, $sortASC);

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_descriptions1_finetune.html.twig', array(
            'description1' => $description1,
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'pageOffset'=> $pageOffset,
            'sortASC' => $sortASC,
            'pagePrevNext' => $prevNext));
    }

    public function managedbinputdescriptions2Action(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBDESCRIPTION2SCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // GET parameters
        $parameters = $request->query;
        if( $parameters->has( 'offset' ) )
            $offset = $parameters->get('offset');
        else
            $offset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = '1';

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_descriptions2.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'offset' => $offset,
            'sortASC' => $sortASC));
    }

    public function managedbinputdescriptions2finetuneAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBDESCRIPTION2FINETUNESCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // $_GET parameters
        $parameters = $request->query;

        if( $parameters->has( 'description2Id' ) )
            $description2Id = intval($parameters->get('description2Id'));
        else
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_descriptions2'));
        if( $parameters->has( 'pageOffset' ) )
            $pageOffset = intval($parameters->get('pageOffset'));
        else
            $pageOffset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = 1;

        $description2 = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDescriptions2')->find($description2Id);
        if (!$description2) {
            $this->sendMessage('System Error: No $description2Id for finetune modification', 1, $bsUserSession->getUserId());
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_descriptions2'));
        }

        // Get previous and next in table ordered on longname with sort
        $prevNext = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDescriptions2')->findPrevNext($description2Id, $sortASC);

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_descriptions2_finetune.html.twig', array(
            'description2' => $description2,
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'pageOffset'=> $pageOffset,
            'sortASC' => $sortASC,
            'pagePrevNext' => $prevNext ));
    }

    public function managedbinputdeliveraddressAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBDELIVERADDRESSSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_deliveraddress.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22));
    }

    public function managedbinputdocumenttypesAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBDOCTYPESSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // GET parameters
        $parameters = $request->query;
        if( $parameters->has( 'offset' ) )
            $offset = $parameters->get('offset');
        else
            $offset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = '1';

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_documenttypes.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'offset' => $offset,
            'sortASC' => $sortASC));
    }

    public function managedbinputdocumenttypesfinetuneAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBDOCTYPESFINETUNESCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // $_GET parameters
        $parameters = $request->query;

        if( $parameters->has( 'documentTypeId' ) )
            $documentTypeId = intval($parameters->get('documentTypeId'));
        else
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_documenttypes'));
        if( $parameters->has( 'pageOffset' ) )
            $pageOffset = intval($parameters->get('pageOffset'));
        else
            $pageOffset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = 1;

        $documentType = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')->find($documentTypeId);
        if (!$documentType) {
            $this->sendMessage('System Error: No $documentTypeId for finetune modification', 1, $bsUserSession->getUserId());
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_documenttypes'));
        }

        // Get previous and next in table ordered on longname with sort
        $prevNext = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')->findPrevNext($documentTypeId, $sortASC);

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_documenttypes_finetune.html.twig', array(
            'documentType' => $documentType,
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'pageOffset'=> $pageOffset,
            'sortASC' => $sortASC,
            'pagePrevNext' => $prevNext));
    }

    public function managedbprovidersAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBPROVIDERSSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // GET parameters
        $parameters = $request->query;
        if( $parameters->has( 'offset' ) )
            $offset = $parameters->get('offset');
        else
            $offset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = '1';

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_providers.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'offset' => $offset,
            'sortASC' => $sortASC));
    }

    public function managedbprovidersfinetuneAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBPROVIDERSFINETUNESCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // $_GET parameters
        $parameters = $request->query;

        if( $parameters->has( 'providerId' ) )
            $providerId = intval($parameters->get('providerId'));
        else
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_legalentities'));
        if( $parameters->has( 'pageOffset' ) )
            $pageOffset = intval($parameters->get('pageOffset'));
        else
            $pageOffset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = 1;

        $provider = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPProviders')->find($providerId);
        if (!$provider) {
            $this->sendMessage('System Error: No $providerId for finetune modification', 1, $bsUserSession->getUserId());
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_providers'));
        }

        // Get previous and next in table ordered on longname with sort
        $prevNext = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPProviders')->findPrevNext($providerId, $sortASC);

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_providers_finetune.html.twig', array(
            'provider' => $provider,
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'pageOffset'=> $pageOffset,
            'sortASC' => $sortASC,
            'pagePrevNext' => $prevNext));
    }

    public function managedbinputlocalizationsAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBLOCALIZATIONSSCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // GET parameters
        $parameters = $request->query;
        if( $parameters->has( 'offset' ) )
            $offset = $parameters->get('offset');
        else
            $offset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = '1';

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_localizations.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'offset' => $offset,
            'sortASC' => $sortASC));
    }

    public function managedbinputlocalizationsfinetuneAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] )) {
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

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language);
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PAGE_MANAGEDBLOCALIZATIONSFINETUNESCREEN, $language);
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        // $_GET parameters
        $parameters = $request->query;

        if( $parameters->has( 'localizationId' ) )
            $localizationId = intval($parameters->get('localizationId'));
        else
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_legalentities'));
        if( $parameters->has( 'pageOffset' ) )
            $pageOffset = intval($parameters->get('pageOffset'));
        else
            $pageOffset = 0;
        if( $parameters->has( 'sortASC' ) )
            $sortASC = $parameters->get('sortASC');
        else
            $sortASC = 1;

        $localization = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPLocalizations')->find($localizationId);
        if (!$localization) {
            $this->sendMessage('System Error: No localizationId for finetune modification', 1, $bsUserSession->getUserId());
            return $this->redirect($this->generateUrl('bs_idp_archivist_managedb_input_localizations'));
        }

        // Get previous and next in table ordered on longname with sort
        $prevNext = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPLocalizations')->findPrevNext($localizationId, $sortASC);

        return $this->render('bsIDPArchiveBundle:Archivist:managedb_input_localizations_finetune.html.twig', array(
            'localization' => $localization,
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22,
            'pageOffset'=> $pageOffset,
            'sortASC' => $sortASC,
            'pagePrevNext' => $prevNext));
    }

    public function askUnlimitedScreenAction( Request $request )
    {
        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged($request->cookies->get('PHPSESSID')))
            return $this->redirect($this->generateUrl('bs_core_user_login'));
        if ($bsUserSession->getUser()->getChangepass())
            return $this->redirect($this->generateUrl('bs_core_user_change_mdp_screen'));

        $language = $bsUserSession->getUserExtension()->getLanguage();
        $systemTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_SYSTEM, $language);

        if (!$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_UNLIMITED][IDPArchimageRights::RIGHT_ID] )) {
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
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

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $commonsettings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->getCommonSettings($bsUserSession);

        $searchTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_TABSEARCH, $language);
        $filterTanslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_TABFILTER, $language);
        $resultTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_PARTIAL_TABRESULTS, $language);

        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_UNLIMITEDSCREEN, $language );

        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);
        $overlay = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsArchive::T_OVERLAY_VIEWARCHIVE, $language);
        $tableTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsBackoffice::T_ARRAY_FIELD_NAMES, $language);

        return $this->render('bsIDPArchiveBundle:Archivist:askUnlimited.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'commonsettings' => $commonsettings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'searchTranslations' => $searchTranslations,
            'filterTranslations' => $filterTanslations,
            'tableTranslations' => $tableTranslations,
            'resultTranslations' => $resultTranslations,
            'language' => $language,
            'overlay' => $overlay,
            'currentMenu' => 35 ));
    }
}
