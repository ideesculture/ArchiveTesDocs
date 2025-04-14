<?php

namespace bs\IDP\BackofficeBundle\Controller;

use bs\IDP\BackofficeBundle\Entity\IDPMainSettings;
use \DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\IDP\DashboardBundle\Entity\bsMessages;
use bs\Core\AdminBundle\Security\IDPArchimageRights;

use bs\Core\TranslationBundle\Translation\bsTranslationIDsCommon;
use bs\IDP\ArchiveBundle\Translation\bsTranslationIDsArchive;
use bs\IDP\BackofficeBundle\Translation\bsTranslationIDsBackoffice;

use bs\IDP\BackofficeBundle\Entity\IDPGlobalSettings;
use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class SettingsController extends Controller
{
	const SETTINGSFIELD_VIEWBUDGETCODE 			= 0;
	const SETTINGSFIELD_MANDATORYBUDGETCODE		= 1;
	const SETTINGSFIELD_VIEWDOCUMENTNATURE		= 2;
	const SETTINGSFIELD_MANDATORYDOCUMENTNATURE	= 3;
	const SETTINGSFIELD_VIEWDOCUMENTTYPE		= 4;
	const SETTINGSFIELD_MANDATORYDOCUMENTTYPE	= 5;
	const SETTINGSFIELD_VIEWDESCRIPTION1		= 6;
	const SETTINGSFIELD_MANDATORYDESCRIPTION1	= 7;
	const SETTINGSFIELD_NAMEDESCRIPTION1		= 8;
	const SETTINGSFIELD_VIEWDESCRIPTION2		= 9;
	const SETTINGSFIELD_MANDATORYDESCRIPTION2	= 10;
	const SETTINGSFIELD_NAMEDESCRIPTION2		= 11;
	const SETTINGSFIELD_VIEWLIMITSNUM			= 12;
	const SETTINGSFIELD_MANDATORYLIMITSNUM		= 13;
	const SETTINGSFIELD_VIEWLIMITSALPHA			= 14;
	const SETTINGSFIELD_MANDATORYLIMITSALPHA	= 15;
	const SETTINGSFIELD_VIEWLIMITSALPHANUM		= 16;
	const SETTINGSFIELD_MANDATORYLIMITSALPHANUM	= 17;
	const SETTINGSFIELD_VIEWLIMITSDATE			= 18;
	const SETTINGSFIELD_MANDATORYLIMITSDATE		= 19;
	const SETTINGSFIELD_VIEWFILENUMBER			= 20;
	const SETTINGSFIELD_MANDATORYFILENUMBER		= 21;
	const SETTINGSFIELD_VIEWBOXNUMBER			= 22;
	const SETTINGSFIELD_MANDATORYBOXNUMBER		= 23;
	const SETTINGSFIELD_VIEWCONTAINERNUMBER		= 24;
	const SETTINGSFIELD_MANDATORYCONTAINERNUMBER= 25;
	const SETTINGSFIELD_VIEWPROVIDER			= 26;
	const SETTINGSFIELD_MANDATORYPROVIDER		= 27;
	const SETTINGSFIELD_DEFAULTLANGUAGE			= 28;
	const SETTINGSFIELD_VIEWTRANSFERINTERNALBASKET = 29;
	const SETTINGSFIELD_VIEWTRANSFERINTERMEDIATEBASKET = 30;
	const SETTINGSFIELD_VIEWTRANSFERPROVIDERBASKET = 31;
	const SETTINGSFIELD_VIEWRELOCINTERNALBASKET = 32;
	const SETTINGSFIELD_VIEWRELOCINTERMEDIATEBASKET = 33;
	const SETTINGSFIELD_VIEWRELOCPROVIDERBASKET = 34;

	const SETTINGSFIELD_ALLSERVICESATONCE = 99;

	protected function sendMessage( $text, $status, $to )
	{
		$msg = new bsMessages();
		$msg->setText( $text );
		$msg->setSentDate( new DateTime() );
		$msg->setBsStatus( $status );
		$msg->setBsViewed( false );
		//	$msg->setBsFrom( null );	// null = system
		$msg->setBsTo( $to );
		$em = $this->getDoctrine()->getManager();
		$em->persist($msg);
		$em->flush();
	}

	private function jsonResponse( $return, $code = 200 ){
		$response = new Response(json_encode($return), $code );
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	public function manageVisibilityAction(Request $request)
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

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_BDD][IDPArchimageRights::RIGHT_ID] ) ){
			return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
			    'error_redirect' => 'bs_idp_archivist_managedb_input_services',
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
		$settings = $this->getDoctrine()
        	->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
        	->getArraySettings();

		$allServiceAtOnce_Setting = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPMainSettings')
            ->getMainSetting( IDPMainSettings::ALL_SERVICES_CONFIGURED_AT_ONCE );

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $partialMenuTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language );
		$translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsBackoffice::T_PAGE_MANAGEVISIBILITYSCREEN, $language );
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

        return $this->render('bsIDPBackofficeBundle:Settings:manageVisibility.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'settings' => $settings,
            'allServiceAtOnce' => $allServiceAtOnce_Setting[IDPMainSettings::ALL_SERVICES_CONFIGURED_AT_ONCE],
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22 ));
	}

	public function setAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged() )
			return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

			// GET
			$parameters = $request->query;
			// POST
			// $parameters = $request->request;

			$settingsfield = $parameters->get('settingsfield');
			$value = $parameters->get('value');
            $serviceId = $parameters->get('serviceid');

			$serviceSettings = $this->getDoctrine()
					->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
					->findOneBy(array( 'service_id' => $serviceId ));

			if( !$serviceSettings )
				return $this->jsonResponse( array('message' => 'System Error : settings not found') , 419);

			if( $settingsfield != self::SETTINGSFIELD_ALLSERVICESATONCE ) {

                switch ($settingsfield) {
                    case self::SETTINGSFIELD_VIEWBUDGETCODE:
                        $serviceSettings->setViewBudgetcode($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYBUDGETCODE:
                        $serviceSettings->setMandatoryBudgetcode($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWDOCUMENTNATURE:
                        $serviceSettings->setViewDocumentnature($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYDOCUMENTNATURE:
                        $serviceSettings->setMandatoryDocumentnature($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWDOCUMENTTYPE:
                        $serviceSettings->setViewDocumenttype($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYDOCUMENTTYPE:
                        $serviceSettings->setMandatoryDocumenttype($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWDESCRIPTION1:
                        $serviceSettings->setViewDescription1($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYDESCRIPTION1:
                        $serviceSettings->setMandatoryDescription1($value == 1);
                        break;
                    case self::SETTINGSFIELD_NAMEDESCRIPTION1:
                        $serviceSettings->setNameDescription1($value);
                        break;
                    case self::SETTINGSFIELD_VIEWDESCRIPTION2:
                        $serviceSettings->setViewDescription2($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYDESCRIPTION2:
                        $serviceSettings->setMandatoryDescription2($value == 1);
                        break;
                    case self::SETTINGSFIELD_NAMEDESCRIPTION2:
                        $serviceSettings->setNameDescription2($value);
                        break;
                    case self::SETTINGSFIELD_VIEWLIMITSNUM:
                        $serviceSettings->setViewLimitsnum($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYLIMITSNUM:
                        $serviceSettings->setMandatoryLimitsnum($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWLIMITSALPHA:
                        $serviceSettings->setViewLimitsalpha($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYLIMITSALPHA:
                        $serviceSettings->setMandatoryLimitsalpha($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWLIMITSALPHANUM:
                        $serviceSettings->setViewLimitsalphanum($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYLIMITSALPHANUM:
                        $serviceSettings->setMandatoryLimitsalphanum($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWLIMITSDATE:
                        $serviceSettings->setViewLimitsdate($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYLIMITSDATE:
                        $serviceSettings->setMandatoryLimitsdate($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWFILENUMBER:
                        $serviceSettings->setViewFilenumber($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYFILENUMBER:
                        $serviceSettings->setMandatoryFilenumber($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWBOXNUMBER:
                        $serviceSettings->setViewBoxnumber($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYBOXNUMBER:
                        $serviceSettings->setMandatoryBoxnumber($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWCONTAINERNUMBER:
                        $serviceSettings->setViewContainernumber($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYCONTAINERNUMBER:
                        $serviceSettings->setMandatoryContainernumber($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWPROVIDER:
                        $serviceSettings->setViewProvider($value == 1);
                        break;
                    case self::SETTINGSFIELD_MANDATORYPROVIDER:
                        $serviceSettings->setMandatoryProvider($value == 1);
                        break;
                    case self::SETTINGSFIELD_DEFAULTLANGUAGE:
                        $serviceSettings->setDefaultLanguage($value);
                        break;
                    case self::SETTINGSFIELD_VIEWTRANSFERINTERNALBASKET:
                        $serviceSettings->setViewTransferInternalBasket($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWTRANSFERINTERMEDIATEBASKET:
                        $serviceSettings->setViewTransferIntermediateBasket($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWTRANSFERPROVIDERBASKET:
                        $serviceSettings->setViewTransferProviderBasket($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWRELOCINTERNALBASKET:
                        $serviceSettings->setViewRelocInternalBasket($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWRELOCINTERMEDIATEBASKET:
                        $serviceSettings->setViewRelocIntermediateBasket($value == 1);
                        break;
                    case self::SETTINGSFIELD_VIEWRELOCPROVIDERBASKET:
                        $serviceSettings->setViewRelocProviderBasket($value == 1);
                        break;
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($serviceSettings);
                $em->flush();

            } else {
                $allinonceSetting = $this->getDoctrine()
                    ->getRepository('bsIDPBackofficeBundle:IDPMainSettings')
                    ->findOneBy(array( 'name' => IDPMainSettings::ALL_SERVICES_CONFIGURED_AT_ONCE ));
                if( !$allinonceSetting )
                    return $this->jsonResponse( array('message' => 'System Error : settings not found') , 417);

                $allinonceSetting->setIntValue( $value );
                $em = $this->getDoctrine()->getManager();
                $em->persist( $allinonceSetting );
                $em->flush();

                // If set to true, copy actual service settings to all other ones
                if( $value == 1 ){
                    $allSettings = $this->getDoctrine()
                        ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
                        ->findAll();
                    foreach( $allSettings as $setting ){
                        if( $serviceSettings->getServiceId() != $setting->getServiceId() ) {
                            $setting->setViewBudgetcode($serviceSettings->getViewBudgetcode());
                            $setting->setMandatoryBudgetcode($serviceSettings->getMandatoryBudgetcode());
                            $setting->setViewDocumentnature($serviceSettings->getViewDocumentnature());
                            $setting->setMandatoryDocumentnature($serviceSettings->getMandatoryDocumentnature());
                            $setting->setViewDocumenttype($serviceSettings->getViewDocumenttype());
                            $setting->setMandatoryDocumenttype($serviceSettings->getMandatoryDocumenttype());
                            $setting->setViewDescription1($serviceSettings->getViewDescription1());
                            $setting->setMandatoryDescription1($serviceSettings->getMandatoryDescription1());
                            $setting->setNameDescription1($serviceSettings->getNameDescription1());
                            $setting->setViewDescription2($serviceSettings->getViewDescription2());
                            $setting->setMandatoryDescription2($serviceSettings->getMandatoryDescription2());
                            $setting->setNameDescription2($serviceSettings->getNameDescription2());
                            $setting->setViewLimitsnum($serviceSettings->getViewLimitsnum());
                            $setting->setMandatoryLimitsnum($serviceSettings->getMandatoryLimitsnum());
                            $setting->setViewLimitsalpha($serviceSettings->getViewLimitsalpha());
                            $setting->setMandatoryLimitsalpha($serviceSettings->getMandatoryLimitsalpha());
                            $setting->setViewLimitsalphanum($serviceSettings->getViewLimitsalphanum());
                            $setting->setMandatoryLimitsalphanum($serviceSettings->getMandatoryLimitsalphanum());
                            $setting->setViewLimitsdate($serviceSettings->getViewLimitsdate());
                            $setting->setMandatoryLimitsdate($serviceSettings->getMandatoryLimitsdate());
                            $setting->setViewFilenumber($serviceSettings->getViewFilenumber());
                            $setting->setMandatoryFilenumber($serviceSettings->getMandatoryFilenumber());
                            $setting->setViewBoxnumber($serviceSettings->getViewBoxnumber());
                            $setting->setMandatoryBoxnumber($serviceSettings->getMandatoryFilenumber());
                            $setting->setViewContainernumber($serviceSettings->getViewContainernumber());
                            $setting->setMandatoryContainernumber($serviceSettings->getMandatoryContainernumber());
                            $setting->setViewProvider($serviceSettings->getViewProvider());
                            $setting->setMandatoryProvider($serviceSettings->getMandatoryProvider());
                            $setting->setDefaultLanguage($serviceSettings->getDefaultLanguage());
                            $setting->setViewTransferInternalBasket($serviceSettings->getViewTransferInternalBasket());
                            $setting->setViewTransferIntermediateBasket($serviceSettings->getViewTransferIntermediateBasket());
                            $setting->setViewTransferProviderBasket($serviceSettings->getViewTransferProviderBasket());
                            $setting->setViewRelocInternalBasket($serviceSettings->getViewRelocInternalBasket());
                            $setting->setViewRelocIntermediateBasket($serviceSettings->getViewRelocIntermediateBasket());
                            $setting->setViewRelocProviderBasket($serviceSettings->getViewRelocProviderBasket());

                            $em->persist( $setting );
                            $em->flush();
                        }
                    }
                }
            }

			return $this->jsonResponse( array('message' => 'Settings modified') );
//		} else
//			return $this->jsonResponse( array('message' => 'System Error : Only Ajax Request Allowed'), 419);
	}

    public function manageGlobalSettingsPasswordsAction(Request $request)
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

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_SETTINGS][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
                'error_redirect' => 'bs_idp_archivist_managedb_input_services',
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

        $passwordGlobalSettings = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPGlobalSettings' )
            ->getPasswordSettings( );

        $partialMenuTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language );
        $translations = null;
/*        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsBackoffice::T_PAGE_MANAGEGLOBALSETTINGSPASSWORDSSCREEN, $language );
*/
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

        return $this->render('bsIDPBackofficeBundle:Settings:manageGlobalSettingsPasswords.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'passwordGlobalSettings' => $passwordGlobalSettings,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'language' => $language,
            'currentMenu' => 22 ));
    }
    public function updateGlobalSettingsPasswordsAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_SETTINGS][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit!' ), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;

        if( $parameters->has( 'password_min_length' ) )
            $password_min_length = intval($parameters->get('password_min_length'));
        else
            $this->jsonResponse( array('message' => 'System Error : password_min_length required'), 400);
        if( $parameters->has( 'password_complexity' ) )
            $password_complexity = intval($parameters->get('password_complexity'));
        else
            $this->jsonResponse( array('message' => 'System Error : password_complexity required'), 400);

        $em = $this->getDoctrine()->getManager();

        $db_min_length = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPGlobalSettings' )
            ->findOneBy( [ 'name' => IDPGlobalSettings::PASSWORD_MIN_LENGTH ] );
        if( !$db_min_length )
            $this->jsonResponse( array('message' => 'System Error : password_min_length not found in database'), 417);
        $db_min_length->setIntValue( $password_min_length );

        $em->persist( $db_min_length );

        $db_complexity = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPGlobalSettings' )
            ->findOneBy( [ 'name' => IDPGlobalSettings::PASSWORD_COMPLEXITY ] );
        if( !$db_complexity )
            $this->jsonResponse( array('message' => 'System Error : password_complexity not found in database'), 417);
        $db_complexity->setIntValue( $password_complexity );
        $em->persist( $db_complexity );

        $em->flush();

        return $this->jsonResponse( array('message' => 'Settings modified') );
//		} else
//			return $this->jsonResponse( array('message' => 'System Error : Only Ajax Request Allowed'), 419);
    }
}
