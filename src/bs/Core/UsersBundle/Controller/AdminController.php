<?php

namespace bs\Core\UsersBundle\Controller;

use bs\Core\UsersBundle\Entity\IDPUserAutoSaveFields;
use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPUserPagesSettings;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use \DateTime;
use bs\IDP\DashboardBundle\Entity\bsMessages;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\Core\AdminBundle\Security\IDPArchimageRights;

use bs\Core\UsersBundle\Entity\bsUsers;
use bs\Core\UsersBundle\Entity\IDPUserExtensions;
use bs\Core\UsersBundle\Entity\bsRights;
use bs\Core\UsersBundle\Entity\bsRoles;
use bs\Core\UsersBundle\Entity\IDPUserServices;
use bs\Core\UsersBundle\Entity\IDPUserAddresses;

use bs\Core\TranslationBundle\Translation\bsTranslationIDsCommon;
use bs\IDP\ArchiveBundle\Translation\bsTranslationIDsArchive;
use bs\Core\UsersBundle\Translation\bsTranslationIDsUsers;

use bs\IDP\BackofficeBundle\Entity\IDPGlobalSettings;
use bs\IDP\BackofficeBundle\Entity\IDPUserColumnsSettings;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class AdminController extends Controller
{
	protected function sendMessage( $text, $status, $userID = null )
	{
		$msg = new bsMessages();
		$msg->setText( $text );
		$msg->setSentDate( new DateTime() );
		$msg->setBsStatus( $status );
		$msg->setBsViewed( false );
		$msg->setBsTo( $userID );
		$em = $this->getDoctrine()->getManager();
		$em->persist($msg);
		$em->flush();
	}

	// User administration

	public function listAction( Request $request )
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

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language );
		$translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsUsers::T_PAGE_USERLISTSCREEN, $language );
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

        return $this->render('bsCoreUsersBundle:admin:user_list.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'currphpsessid' => $request->cookies->get('PHPSESSID'),
            'sesslifetime' => UsersController::BS_CORE_USER_SESSION_LIFETIME,
            'language' => $language,
            'currentMenu' => 22) );
	}

	private function makeUserAddForm( $translations, $user = null, $userExtension = null, $services = null, $modify = false, $addresses = null )
	{
		$form = $this->createFormBuilder();
		if( $services == null )
			$form->add( 'userServices', HiddenType::class, array( 'data' => '' ));
		else {
			$strUserServices = '';
			$first = true;
			foreach( $services as $service ) {
				if( $first )
					$first = false;
				else
					$strUserServices .= ',';
				$strUserServices .= $service->getService()->getId();
			}
			$form->add( 'userServices', HiddenType::class, array( 'data' => $strUserServices ));
		}
		if( $addresses == null )
		    $form->add( 'userAddresses', HiddenType::class, array( 'data' => '' ));
		else {
		    $strUserAddresses = '';
		    $first = true;
		    foreach( $addresses as $address ){
		        if( $first )
		            $first = false;
		        else
		            $strUserAddresses .= ',';
		        $strUserAddresses .= $address->getAddress()->getId();
            }
            $form->add( 'userAddresses', HiddenType::class, array( 'data' => $strUserAddresses ));
        }

		if( $user == null ){
			$form->add( 'userFirstname', TextType::class, array ( 'label' => $translations[2], 'required' => true ));
			$form->add( 'userLastname', TextType::class, array ( 'label' => $translations[1], 'required' => true ));
			$form->add( 'userLogin', TextType::class, array ( 'label' => $translations[3], 'required' => true ));
			$form->add( 'userPassword', TextType::class, array( 'label' => $translations[4], 'required' => true ));
		} else {
			$form->add( 'userId', HiddenType::class, array( 'data' => $user->getId() ));
			$form->add( 'userFirstname', TextType::class, array ( 'label' => $translations[1], 'required' => true, 'data' => $user->getFirstname() ));
			$form->add( 'userLastname', TextType::class, array ( 'label' => $translations[2], 'required' => true, 'data' => $user->getLastname() ));
			$form->add( 'userLogin', TextType::class, array ( 'label' => $translations[3], 'required' => true, 'data' => $user->getLogin() ));
			$form->add( 'userPassword', TextType::class, array( 'label' => $translations[5], 'required' => false, 'attr' => array(
        		'placeholder' => $translations[6] ) ));
		}

		if( $userExtension == null ){
			$form->add( 'userInitials', TextType::class, array( 'label' => $translations[7], 'required' => true ));
		} else {
			$form->add( 'userInitials', TextType::class, array( 'label' => $translations[7], 'required' => true, 'data' => $userExtension->getInitials() ));
		}

		if( !$modify )
			$form->add( 'addUserBtn', SubmitType::class, array( 'label' => $translations[8] ));
		else
			$form->add( 'modifyUserBtn', SubmitType::class, array( 'label' => $translations[9] ));

		return $form->getForm();
	}

	public function addAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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

        $passwordGlobalSettings = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPGlobalSettings' )
            ->getPasswordSettings();

		$partialMenuTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language );
		$translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsUsers::T_PAGE_ADDUSERSCREEN, $language );
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

        $form = $this->makeUserAddForm( $translations );

		return $this->render('bsCoreUsersBundle:admin:addUserScreen.html.twig', array(
		    'form' => $form->createView(),
            'user' => null,
            'role' => null,
            'action' => 'ADD',
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'partialmenuTranslations' => $partialMenuTranslations,
            'translations' => $translations,
            'passwordGlobalSettings' => $passwordGlobalSettings,
            'language' => $language,
            'currentMenu' => 22 ));
	}

    protected function createUserSettings( $userID, $fullLog, &$log ){

        $upsRep = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPUserPagesSettings' );
        $ucsRep = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPUserColumnsSettings' );

        // Verify if settings don't already exist
        $settings = $upsRep->findBy( array( 'user_id' => $userID ) );
        if( $settings && count( $settings ) >= 1 ){
            array_push( $log, "User Page Settings already exists for user: $userID" );
            return false;
        }

        // Get default settings
        $defaultPagesSettings = $upsRep->findBy( array( 'user_id' => 0 ) );
        if( !$defaultPagesSettings || count( $defaultPagesSettings ) <= 0 ){
            array_push( $log, "No default Page Settings found" );
            return false;
        }

        $em = $this->getDoctrine()->getManager();
        $countPages = 0;
        $countColumns = 0;

        foreach ( $defaultPagesSettings as $defaultPageSettings ){
            $countPages++;

            // Create same settings for user
            $newPageSettings = clone( $defaultPageSettings );
            $newPageSettings->setUserid( $userID );

            $em->persist( $newPageSettings );
            $em->flush();

            $pageSettingsID = $newPageSettings->getId();

            if( $pageSettingsID > 0 ){
                if( $fullLog )
                    array_push( $log, "* Page Settings ".$defaultPageSettings->getId()." successfully copyied in $pageSettingsID" );
                // If not BDD page, copy also Columns settings
                if( !( ($pageSettingsID >= IDPUserPagesSettings::PAGE_BDD_ENTRY_SERVICES)&&($pageSettingsID <= IDPUserPagesSettings::PAGE_BDD_PROVIDERS) ) ) {
                    // Get all default columns settings for the defaultPageSettings
                    $defaultColumnsSettings = $ucsRep->findBy(array('user_page_settings' => $defaultPageSettings));
                    if ($defaultColumnsSettings && count($defaultColumnsSettings) >= 1) {
                        foreach ($defaultColumnsSettings as $defaultColumnSettings) {
                            $countColumns++;

                            // Create same settings for new page
                            $newColumnSettings = clone ($defaultColumnSettings);
                            $newColumnSettings->setUserpagesettings($newPageSettings);

                            $em->persist($newColumnSettings);
                            if ($fullLog) {
                                $em->flush();
                                $columnSettingID = $newColumnSettings->getId();
                            }

                            if ($fullLog) {
                                if ($columnSettingID <= 0)
                                    array_push($log, " - Error while copying Column Settings " . $defaultPageSettings->getId());
                                else
                                    array_push($log, " - Column Settings " . $defaultColumnSettings->getId() . " successfully copyied in $columnSettingID");
                            }
                        }
                        if (!$fullLog)
                            $em->flush();
                    }
                }
            } else
                array_push( $log, "Error while copying Page Settings ".$defaultPageSettings->getId() );
        }
        array_push( $log, "$countColumns Columns created in $countPages Pages" );
        return true;
    }

	public function doaddAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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

        $data = array();
		if ($request->isMethod('POST')) {
			$data =  $request->request->get('form');

			// Create bsUsers entry
			$user = new bsUsers();
			$user->setFirstname( $data['userFirstname']);
			$user->setLastname( $data['userLastname']);
			$user->setLogin( $data['userLogin'] );
			$user->setPassword( md5( $data['userPassword'] ) );
			$user->setChangepass( false );
			$user->setFailedconnexioncounter( 0 );

			$em = $this->getDoctrine()->getManager();
			$em->persist( $user );

			$role = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsRoles' )->find( intval( $data['userRole' ] ) );
			if( $role ){
				$user->addRole( $role );	// Tricky ManyToMany Symfony2 update thing ! (but it works !)
				$role->addUser( $user );
				$em->persist( $role );
				$em->persist( $user );
				foreach( $role->getRights() as $right ){
					$user->addRight( $right );
					$right->addUser( $user );
					$em->persist( $right );
					$em->persist( $user );
				}
			}

			$em->flush();

			// TODO get Default Language from USER settings instead !!
			$settings = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
				->getArraySettings();


			// Create IDPUserExtension entry
			$userExt = new IDPUserExtensions();
			$userExt->setUser( $user );
			$userExt->setInitials( strtoupper( $data['userInitials'] ) );
			$userExt->setUacounter( 0 );
			$userExt->setLanguage( $settings[0]['default_language'] );

			$em->persist( $userExt );
			$em->flush();

			// Create IDPUserServices entry
			$services = explode( ",", $data['userServices'] );

			foreach( $services as $serviceId ){
				$userService = new IDPUserServices();
				$userService->setUser( $user );
				$service = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPServices' )->find( intval( $serviceId ) );
				if( $service ){
					$userService->setService( $service );

					$em->persist( $userService );
					$em->flush();
				}
			}

			// Create IDPUserAddresses entry
            $addresses = explode( ',', $data['userAddresses'] );

            foreach( $addresses as $addressId ){
                $userAddress = new IDPUserAddresses();
                $userAddress->setUser( $user );
                $address = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDeliverAddress' )->find( intval( $addressId ) );
                if( $address ){
                    $userAddress->setAddress( $address );

                    $em->persist( $userAddress );
                    $em->flush();
                }
            }

            // Create DefaultSettings for user
            $log = array();
            if( !$this->createUserSettings( $user->getId(), false, $log ) )
                $this->sendMessage( 'Erreur lors de la création des paramètres par défaut.', 0, $bsUserSession->getUserId() );

            // Create default ASF for user E#307
            $default_asf = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserAutoSaveFields' )
                ->findOneBy( array( 'user_id' => 0 ));
            if( $default_asf ){
                // Verify if no Asf is already there for this user
                $user_asf = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserAutoSaveFields' )
                    ->findOneBy( array( 'user_id' =>  $user->getId() ) );
                if( !$user_asf ){
                    // No asf for this new user, It's normal, create one
                    $user_asf = new IDPUserAutoSaveFields();
                    $user_asf->setUserId( $user->getId() );
                    $user_asf->setAsfService( $default_asf->getAsfService() );
                    $user_asf->setAsfLegalentity( $default_asf->getAsfLegalentity() );
                    $user_asf->setAsfBudgetcode( $default_asf->getAsfBudgetcode() );
                    $user_asf->setAsfDocumentnature( $default_asf->getAsfDocumentnature() );
                    $user_asf->setAsfDocumenttype( $default_asf->getAsfDocumenttype() );
                    $user_asf->setAsfDescription1( $default_asf->getAsfDescription1() );
                    $user_asf->setAsfDescription2( $default_asf->getAsfDescription2() );
                    $user_asf->setAsfClosureyear( $default_asf->getAsfClosureyear() );
                    $user_asf->setAsfDestructionyear( $default_asf->getAsfDestructionyear() );
                    $user_asf->setAsfFilenumber( $default_asf->getAsfFilenumber() );
                    $user_asf->setAsfBoxnumber( $default_asf->getAsfBoxnumber() );
                    $user_asf->setAsfContainernumber( $default_asf->getAsfContainernumber() );
                    $user_asf->setAsfProvider( $default_asf->getAsfProvider() );
                    $user_asf->setAsfLimitsdate( $default_asf->getAsfLimitsdate() );
                    $user_asf->setAsfLimitsnum( $default_asf->getAsfLimitsnum() );
                    $user_asf->setAsfLimitsalpha( $default_asf->getAsfLimitsalpha() );
                    $user_asf->setAsfLimitsalphanum( $default_asf->getAsfLimitsalphanum() );
                    $user_asf->setAsfName( $default_asf->getAsfName() );
                    $em->persist( $user_asf );
                    $em->flush();
                } else
                    $this->sendMessage( 'Il y a déjà un Asf pour cet utilisateur.', 0, $bsUserSession->getUserId() );
            } else
                $this->sendMessage( 'Erreur lors de la recherche du default Asf.', 0, $bsUserSession->getUserId() );

			$this->sendMessage( 'Utilisateur a été créé avec succès.', 0, $bsUserSession->getUserId() );
		} else {
			$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
		}

		return $this->redirect($this->generateUrl('bs_core_users_admin_list'));
	}

	public function modifyAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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

        $passwordGlobalSettings = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPGlobalSettings' )
            ->getPasswordSettings();

        if ($request->isMethod('POST')) {

			$userId =  intval( $request->request->get('userID') );

			$user = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsUsers' )->find( $userId );
			if( !$user ){
				$this->sendMessage( 'System Error: No userID for modification', 1, $bsUserSession->getUserId() );
				return $this->redirect($this->generateUrl('bs_core_users_admin_list'));
			}
			$userServices = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserServices' )->findBy( array( 'user' => $userId ));
			if( !$userServices )
				$userServices = null;

			$userAddresses = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserAddresses' )->findBy( array( 'user' => $userId ));
			if( !$userAddresses )
			    $userAddresses = null;

			$userExtension = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserExtensions' )->findBy( array( 'user' => $userId ));
			if( !$userExtension )
				$userExtension = null;

			$partialMenuTranslations = $this->getDoctrine()
				->getRepository('bsCoreTranslationBundle:bsTranslation')
				->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language );
			$translations = $this->getDoctrine()
				->getRepository('bsCoreTranslationBundle:bsTranslation')
				->getArrayTranslation( bsTranslationIDsUsers::T_PAGE_ADDUSERSCREEN, $language );
			$headTranslations = $this->getDoctrine()
				->getRepository('bsCoreTranslationBundle:bsTranslation')
				->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

			$form = $this->makeUserAddForm( $translations, $user, $userExtension[0], $userServices, true, $userAddresses ); // Modify


			$userRoles = $user->getRoles();
			if( $userRoles )
                $role = $userRoles[0];
			else
                $role = null;


            return $this->render('bsCoreUsersBundle:admin:addUserScreen.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'role' => $role,
                'userscale' => $userScale,
                'userFilesResume' => $userFilesResume,
                'action' => 'MODIFY',
                'curruser' => $bsUserSession->getUser(),
                'headTranslations' => $headTranslations,
                'partialmenuTranslations' => $partialMenuTranslations,
                'translations' => $translations,
                'passwordGlobalSettings' => $passwordGlobalSettings,
                'language' => $language,
                'currentMenu' => 22 ));

		} else {
			$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
		}

		return $this->redirect($this->generateUrl('bs_core_users_admin_list'));
	}

	public function domodifyAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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


        if ($request->isMethod('POST')) {
			$data =  $request->request->get('form');

			$user = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsUsers' )->find( $data['userId'] );
			if( !$user ){
				$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
				return $this->redirect( $this->generateUrl( 'bs_core_users_admin_list' ));
			}
			// Update user
			$user->setFirstname( $data['userFirstname']);
			$user->setLastname( $data['userLastname']);
			$user->setLogin( $data['userLogin'] );
			if( strlen( $data['userPassword'] ) > 0 )
				$user->setPassword( md5( $data['userPassword'] ) );

			$em = $this->getDoctrine()->getManager();

			// Remove old Role
			foreach( $user->getRoles() as $role ){
				$user->removeRole( $role );
				$role->removeUser( $user );
				$em->persist( $user );
				$em->persist( $role );
			}

			$role = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsRoles' )->find( intval( $data['userRole' ] ) );
			if( $role ){
				$user->addRole( $role );	// Tricky ManyToMany Symfony2 update thing ! (but it works !)
				$role->addUser( $user );
				$em->persist( $role );
				$em->persist( $user );

				// Remove old Rights
				foreach( $user->getRights() as $right ){
					$user->removeRight( $right );
					$right->removeUser( $user );
					$em->persist( $right );
					$em->persist( $user );
				}

				foreach( $role->getRights() as $right ){
					$user->addRight( $right );
					$right->addUser( $user );
					$em->persist( $right );
					$em->persist( $user );
				}
			}

			$em->flush();

			$userExtensionRet = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserExtensions' )->findBy( array( 'user' => $user->getId() ));

			if( !$userExtensionRet || !is_array($userExtensionRet) ){
				$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
				return $this->redirect( $this->generateUrl( 'bs_core_users_admin_list' ));
			}
			$userExtension = $userExtensionRet[0];
			// Update extension
			$userExtension->setInitials( strtoupper( $data['userInitials'] ) );

			$em->persist( $userExtension );
			$em->flush();

			$userServices = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserServices' )->findBy( array( 'user'  => $user->getId() ));
			foreach( $userServices as $userService ){
				$em->remove( $userService );
			}
			$em->flush();
			// Update Services linked
			$services = explode( ",", $data['userServices'] );

			// Remove all old links
			$this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserServices' )->delete( $user->getId() );

			foreach( $services as $serviceId ){
				$userService = new IDPUserServices();
				$userService->setUser( $user );
				$service = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPServices' )->find( intval( $serviceId ) );
				if( $service ){
					$userService->setService( $service );

					$em->persist( $userService );
					$em->flush();
				}
			}

			$userAddresses = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserAddresses' )->findBy( array( 'user' => $user->getId() ));
            foreach( $userAddresses as $userAddress ){
                $em->remove( $userAddress );
            }
            $em->flush();
            $addresses = explode( ",", $data['userAddresses'] );

            $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserAddresses' )->delete( $user->getId() );

            foreach( $addresses as $addressId ){
                $userAddress = new IDPUserAddresses();
                $userAddress->setUser( $user );
                $address = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDeliverAddress' )->find( intval( $addressId ) );
                if( $address ){
                    $userAddress->setAddress( $address );
                    $em->persist( $userAddress );
                    $em->flush();
                }
            }

			// $this->sendMessage( 'Utilisateur a été modifié avec succès.', 0, $bsUserSession->getUserId() );

		} else {
			$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
		}

		return $this->redirect($this->generateUrl('bs_core_users_admin_list'));
	}

	public function finetuneAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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

        if ($request->isMethod('POST')) {

			$userId =  intval( $request->request->get('userID') );

			$user = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsUsers' )->find( $userId );
			if( !$user ){
				$this->sendMessage( 'System Error: No userID for rights modification', 1, $bsUserSession->getUserId() );
				return $this->redirect($this->generateUrl('bs_core_users_admin_list'));
			}

			$partialMenuTranslations = $this->getDoctrine()
				->getRepository('bsCoreTranslationBundle:bsTranslation')
				->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_MENUMANAGEDBSCREEN, $language );
			$translations = $this->getDoctrine()
				->getRepository('bsCoreTranslationBundle:bsTranslation')
				->getArrayTranslation( bsTranslationIDsUsers::T_PAGE_USERFINETUNESCREEN, $language );
			$headTranslations = $this->getDoctrine()
				->getRepository('bsCoreTranslationBundle:bsTranslation')
				->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

            return $this->render('bsCoreUsersBundle:admin:finetuneUserScreen.html.twig', array(
                'user' => $user,
                'curruser' => $bsUserSession->getUser(),
                'userscale' => $userScale,
                'userFilesResume' => $userFilesResume,
                'headTranslations' => $headTranslations,
                'partialmenuTranslations' => $partialMenuTranslations,
                'translations' => $translations,
                'language' => $language,
                'currentMenu' => 22 ));

		} else {
			$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
		}

		return $this->redirect($this->generateUrl('bs_core_users_admin_list'));
	}


	// Role administration
	public function rolesListAction( Request $request )
	{
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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

        return $this->render('bsCoreUsersBundle:admin:role_list.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume ) );
	}

	private function makeRoleAddForm( $role = null, $modify = false )
	{
		$form = $this->createFormBuilder();

		if( $role == null ){
			$form->add( 'roleName', TextType::class, array ( 'label' => 'Nom', 'required' => true ));
			$form->add( 'roleDescription', TextType::class, array ( 'label' => 'Description', 'required' => false ));
			$form->add( 'roleScale', TextType::class, array ( 'label' => 'Echelle', 'required' => true ));
		} else {
			$form->add( 'roleId', HiddenType::class, array( 'data' => $role->getId() ));
			$form->add( 'roleName', TextType::class, array ( 'label' => 'Nom', 'required' => true, 'data' => $role->getName() ));
			$form->add( 'roleDescription', TextType::class, array ( 'label' => 'Description', 'required' => false, 'data' => $role->getDescription() ));
			$form->add( 'roleScale', TextType::class, array ( 'label' => 'Echelle', 'required' => true, 'data' => $role->getScale() ));
		}

		if( !$modify )
			$form->add( 'addRoleBtn', SubmitType::class, array( 'label' => 'Ajouter' ));
		else
			$form->add( 'modifyRoleBtn', SubmitType::class, array( 'label' => 'Modifier' ));

		return $form->getForm();
	}

	public function rolesAddAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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

        $form = $this->makeRoleAddForm( );

        return $this->render('bsCoreUsersBundle:admin:addRoleScreen.html.twig', array(
            'form' => $form->createView(),
            'role' => null,
            'action' => 'ADD',
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume ));
	}

	public function rolesDoAddAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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

        $data = array();
		if ($request->isMethod('POST')) {
			$data =  $request->request->get('form');

			// Create bsRoles entry
			$role = new bsRoles();
			$role->setName( $data['roleName']);
			$role->setDescription( $data['roleDescription']);
			$role->setScale( $data['roleScale'] );

			$em = $this->getDoctrine()->getManager();
			$em->persist( $role );
			$em->flush();

			$this->sendMessage( 'Rôle a été créé avec succès.', 0, $bsUserSession->getUserId() );
		} else {
			$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
		}

		return $this->redirect($this->generateUrl('bs_core_roles_admin_list'));
	}

	public function rolesModifyAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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

        if ($request->isMethod('POST')) {

			$roleId =  intval( $request->request->get('roleID') );

			$role = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsRoles' )->find( $roleId );
			if( !$role ){
				$this->sendMessage( 'System Error: No roleID for modification', 1, $bsUserSession->getUserId() );
				return $this->redirect($this->generateUrl('bs_core_roles_admin_list'));
			}

			$form = $this->makeRoleAddForm( $role, true ); // Modify

            return $this->render('bsCoreUsersBundle:admin:addRoleScreen.html.twig', array(
                'form' => $form->createView(),
                'role' => $role,
                'action' => 'MODIFY',
                'curruser' => $bsUserSession->getUser(),
                'userscale' => $userScale,
                'userFilesResume' => $userFilesResume ));

		} else {
			$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
		}

		return $this->redirect($this->generateUrl('bs_core_roles_admin_list'));
	}

	public function rolesDoModifyAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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

        if ($request->isMethod('POST')) {
			$data =  $request->request->get('form');

			$role = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsRoles' )->find( $data['roleId'] );
			if( !$role ){
				$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
				return $this->redirect( $this->generateUrl( 'bs_core_roles_admin_list' ));
			}
			// Update role
			$role->setName( $data['roleName']);
			$role->setDescription( $data['roleDescription']);
			$role->setScale( $data['roleScale'] );

			$em = $this->getDoctrine()->getManager();
			$em->persist( $role );
			$em->flush();

			$this->sendMessage( 'Rôle a été modifié avec succès.', 0, $bsUserSession->getUserId() );

		} else {
			$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
		}

		return $this->redirect($this->generateUrl('bs_core_roles_admin_list'));
	}

	public function rolesFinetuneAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_MANAGE_USERS][IDPArchimageRights::RIGHT_ID] ) ){
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

        if ($request->isMethod('POST')) {

			$roleId =  intval( $request->request->get('roleID') );

			$role = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:bsRoles' )->find( $roleId );
			if( !$role ){
				$this->sendMessage( 'System Error: No roleID for rights modification', 1, $bsUserSession->getUserId() );
				return $this->redirect($this->generateUrl('bs_core_roles_admin_list'));
			}

			return $this->render('bsCoreUsersBundle:admin:finetuneRoleScreen.html.twig', array(
			    'role' => $role,
                'curruser' => $bsUserSession->getUser(),
                'userscale' => $userScale,
                'userFilesResume' => $userFilesResume ));

		} else {
			$this->sendMessage( 'System Error', 1, $bsUserSession->getUserId() );
		}

		return $this->redirect($this->generateUrl('bs_core_roles_admin_list'));
	}

}
