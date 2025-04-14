<?php

namespace bs\Core\UsersBundle\Controller;

use \DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use bs\Core\TranslationBundle\Translation\bsTranslation;
use bs\Core\UsersBundle\Translation\bsTranslationIDsUsers;
use bs\Core\TranslationBundle\Translation\bsTranslationIDsCommon;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\Core\AdminBundle\Entity\bsAdminConfig;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalSettings;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class UsersController extends Controller
{
	// 30 minutes
	const BS_CORE_USER_SESSION_LIFETIME = 1800; // seconds

    public function loginAction( )
    {
    	$settings = $this->getDoctrine()
    		->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
    		->getArraySettings();

    	$translations = $this->getDoctrine()
    		->getRepository('bsCoreTranslationBundle:bsTranslation')
    		->getArrayTranslation( bsTranslationIDsUsers::T_PAGE_LOGINSCREEN, $settings[0]['default_language'] );

   		$form = $this->makeLoginForm( $translations );

   		$bsAdminConfig = $this->getDoctrine()
            ->getRepository('bsCoreAdminBundle:bsAdminconfig')
            ->findOneById( 1 );
        $versions = [ 'upToDate' => (( bsAdminconfig::CURRENT_SOFTWARE_VERSION == $bsAdminConfig->getSoftwareversion() )&&( bsAdminconfig::CURRENT_DATABASE_VERSION == $bsAdminConfig->getDatabaseversion() )),
            'target_sw'=>bsAdminconfig::CURRENT_SOFTWARE_VERSION,
            'current_sw'=> $bsAdminConfig->getSoftwareversion(),
            'target_bdd'=>bsAdminconfig::CURRENT_DATABASE_VERSION,
            'current_bdd'=> $bsAdminConfig->getDatabaseversion(),
            'dev_mode'=> $this->container->getParameter('kernel.environment') == 'dev'];

   		return $this->render('bsCoreUsersBundle:bsUsers:LoginScreen.html.twig', array(
   		    'form' => $form->createView(),
            'msg' => null, 'translations' => $translations,
            'versions' => $versions ));
   	}

   	public function checkloginAction( Request $request )
   	{
   		$settings = $this->getDoctrine()
   			->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
   			->getArraySettings();

   		$translations = $this->getDoctrine()
   			->getRepository('bsCoreTranslationBundle:bsTranslation')
   			->getArrayTranslation( bsTranslationIDsUsers::T_PAGE_LOGINSCREEN, $settings[0]['default_language'] );

        $bsAdminConfig = $this->getDoctrine()
            ->getRepository('bsCoreAdminBundle:bsAdminconfig')
            ->findOneById( 1 );
        $versions = [ 'upToDate' => (( bsAdminconfig::CURRENT_SOFTWARE_VERSION == $bsAdminConfig->getSoftwareversion() )&&( bsAdminconfig::CURRENT_DATABASE_VERSION == $bsAdminConfig->getDatabaseversion() )),
            'target_sw'=>bsAdminconfig::CURRENT_SOFTWARE_VERSION,
            'current_sw'=> $bsAdminConfig->getSoftwareversion(),
            'target_bdd'=>bsAdminconfig::CURRENT_DATABASE_VERSION,
            'current_bdd'=> $bsAdminConfig->getDatabaseversion(),
            'dev_mode'=> $this->container->getParameter('kernel.environment') == 'dev'];

        $data = array();
   		$form = $this->makeLoginForm( $translations );
   		if ($request->isMethod('POST')) {
   			$form->handleRequest($request);
   			// $data is a simply array with your form fields
   			$data = $form->getData();
   			$login = $data['bsUsersLogin'];
   			$password = $data['bsUsersPassword'];

   			$bsUsersRep = $this->getDoctrine()->getRepository('bsCoreUsersBundle:bsUsers');
   			$em = $this->getDoctrine()->getEntityManager();

   			$user = $bsUsersRep->verifyUser( $login, $password );
            
            if ( $user ) {

                $now_ = new DateTime();
                $now = $now_->getTimestamp();
   				if( $user->getConnected() )
   				{
   				    // verify if same phpsessid
                    if( $user->getPhpsessid() != $request->cookies->get('PHPSESSID') ) {
                        // Verify timeout autorization
                        if( $user->getLastaction() + self::BS_CORE_USER_SESSION_LIFETIME > $now ) {
                            return $this->render('bsCoreUsersBundle:bsUsers:LoginScreen.html.twig', array(
                                'form' => $form->createView(),
                                'msg' => $translations[bsTranslationIDsUsers::T_LOGINSCREEN_ALREADYCONNECTED],
                                'translations' => $translations,
                                'versions' => $versions));
                        } else {
                            $user->setPhpsessid($request->cookies->get('PHPSESSID'));
                        }
                    }
				} else {
   				    $user->setPhpsessid( $request->cookies->get('PHPSESSID') );
                }

   				// Everything good to connect user
				$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine(), $user );
				$bsUserSession->logUser( $user->getId() );

				$bsUserSession->getUser()->setConnected( true );
				$bsUserSession->getUser()->setLastaction( $now );
                $em = $this->getDoctrine()->getManager();
                $em->persist( $bsUserSession->getUser() );
                $em->flush();

				// If user is asked to change is password, just redirect him to this page
                if( $user->getChangepass() )
                    return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
                // Otherwise go to dashboard
				return $this->redirect($this->generateUrl('bs_idp_dashboard_homepage'));
   			} else
   				return $this->render('bsCoreUsersBundle:bsUsers:LoginScreen.html.twig', array(
   				    'form' => $form->createView(),
                    'msg' => $translations[bsTranslationIDsUsers::T_LOGINSCREEN_WRONGID],
                    'translations' => $translations,
                    'versions' => $versions ));
   		}

   		return $this->render('bsCoreUsersBundle:bsUsers:LoginScreen.html.twig', array(
   		    'form' => $form->createView(),
            'msg' => $translations[bsTranslationIDsUsers::T_LOGINSCREEN_ERROR],
            'translations' => $translations,
            'versions' => $versions ));
	}

	private function makeLoginForm( $translations )
	{
		$form = $this->createFormBuilder();
		// $form->add( 'redirect', HiddenType::class, array( 'data' => '' ));

		$form->add( 'bsUsersLogin', TextType::class , array (
		    'label' => $translations[bsTranslationIDsUsers::T_LOGINSCREEN_IDENTIFY],
            'constraints' => array( new Length(255), new NotBlank() )));
		$form->add( 'bsUsersPassword', PasswordType::class, array (
		    'label' => $translations[bsTranslationIDsUsers::T_LOGINSCREEN_PASSWORD],
            'constraints' => array( new Length(255), new NotBlank() )));

		$form->add( 'bsUsersLoginBtn', SubmitType::class, array( 'label' => $translations[bsTranslationIDsUsers::T_LOGINSCREEN_CONNECTBTN] ));

		return $form->getForm();
	}

	public function logoutAction( Request $request )
	{
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );

		$user = $bsUserSession->getUser();

		if( $bsUserSession->isUserLogged( false ) )
			$bsUserSession->unlogUser();
		else
			if( $user ){
				$user->setConnected( false );
				$this->getDoctrine()->getEntityManager()->persist( $user );
				$this->getDoctrine()->getEntityManager()->flush();
			}

		return $this->redirect( $this->generateUrl('bs_core_user_login'));
	}

	public function changemdpscreenAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $language = $bsUserSession->getUserExtension()->getLanguage();
/*
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_ADDUASCREEN, $language );
*/
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $passwordGlobalSettings = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPGlobalSettings' )
            ->getPasswordSettings();

        return $this->render('bsCoreUsersBundle:bsUsers:userChangeMdpScreen.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'passwordGlobalSettings' => $passwordGlobalSettings ) );
    }

    public function changemdpAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $language = $bsUserSession->getUserExtension()->getLanguage();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request

        if( $parameters->has( 'cuid' ) )
            $cuid = $parameters->get('cuid');
        else
            $cuid = null;
        if( $parameters->has( 'oldpwd' ) )
            $oldpwd = $parameters->get('oldpwd');
        else
            $oldpwd = null;
        if( $parameters->has( 'newpwd' ) )
            $newpwd = $parameters->get('newpwd');
        else
            $newpwd = null;

        if( $cuid != $bsUserSession->getUserId() ){
            return $this->render('bsCoreUsersBundle:bsUsers:userChangeMdpMessage.html.twig', array(
                'curruser' => $bsUserSession->getUser(),
                'userFilesResume' => $userFilesResume,
                'headTranslations' => $headTranslations,
                'message'=> "L'utilisateur connecté est incohérent avec cette demande !", 'type'=>1 ) );
        }

        if( !$bsUserSession->getUser()->getChangePass() && ( $oldpwd == null || $oldpwd == '' ) ){
            return $this->render('bsCoreUsersBundle:bsUsers:userChangeMdpMessage.html.twig', array(
                'curruser' => $bsUserSession->getUser(),
                'userFilesResume' => $userFilesResume,
                'headTranslations' => $headTranslations,
                'message'=> "L'ancien mot de passe ne peut pas être vide", 'type'=>1 ) );
        }

        if( !$bsUserSession->getUser()->getChangePass() && md5($oldpwd) != $bsUserSession->getUser()->getPassword() ){
            return $this->render('bsCoreUsersBundle:bsUsers:userChangeMdpMessage.html.twig', array(
                'curruser' => $bsUserSession->getUser(),
                'userFilesResume' => $userFilesResume,
                'headTranslations' => $headTranslations,
                'message'=> "L'ancien mot de passe ne correspond pas à celui enregistré !", 'type'=>1 ) );
        }

        $bsUserSession->getUser()->setPassword( md5( $newpwd ) );
        $bsUserSession->getUser()->setChangePass( false );
        $em = $this->getDoctrine()->getManager();
        $em->persist( $bsUserSession->getUser() );
        $em->flush();

        return $this->render('bsCoreUsersBundle:bsUsers:userChangeMdpMessage.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'userFilesResume' => $userFilesResume,
            'headTranslations' => $headTranslations,
            'message'=> "Votre nouveau mot de passe est désormais actif !", 'type'=>0 ) );
    }

}
