<?php
// (c) Archimage - IDP Consulting 2016
// Author: Cyril PATRIGEON - BeSecure Labs
//

namespace bs\IDP\ArchiveBundle\Controller;

use \DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\Core\AdminBundle\Security\IDPArchimageRights;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;
use bs\IDP\DashboardBundle\Entity\bsMessages;
use bs\IDP\ArchiveBundle\Entity\IDPAudit;

use bs\IDP\ArchiveBundle\Form\Type\IDPArchiveType;
use bs\IDP\ArchiveBundle\Form\Type\IDPCustTransferType;

use bs\IDP\ArchiveBundle\Translation\bsTranslationIDsArchive;
use bs\Core\TranslationBundle\Translation\bsTranslationIDsCommon;
use bs\IDP\BackofficeBundle\Translation\bsTranslationIDsBackoffice;

use bs\IDP\ArchiveBundle\Common\IDPManageContainerBox;

use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class DefaultController extends Controller
{
	public function viewAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight(  IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_ARCHIVE_NEW][IDPArchimageRights::RIGHT_ID]  ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        if ($request->isMethod('POST')) {

			$translations = $this->getDoctrine()
				->getRepository('bsCoreTranslationBundle:bsTranslation')
				->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_ADDUASCREEN, $language );
			$headTranslations = $this->getDoctrine()
				->getRepository('bsCoreTranslationBundle:bsTranslation')
				->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

			$settings = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
				->getArraySettings();

			$parameters = $request->request;

			$archive_id = $parameters->get('id');
			$archive_id = (strlen($archive_id)>0)?intval($archive_id):null;

			if( $archive_id ){
				$uaarray = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive')
					->getArray( $archive_id );
			} else {
				return $this->redirect( $this->generateUrl('bs_idp_dashboard_homepage' ));
			}

            return $this->render('bsIDPArchiveBundle:Default:view.html.twig', array(
                'curruser' => $bsUserSession->getUser(),
                'userscale' => $userScale,
				'userFilesResume' => $userFilesResume,
				'uaid' => $archive_id,
                'ua' => $uaarray,
                'token' => 'abcd',
                'settings' => $settings,
                'headTranslations' => $headTranslations,
                'translations' => $translations ));

		} else {
			return $this->redirect( $this->generateUrl('bs_idp_dashboard_homepage' ));
		}

	}

	public function newAction(Request $request)
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

		if( !$bsUserSession->isUserGotRight(  IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_ARCHIVE_NEW][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $logger = $this->container->getParameter('kernel.environment') == 'dev' ?$this->container->get('logger'):null;

		$translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_ADDUASCREEN, $language );
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

		$settings = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
			->getArraySettings();

		$idparchive = $this->getDoctrine()
			->getRepository('bsIDPArchiveBundle:IDPArchive')
			->getArchivesWithStatuses( ['NAV'], $bsUserSession->getUserId(), true );

		$em = $this->getDoctrine()->getManager();

        // Get User ASF to copy all wanted fields
        $user_asf = $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserAutoSaveFields')->findOneBy(array('user_id' => $bsUserSession->getUserId()));
        $user_asf_array = $user_asf ? $this->getDoctrine()->getRepository('bsCoreUsersBundle:IDPUserAutoSaveFields')->getArray( $user_asf->getId() ) : null;

        // If no result found, we are in creation mode, else we are in edition mode
		if( $idparchive == null )
		{
			$lastarchive = $this->getDoctrine()
				->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
				->getLastEnteredArchive( $bsUserSession->getUserId() );

			$idparchive = new IDPArchive();
			$idparchive->setCreatedat( new DateTime() );
			$userExtension = $bsUserSession->getUserExtension();
			$ordernumber = sprintf( '%s%05d', $userExtension->getInitials(), $userExtension->getUacounter()+1 );
			$userExtension->setUacounter( $userExtension->getUacounter()+1 );
			$em->persist( $userExtension );
			$em->flush();
			// Automatic save of UA
			$idparchive->setOrdernumber( $ordernumber );
			$idparchive->setOwner( $bsUserSession->getUser() );
//			$idparchive->setClosureyear(date("Y"));
//			$idparchive->setDestructionyear(date("Y"));
			$idparchive->setLastactionby( $bsUserSession->getUser() );
			$Status = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('NAV');
			$idparchive->setStatus($Status);

            if( $user_asf ) {   // Copy Only if ASF is set
                if ($lastarchive != null) {
                    if ( $user_asf->getAsfService() )
                        $idparchive->setService($lastarchive->getService());
                    if ( $user_asf->getAsfLegalentity() )
                        $idparchive->setLegalentity($lastarchive->getLegalentity());
                    if ( $user_asf->getAsfBudgetcode() )
                        $idparchive->setBudgetcode($lastarchive->getBudgetcode());
                    if ( $user_asf->getAsfDocumentnature() )
                        $idparchive->setDocumentnature($lastarchive->getDocumentnature());
                    if ( $user_asf->getAsfDocumenttype() )
                        $idparchive->setDocumenttype($lastarchive->getDocumenttype());
                    if ( $user_asf->getAsfDescription1() )
                        $idparchive->setDescription1($lastarchive->getDescription1());
                    if ( $user_asf->getAsfDescription2() )
                        $idparchive->setDescription2($lastarchive->getDescription2());
                    if ( $user_asf->getAsfClosureyear() )
                        $idparchive->setClosureyear($lastarchive->getClosureyear());
                    if ( $user_asf->getAsfDestructionyear() )
                        $idparchive->setDestructionyear($lastarchive->getDestructionyear());
                    if ( $user_asf->getAsfFilenumber() )
                        $idparchive->setDocumentnumber($lastarchive->getDocumentnumber());
                    if ( $user_asf->getAsfBoxnumber() )
                        $idparchive->setBoxnumber($lastarchive->getBoxnumber());
                    if ( $user_asf->getAsfContainernumber() )
                        $idparchive->setContainernumber($lastarchive->getContainernumber());
                    if ( $user_asf->getAsfProvider() )
                        $idparchive->setProvider($lastarchive->getProvider());
                    if ( $user_asf->getAsfLimitsdate() ) {
                        $idparchive->setLimitdatemin($lastarchive->getLimitdatemin());
                        $idparchive->setLimitdatemax($lastarchive->getLimitdatemax());
                    }
                    if ( $user_asf->getAsfLimitsnum() ) {
                        $idparchive->setLimitnummin($lastarchive->getLimitnummin());
                        $idparchive->setLimitnummax($lastarchive->getLimitnummax());
                    }
                    if ( $user_asf->getAsfLimitsalpha() ) {
                        $idparchive->setLimitalphamin($lastarchive->getLimitalphamin());
                        $idparchive->setLimitalphamax($lastarchive->getLimitalphamax());
                    }
                    if ( $user_asf->getAsfLimitsalphanum() ) {
                        $idparchive->setLimitalphanummin($lastarchive->getLimitalphanummin());
                        $idparchive->setLimitalphanummax($lastarchive->getLimitalphanummax());
                    }
                    if ( $user_asf->getAsfName() )
                        $idparchive->setName($lastarchive->getName());
                }
            }
            
			$em->persist($idparchive);
			$em->flush();
		} else {
            $idparchive = $idparchive[0];
        }

		$uaarray = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive')
			->getArray( $idparchive->getId() );

		return $this->render('bsIDPArchiveBundle:Default:new.html.twig', array(
            'modify' => 0,
            'curruser' => $bsUserSession->getUser(),
			'user_id' => $bsUserSession->getUser()->getId(),
            'user_asf_array' => $user_asf_array,
			'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'uaid' => $idparchive->getId(),
            'ua' => $uaarray,
            'token' => 'abcd',
            'settings' => $settings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'currentMenu' => 1 ));

	}

	protected function modifyArchive( $idparchive, $parameters, $systemTranslations, $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );

		$service_id = $parameters->get('service');
		$service_id = (strlen($service_id)>0)?intval($service_id):null;
		$legalentity_id = $parameters->get('legal_entity');
		$legalentity_id = (strlen($legalentity_id)>0)?intval($legalentity_id):null;
		$budgetcode_id = $parameters->get('budget_code');
		$budgetcode_id = (strlen($budgetcode_id)>0)?intval($budgetcode_id):null;
		$documentnature_id = $parameters->get('document_nature');
		$documentnature_id = (strlen($documentnature_id)>0)?intval($documentnature_id):null;
		$documenttype_id = $parameters->get('document_type');
		$documenttype_id = (strlen($documenttype_id)>0)?intval($documenttype_id):null;
		$description1_id = $parameters->get('description1');
		$description1_id = (strlen($description1_id)>0)?intval($description1_id):null;
		$description2_id = $parameters->get('description2');
		$description2_id = (strlen($description2_id)>0)?intval($description2_id):null;
		$closureyear = $parameters->get('closure_year');
		$closureyear = (strlen($closureyear)>0)?intval($closureyear):null;
		$destructionyear = $parameters->get('destruction_year');
		$destructionyear = (strlen($destructionyear)>0)?intval($destructionyear):null;
		$documentnumber = $parameters->get('document_number');
        $documentnumber = ($documentnumber && strlen(trim($documentnumber))!=0)?trim($documentnumber):null;
		$boxnumber = $parameters->get('box_number');
        $boxnumber = ($boxnumber && strlen(trim($boxnumber))!=0)?trim($boxnumber):null;
		$containernumber = $parameters->get('container_number');
        $containernumber = ($containernumber && strlen(trim($containernumber))!=0)?trim($containernumber):null;
		$provider_id = $parameters->get('provider');
		$provider_id = (strlen($provider_id)>0)?intval($provider_id):null;
		$ordernumber = $parameters->get('order_number');
		$ordernumber = (strlen($ordernumber)>0)?$ordernumber:null;
		$name = $parameters->get('name');
		$name = (strlen($name)>0)?$name:null;
		$limitdatemin = $parameters->get('limit_date_min');
		$limitdatemin = (strlen($limitdatemin)>0)?$limitdatemin:null;
		$limitdatemax = $parameters->get('limit_date_max');
		$limitdatemax = (strlen($limitdatemax)>0)?$limitdatemax:null;
		$limitnummin = $parameters->get('limit_num_min');
		$limitnummin = (strlen($limitnummin)>0)?$limitnummin:null;
		$limitnummax = $parameters->get('limit_num_max');
		$limitnummax = (strlen($limitnummax)>0)?$limitnummax:null;
		$limitalphamin = $parameters->get('limit_alpha_min');
		$limitalphamin = (strlen($limitalphamin)>0)?$limitalphamin:null;
		$limitalphamax = $parameters->get('limit_alpha_max');
		$limitalphamax = (strlen($limitalphamax)>0)?$limitalphamax:null;
		$limitalphanummin = $parameters->get('limit_alphanum_min');
		$limitalphanummin = (strlen($limitalphanummin)>0)?$limitalphanummin:null;
		$limitalphanummax = $parameters->get('limit_alphanum_max');
		$limitalphanummax = (strlen($limitalphanummax)>0)?$limitalphanummax:null;

		$service = null;
		if( $service_id ){
			$service = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPServices' )
				->find( $service_id );
			if( !$service ){
				return null;
			}
		}
		$idparchive->setService( $service );

		$legalentity = null;
		if( $legalentity_id ){
			$legalentity = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPLegalEntities' )
				->find( $legalentity_id );
			if( !$legalentity ){
				return null;
			}
		}
		$idparchive->setLegalentity( $legalentity );

		$budgetcode = null;
		if( $budgetcode_id ){
			$budgetcode = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPBudgetCodes' )
				->find( $budgetcode_id );
			if( !$budgetcode ){
				return null;
			}
		}
		$idparchive->setBudgetcode( $budgetcode );

		$documentnature = null;
		if( $documentnature_id ){
			$documentnature = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDocumentNatures' )
				->find( $documentnature_id );
			if( !$documentnature ){
				return null;
			}
		}
		$idparchive->setDocumentnature( $documentnature );

		$documenttype = null;
		if( $documenttype_id ){
			$documenttype = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDocumentTypes' )
				->find( $documenttype_id );
			if( !$documenttype ){
				return null;
			}
		}
		$idparchive->setDocumenttype( $documenttype );

		$description1 = null;
		if( $description1_id ){
			$description1 = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions1')
				->find( $description1_id );
			if( !$description1 ){
				return null;
			}
		}
		$idparchive->setDescription1( $description1 );

		$description2 = null;
		if( $description2_id ){
			$description2 = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions2' )
				->find( $description2_id );
			if( !$description2_id ){
				return null;
			}
		}
		$idparchive->setDescription2( $description2 );

		if( $closureyear ){
			if((string)(int)$closureyear != $closureyear )
				$closureyear = null;
		}
		$idparchive->setClosureyear( $closureyear );

		if( $destructionyear ){
			if((string)(int)$destructionyear != $destructionyear )
				$destructionyear = null;
		}
		$idparchive->setDestructionyear( $destructionyear );

		$idparchive->setDocumentnumber( $documentnumber );

		$idparchive->setBoxnumber( $boxnumber );

		$idparchive->setContainernumber( $containernumber );

		$provider = null;
		if( $provider_id ){
			$provider = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPProviders' )
				->find( $provider_id );
			if( !$provider ){
				return null;
			}
		}
		$idparchive->setProvider( $provider );

		$idparchive->setName( $name );

		$limitdatemin_ = null;
		if( $limitdatemin ){
			$limitdatemin_ = DateTime::createFromFormat( 'd/m/Y', $limitdatemin );
		}
		$idparchive->setLimitdatemin( $limitdatemin_ );

		$limitdatemax_ = null;
		if( $limitdatemax ){
			$limitdatemax_ = DateTime::createFromFormat( 'd/m/Y', $limitdatemax );
		}
		$idparchive->setLimitdatemax( $limitdatemax_ );

		$idparchive->setLimitalphamin( $limitalphamin );

		$idparchive->setLimitalphamax( $limitalphamax );

		$idparchive->setLimitnummin( $limitnummin );

		$idparchive->setLimitnummax( $limitnummax );

		$idparchive->setLimitalphanummin( $limitalphanummin );

		$idparchive->setLimitalphanummax( $limitalphanummax );

		return $idparchive;

	}

	public function donewAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight(  IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_ARCHIVE_NEW][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        if ($request->isMethod('POST')) {

			// $_GET parameters
			// $parameters = $request->query;
			// $_POST parameters
			$parameters = $request->request;

			$idparchive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
				->find( $parameters->get('id') );
			if( !$idparchive ){
				return $this->redirect( $this->generateUrl('bs_idp_dashboard_homepage' ));
			}

			$idparchive = $this->modifyArchive( $idparchive, $parameters, $systemTranslations, $request );
			if( !$idparchive )
				return $this->redirect( $this->generateUrl('bs_idp_dashboard_homepage' ));

			$newStatus = 'NAV';
			// Or is it another button ?
			if(( $parameters->get('from') == 'validate' )||( $parameters->get('from') == 'print' ))
				$newStatus = 'DTA';
			if( $parameters->get('from') == 'modify' )
				$newStatus = null;

			if( $newStatus != null ){
				$Status = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname($newStatus);
				$idparchive->setStatus($Status);
				$em = $this->getDoctrine()->getManager();
				$em->persist($idparchive);
				$em->flush();

				// Add an archive creation to audit table
				$this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addOneAuditEntry(
				    IDPConstants::AUDIT_ACTION_CREATE,      // action
                    $bsUserSession->getUserId(),            // user_id
                    IDPConstants::FIELD_NA,                 // field
                    IDPConstants::ENTITY_ARCHIVE,           // entity
                    $idparchive->getId(),                   // id of entity concerned
                    null,                                   // new string value
                    null,                                   // new int value
                    null,                                   // old string value
                    null,                                   // old int value
                    null,                                   // link to complete UA values
                    null                                    // during creation we do not need to backup contain type
                );
			}

			return $this->redirect($this->generateUrl('bs_idp_archive_new'));

		} else {
			return $this->redirect( $this->generateUrl('bs_idp_dashboard_homepage' ));
		}

		return $this->redirect( $this->generateUrl('bs_idp_dashboard_homepage' ));
	}

	public function modifyAction( $archiveId ){

		$request = $this->get('request');

		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_ARCHIVE_NEW][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $settings = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
			->getArraySettings();
		$translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_ADDUASCREEN, $language );
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

		if (!$idparchive = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive')->findOneBy( array( 'id' => $archiveId ) ) )
		{
			return $this->redirect($this->generateUrl('bs_idp_dashboard_homepage'));
		}

		$uaarray = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive')
			->getArray( $idparchive->getId() );

        return $this->render('bsIDPArchiveBundle:Default:new.html.twig', array(
            'modify' => 1,
            'curruser' => $bsUserSession->getUser(),
		    'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'uaid' => $idparchive->getId(),
            'ua' => $uaarray,
            'token' => 'abcd',
            'settings' => $settings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'currentMenu' => 1 ));
	}

	public function domodifyAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_ARCHIVE_NEW][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

		if ($request->isMethod('POST')) {

			// $_GET parameters
			// $parameters = $request->query;
			// $_POST parameters
			$parameters = $request->request;

			$idparchive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
				->find( $parameters->get('id') );
			if( !$idparchive ){
				return $this->redirect( $this->generateUrl('bs_idp_dashboard_homepage' ));
			}

			$idparchive = $this->modifyArchive( $idparchive, $parameters, $systemTranslations, $request );
			if( !$idparchive )
				return $this->redirect( $this->generateUrl('bs_idp_dashboard_homepage' ));

			$em = $this->getDoctrine()->getManager();
			$em->persist($idparchive);
			$em->flush();

			return $this->redirect($this->generateUrl('bs_idp_dashboard_homepage'));

		} else {
			return $this->redirect( $this->generateUrl('bs_idp_dashboard_homepage' ));
		}
	}

	public function transferScreenAction( Request $request )
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

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_TRANSFER][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $commonSettings = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
			->getCommonSettings( $bsUserSession );

		$translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_TRANSFERSCREEN, $language );
		$overlay = $this->getDoctrine()
			->getRepository( 'bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_OVERLAY_VIEWARCHIVE, $language );
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );
        $tableTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsBackoffice::T_ARRAY_FIELD_NAMES, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );
        $searchTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABSEARCH, $language );
        $filterTanslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABFILTER, $language );
        $resultTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABRESULTS, $language );

        $whoname = $bsUserSession->getUser()->getFirstname() . ' ' . $bsUserSession->getUser()->getLastname();
        $form = $this->makePrecisionForm( $precisionTranslations, $whoname, 2, $bsUserSession->getUserId() );

        return $this->render('bsIDPArchiveBundle:Default:transferScreen.html.twig', array(
            'form' => $form->createView(),
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'commonsettings' => $commonSettings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'tableTranslations' => $tableTranslations,
            'precisionTranslations' => $precisionTranslations,
            'searchTranslations' => $searchTranslations,
            'filterTranslations' => $filterTanslations,
            'resultTranslations' => $resultTranslations,
            'language' => $language,
            'overlay' => $overlay,
            'currentMenu' => 2 ));
	}

	public function doCustomerTransferAction( Request $request )
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

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_TRANSFER][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();
		$translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_TRANSFERSCREEN, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );

        $data = array();
        $form = $this->makePrecisionForm( $precisionTranslations, null, 2, $bsUserSession->getUserId() );
		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			// $data is a simply array with your form fields
			$data = $form->getData();
			$ids = json_decode( $data['ids'], true );
			$em = $this->getDoctrine()->getManager();
			$rep = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive');

            $precisiondate = new DateTime();
            $precisiondate = DateTime::createFromFormat ( 'd/m/Y', $data['precisiondate'] );
            $precisionwhere = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')->findOneById( $data['precisionwhere'] );
            $precisionfloor = $data['precisionfloor'];
            $precisionoffice = $data['precisionoffice'];
            $precisionwho = $data['precisionwho'];
            $precisioncomment = $data['precisioncomment'];
            $now = new DateTime();

            $audit = [];

			$status = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DTRI');
			foreach( $ids['service'] as $archid ){
				$idparchive = $rep->findOneById( $archid );

				$idparchive->setLastActionBy( $bsUserSession->getUser() );
                $idparchive->setModifiedat( $now );
                $idparchive->setPrecisiondate( $precisiondate );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'obect_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $status->getId(),
                    'old_str' => null,
                    'old_int' => $idparchive->getStatus()->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setStatus( $status );

				$em->persist( $idparchive );

			}

			$status = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DTRINT');
			foreach( $ids['intermediate'] as $archid ){
                $idparchive = $rep->findOneById( $archid );

                $idparchive->setLastActionBy( $bsUserSession->getUser() );
                $idparchive->setModifiedat( $now );
                $idparchive->setPrecisiondate( $precisiondate );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $status->getId(),
                    'old_str' => null,
                    'old_int' => $idparchive->getStatus()->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setStatus( $status );

                $em->persist( $idparchive );

			}

			$status = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DTRP');
			foreach( $ids['provider'] as $archid ){
                $idparchive = $rep->findOneById( $archid );

                $idparchive->setLastActionBy( $bsUserSession->getUser() );
                $idparchive->setModifiedat( $now );
                $idparchive->setPrecisiondate( $precisiondate );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $status->getId(),
                    'old_str' => null,
                    'old_int' => $idparchive->getStatus()->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null

                ];
                $idparchive->setStatus( $status );

                $em->persist( $idparchive );

			}

            $em->flush();

            $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );
		}

		return $this->redirect($this->generateUrl('bs_idp_archive_transferscreen'));
	}

	public function searchAction( Request $request )
	{
	}

	public function askconsultScreenAction( Request $request )
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

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_CONSULT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $commonsettings = $this->getDoctrine()
			->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
			->getCommonSettings( $bsUserSession );

		$searchTranslations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABSEARCH, $language );
		$filterTanslations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABFILTER, $language );
		$resultTranslations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABRESULTS, $language );
	    $translations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_CONSULTSCREEN, $language );
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );
		$overlay = $this->getDoctrine()
			->getRepository( 'bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_OVERLAY_VIEWARCHIVE, $language );
        $tableTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsBackoffice::T_ARRAY_FIELD_NAMES, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );


        $whoname = $bsUserSession->getUser()->getFirstname() . ' ' . $bsUserSession->getUser()->getLastname();
        $form = $this->makePrecisionForm( $precisionTranslations, $whoname, 3, $bsUserSession->getUserId() );

        return $this->render( 'bsIDPArchiveBundle:Default:askConsult.html.twig' , array(
            'form' => $form->createView(),
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'commonsettings' => $commonsettings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'searchTranslations' => $searchTranslations,
            'filterTranslations' => $filterTanslations,
            'resultTranslations' => $resultTranslations,
            'language' => $language,
            'tableTranslations' => $tableTranslations,
            'precisionTranslations' => $precisionTranslations,
            'overlay' => $overlay,
            'currentMenu' => 3 ));
	}

	public function doAskConsultAction( Request $request )
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

		if( !$bsUserSession->isUserGotRight(  IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_CONSULT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_CONSULTSCREEN, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );

        $data = array();
        $form = $this->makePrecisionForm( $precisionTranslations, null, 3, $bsUserSession->getUserId() );
		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			// $data is a simply array with your form fields
			$data = $form->getData();
			$ids = json_decode( $data['ids'], true );
			$em = $this->getDoctrine()->getManager();
			$rep = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive');

            $precisiondate = new DateTime();
            $precisiondate = DateTime::createFromFormat ( 'd/m/Y', $data['precisiondate'] );
            $precisionwhere = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')->findOneById( $data['precisionwhere'] );
            $precisionfloor = $data['precisionfloor'];
            $precisionoffice = $data['precisionoffice'];
            $precisionwho = $data['precisionwho'];
            $precisioncomment = $data['precisioncomment'];
            $now = new DateTime();

			$statusDISI = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DISI');
			$statusDISINT = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DISINT');
			$statusDISP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DISP');

			$statusCLAI = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CLAI');
   			$statusCPAI = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CPAI');
			$statusCLAINT = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CLAINT');
			$statusCPAINT = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CPAINT');
			$statusCLAP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CLAP');
			$statusCPAP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CPAP');

			$audit = [];

			foreach( $ids['deliver'] as $archid ){
				$idparchive = $rep->findOneById( $archid );

                $idparchive->setLastActionBy( $bsUserSession->getUser() );
				$oldStatus = $idparchive->getStatus();
				$newStatus = null;
				if( $oldStatus == $statusDISI ) $newStatus = $statusCLAI;
				if( $oldStatus == $statusDISINT ) $newStatus = $statusCLAINT;
				if( $oldStatus == $statusDISP ) $newStatus = $statusCLAP;

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $newStatus===null?null:$newStatus->getId(),
                    'old_str' => null,
                    'old_int' => $oldStatus===null?null:$oldStatus->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                if( $newStatus ) $idparchive->setStatus( $newStatus );

                $idparchive->setModifiedat( $now );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisiondate( $precisiondate );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONOFFICE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionoffice,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

				$em->persist( $idparchive );
                $em->flush();
			}

			foreach( $ids['prepare'] as $archid ){
				$idparchive = $rep->findOneById( $archid );

                $idparchive->setLastActionBy( $bsUserSession->getUser() );
				$oldStatus = $idparchive->getStatus();
				$newStatus = null;
				if( $oldStatus == $statusDISI ) $newStatus = $statusCPAI;
				if( $oldStatus == $statusDISINT ) $newStatus = $statusCPAINT;
				if( $oldStatus == $statusDISP ) $newStatus = $statusCPAP;

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $newStatus===null?null:$newStatus->getId(),
                    'old_str' => null,
                    'old_int' => $oldStatus===null?null:$oldStatus->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setStatus( $newStatus );

                $idparchive->setModifiedat( $now );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisiondate( $precisiondate );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONOFFICE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionoffice,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

                $em->persist( $idparchive );
                $em->flush();
			}

            $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );
		}

        //return null; // debug purpose
		return $this->redirect($this->generateUrl('bs_idp_archive_askconsult_screen'));
	}

	public function askreturnScreenAction( Request $request )
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

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RETURN][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $commonsettings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->getCommonSettings( $bsUserSession );

		$searchTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABSEARCH, $language );
		$filterTanslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABFILTER, $language );
		$resultTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABRESULTS, $language );
	    $translations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_RETURNSCREEN, $language );
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );
		$overlay = $this->getDoctrine()
			->getRepository( 'bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_OVERLAY_VIEWARCHIVE, $language );
        $tableTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsBackoffice::T_ARRAY_FIELD_NAMES, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );

        $whoname = $bsUserSession->getUser()->getFirstname() . ' ' . $bsUserSession->getUser()->getLastname();
        $form = $this->makePrecisionForm( $precisionTranslations, $whoname, 4, $bsUserSession->getUserId() );

        return $this->render( 'bsIDPArchiveBundle:Default:askReturn.html.twig' , array(
            'form' => $form->createView(),
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'commonsettings' => $commonsettings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'searchTranslations' => $searchTranslations,
            'filterTranslations' => $filterTanslations,
            'resultTranslations' => $resultTranslations,
            'language' => $language,
            'tableTranslations' => $tableTranslations,
            'precisionTranslations' => $precisionTranslations,
            'overlay' => $overlay,
            'currentMenu' => 4 ));
	}

	public function doAskReturnAction( Request $request )
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

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RETURN][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_RETURNSCREEN, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );

        $logger = $this->container->getParameter('kernel.environment') == 'dev'?$this->container->get('logger'):null;

        $data = array();
        $form = $this->makePrecisionForm( $precisionTranslations, null, 4, $bsUserSession->getUserId() );
		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			// $data is a simply array with your form fields
			$data = $form->getData();
			$ids = json_decode( $data['ids'], true );
			$em = $this->getDoctrine()->getManager();
			$rep = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive');

            $precisiondate = new DateTime();
            $precisiondate = DateTime::createFromFormat ( 'd/m/Y', $data['precisiondate'] );
            $precisionwhere = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')->findOneById( $data['precisionwhere'] );
            $precisionfloor = $data['precisionfloor'];
            $precisionoffice = $data['precisionoffice'];
            $precisionwho = $data['precisionwho'];
            $precisioncomment = $data['precisioncomment'];
            $now = new DateTime();

			$statusCONI = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CONI');
			$statusCONINT = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CONINT');
			$statusCONP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CONP');
			$statusCONRIDISP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CONRIDISP');
            $statusCONRINTDISP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CONRINTDISP');
            $statusCONRICONP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CONRICONP');
            $statusCONRINTCONP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CONRINTCONP');

			$statusCRAI = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CRAI');
			$statusCRAINT = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CRAINT');
			$statusCRAP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CRAP');
			$statusCRAPCONRIDISP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CRAPCONRIDISP');
			$statusCRAPCONRINTDISP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CRAPCONRINTDISP');
            $statusCRAPCONRICONP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CRAPCONRICONP');
            $statusCRAPCONRINTCONP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CRAPCONRINTCONP');

			$audit = [];

			foreach( $ids['return'] as $archid ){
				$idparchive = $rep->findOneById( $archid );

                $idparchive->setLastActionBy( $bsUserSession->getUser() );
				$oldStatus = $idparchive->getStatus();
				$newStatus = null;
				if( $oldStatus == $statusCONI ) $newStatus = $statusCRAI;
				if( $oldStatus == $statusCONINT ) $newStatus = $statusCRAINT;
				if( $oldStatus == $statusCONP ) $newStatus = $statusCRAP;
				if( $oldStatus == $statusCONRIDISP ) $newStatus = $statusCRAPCONRIDISP;
				if( $oldStatus == $statusCONRINTDISP ) $newStatus = $statusCRAPCONRINTDISP;
				if( $oldStatus == $statusCONRICONP ) $newStatus = $statusCRAPCONRICONP;
				if( $oldStatus == $statusCONRINTCONP ) $newStatus = $statusCRAPCONRINTCONP;
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $newStatus===null?null:$newStatus->getId(),
                    'old_str' => null,
                    'old_int' => $oldStatus===null?null:$oldStatus->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setStatus( $newStatus );

                $idparchive->setModifiedat( $now );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisiondate( $precisiondate );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONOFFICE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionoffice,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

                if( ( $oldStatus == $statusCONRICONP ) || ( $oldStatus == $statusCONRINTCONP ) || ( $oldStatus == $statusCONRIDISP ) || ( $oldStatus == $statusCONRINTDISP ) ){
                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATION,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $idparchive->getId(),
                        'new_str' => null,
                        'new_int' => $idparchive->getOldlocalization()===null?null:$idparchive->getOldlocalization()->getId(),
                        'old_str' => null,
                        'old_int' => $idparchive->getLocalization()===null?null:$idparchive->getLocalization()->getId(),
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];
                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $idparchive->getId(),
                        'new_str' => $idparchive->getOldlocalizationfree(),
                        'new_int' => null,
                        'old_str' => $idparchive->getLocalizationfree(),
                        'old_int' => null,
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];
                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_OLDLOCALIZATION,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $idparchive->getId(),
                        'new_str' => null,
                        'new_int' => $idparchive->getLocalization()===null?null:$idparchive->getLocalization()->getId(),
                        'old_str' => null,
                        'old_int' => $idparchive->getOldlocalization()===null?null:$idparchive->getOldlocalization()->getId(),
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];
                    $audit[] = [
                        'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                        'user_id' => $bsUserSession->getUserId(),
                        'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                        'entity' => IDPConstants::ENTITY_ARCHIVE,
                        'entity_id' => $idparchive->getId(),
                        'new_str' => $idparchive->getLocalizationfree(),
                        'new_int' => null,
                        'old_str' => $idparchive->getOldlocalizationfree(),
                        'old_int' => null,
                        'complete_ua_id' => null,
                        'object_type' => null
                    ];

                    $tempReloc = $idparchive->getLocalization();
                    $tempRelocfree = $idparchive->getLocalizationfree();
                    $idparchive->setLocalization( $idparchive->getOldlocalization() );
                    $idparchive->setLocalizationfree( $idparchive->getOldlocalizationfree() );
                    $idparchive->setOldlocalization( $tempReloc );
                    $idparchive->setOldlocalizationfree( $tempRelocfree );

                }

                $em->persist( $idparchive );
                $em->flush();
			}

            $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );
		}

		//return null;
		return $this->redirect($this->generateUrl('bs_idp_archive_askreturn_screen'));
	}

	public function askexitScreenAction( Request $request )
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

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_EXIT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $commonsettings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->getCommonSettings( $bsUserSession );

		$searchTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABSEARCH, $language );
		$filterTanslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABFILTER, $language );
		$resultTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABRESULTS, $language );
	    $translations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_EXITSCREEN, $language );
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );
		$overlay = $this->getDoctrine()
			->getRepository( 'bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_OVERLAY_VIEWARCHIVE, $language );
        $tableTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsBackoffice::T_ARRAY_FIELD_NAMES, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );

        $whoname = $bsUserSession->getUser()->getFirstname() . ' ' . $bsUserSession->getUser()->getLastname();
        $form = $this->makePrecisionForm( $precisionTranslations, $whoname, 5, $bsUserSession->getUserId() );

        return $this->render( 'bsIDPArchiveBundle:Default:askExit.html.twig' , array(
            'form' => $form->createView(),
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'commonsettings' => $commonsettings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'searchTranslations' => $searchTranslations,
            'filterTranslations' => $filterTanslations,
            'resultTranslations' => $resultTranslations,
            'language' => $language,
            'tableTranslations' => $tableTranslations,
            'precisionTranslations' => $precisionTranslations,
            'overlay' => $overlay,
            'currentMenu' => 5 ));
	}

	public function doAskExitAction( Request $request )
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

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_EXIT][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_EXITSCREEN, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );

        $data = array();
        $form = $this->makePrecisionForm( $precisionTranslations, null, 5, $bsUserSession->getUserId() );
		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			// $data is a simply array with your form fields
			$data = $form->getData();
			$ids = json_decode( $data['ids'], true );
			$em = $this->getDoctrine()->getManager();
			$rep = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive');

            $precisiondate = new DateTime();
            $precisiondate = DateTime::createFromFormat ( 'd/m/Y', $data['precisiondate'] );
            $precisionwhere = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')->findOneById( $data['precisionwhere'] );
            $precisionfloor = $data['precisionfloor'];
            $precisionoffice = $data['precisionoffice'];
            $precisionwho = $data['precisionwho'];
            $precisioncomment = $data['precisioncomment'];
            $now = new DateTime();

			$statusDISI = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DISI');
			$statusDISINT = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DISINT');
			$statusDISP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DISP');

			$statusCSAI = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CSAI');
			$statusCSAINT = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CSAINT');
			$statusCSAP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CSAP');

			$audit = [];

			foreach( $ids['exit'] as $archid ){
				$idparchive = $rep->findOneById( $archid );

                $idparchive->setLastActionBy( $bsUserSession->getUser() );
				$oldStatus = $idparchive->getStatus();
				$newStatus = null;
				if( $oldStatus == $statusDISI ) $newStatus = $statusCSAI;
				if( $oldStatus == $statusDISINT ) $newStatus = $statusCSAINT;
				if( $oldStatus == $statusDISP ) $newStatus = $statusCSAP;
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $newStatus===null?null:$newStatus->getId(),
                    'old_str' => null,
                    'old_int' => $oldStatus===null?null:$oldStatus->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null

                ];
                $idparchive->setStatus( $newStatus );

                $idparchive->setModifiedat( $now );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisiondate( $precisiondate );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONOFFICE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionoffice,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

                $em->persist( $idparchive );
                $em->flush();
			}

            $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );
		}

		return $this->redirect($this->generateUrl('bs_idp_archive_askexit_screen'));
	}

	public function askdeleteScreenAction( Request $request )
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

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_DELETE][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $commonsettings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->getCommonSettings( $bsUserSession );

		$searchTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABSEARCH, $language );
		$filterTanslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABFILTER, $language );
		$resultTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABRESULTS, $language );
		$translations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_DELETESCREEN, $language );
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );
		$overlay = $this->getDoctrine()
			->getRepository( 'bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_OVERLAY_VIEWARCHIVE, $language );
        $tableTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsBackoffice::T_ARRAY_FIELD_NAMES, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );

        $whoname = $bsUserSession->getUser()->getFirstname() . ' ' . $bsUserSession->getUser()->getLastname();
        $form = $this->makePrecisionForm( $precisionTranslations, $whoname, 6, $bsUserSession->getUserId() );

        return $this->render( 'bsIDPArchiveBundle:Default:askDelete.html.twig' , array(
            'form' => $form->createView(),
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'commonsettings' => $commonsettings,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'searchTranslations' => $searchTranslations,
            'filterTranslations' => $filterTanslations,
            'resultTranslations' => $resultTranslations,
            'language' => $language,
            'tableTranslations' => $tableTranslations,
            'precisionTranslations' => $precisionTranslations,
            'overlay' => $overlay,
            'currentMenu' => 6 ));
	}

	public function doAskDeleteAction( Request $request )
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
		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_DELETE][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $translations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_DELETESCREEN, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );

        $data = array();
        $form = $this->makePrecisionForm( $precisionTranslations, null, 6, $bsUserSession->getUserId() );
		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			// $data is a simply array with your form fields
			$data = $form->getData();
			$ids = json_decode( $data['ids'], true );
			$em = $this->getDoctrine()->getManager();
			$rep = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive');

            $precisiondate = new DateTime();
            $precisiondate = DateTime::createFromFormat ( 'd/m/Y', $data['precisiondate'] );
            $precisionwhere = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')->findOneById( $data['precisionwhere'] );
            $precisionfloor = $data['precisionfloor'];
            $precisionoffice = $data['precisionoffice'];
            $precisionwho = $data['precisionwho'];
            $precisioncomment = $data['precisioncomment'];
            $now = new DateTime();

			$statusDISI = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DISI');
			$statusDISINT = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DISINT');
			$statusDISP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('DISP');

			$statusCDAI = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CDAI');
			$statusCDAINT = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CDAINT');
			$statusCDAP = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->findOneByShortname('CDAP');

			$audit = [];
			foreach( $ids['delete'] as $archid ){
				$idparchive = $rep->findOneById( $archid );

                $idparchive->setLastActionBy( $bsUserSession->getUser() );
				$oldStatus = $idparchive->getStatus();
				$newStatus = null;
				if( $oldStatus == $statusDISI ) $newStatus = $statusCDAI;
				if( $oldStatus == $statusDISINT ) $newStatus = $statusCDAINT;
				if( $oldStatus == $statusDISP ) $newStatus = $statusCDAP;

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $newStatus===null?null:$newStatus->getId(),
                    'old_str' => null,
                    'old_int' => $oldStatus===null?null:$oldStatus->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null

                ];
                $idparchive->setStatus( $newStatus );

                $idparchive->setModifiedat( $now );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisiondate( $precisiondate );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_id' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONOFFICE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionoffice,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

                $em->persist( $idparchive );
                $em->flush();
			}

            $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );
		}

		return $this->redirect($this->generateUrl('bs_idp_archive_askdelete_screen'));
	}

    // v0.4.0: add reloc form management
    private function makePrecisionForm( $precisiontranslations, $defaultwho = null, $action = 2, $userId = null )
    {
        $form = $this->createFormBuilder();
        $form->add( 'ids', HiddenType::class, array( 'data' => '' ));

        $lblPrecisionDate = 'Empty';
        $lblPrecisionBtn = 'Empty';
        switch( $action ){
            case 2:
                $lblPrecisionDate = $precisiontranslations[7];
                $lblPrecisionBtn = $precisiontranslations[8];
                break;
            case 3:
                $lblPrecisionDate = $precisiontranslations[9];
                $lblPrecisionBtn = $precisiontranslations[10];
                break;
            case 4:
                $lblPrecisionDate = $precisiontranslations[11];
                $lblPrecisionBtn = $precisiontranslations[12];
                break;
            case 5:
                $lblPrecisionDate = $precisiontranslations[13];
                $lblPrecisionBtn = $precisiontranslations[14];
                break;
            case 6:
                $lblPrecisionDate = $precisiontranslations[15];
                $lblPrecisionBtn = $precisiontranslations[16];
                break;
            case 28:
                $lblPrecisionDate = $precisiontranslations[17];
                $lblPrecisionBtn = $precisiontranslations[18];
                break;
        }

        // Precision date
        $form->add( 'precisiondate', TextType::class, array ( 'label' => $lblPrecisionDate, 'required' => false ));
        // Precision who
        if( $defaultwho != null )
            $form->add( 'precisionwho', TextType::class, array ('label' => $precisiontranslations[1], 'required' => false, 'data' => $defaultwho ));
        else
            $form->add( 'precisionwho', TextType::class, array ('label' => $precisiontranslations[1], 'required' => false ));
        // Precision where
        // Retreive autorized deliver addresses from IDPUserAddresses
        $allowedAddresses = $this->getDoctrine()->getRepository( 'bsCoreUsersBundle:IDPUserAddresses' )
            ->findBy( array( 'user' => $userId ) );

        $arrInAllowedAddresses = array();
        if( $allowedAddresses )
            foreach( $allowedAddresses as $allowedAddress ) {
                array_push($arrInAllowedAddresses, $allowedAddress->getAddress()->getId());
            }

        $form->add( 'precisionwhere', EntityType::class, array (
            'class' => 'bsIDPBackofficeBundle:IDPDeliverAddress',
            'choice_label' => 'longname',
            'query_builder' => function( EntityRepository $repository ) use ( $arrInAllowedAddresses ) {
                return $repository->createQueryBuilder( 'da' )
                    ->where( 'da.id IN (:daarray)' )
                    ->setParameter( 'daarray', $arrInAllowedAddresses )
                    ->orderBy( 'da.longname', 'ASC' );
            },
            'placeholder' => '',
            'empty_data'  => null,
            'label' => $precisiontranslations[2],

            'required' => false
        ) );
        // Precision Floor
        $form->add( 'precisionfloor', TextType::class, array ( 'label' => $precisiontranslations[3], 'required' => false, 'attr' => array('maxlength' => 10) ));
        // Precision Office
        $form->add( 'precisionoffice', TextType::class, array ( 'label' => $precisiontranslations[4], 'required' => false, 'attr' => array('maxlength' => 10) ));
        // Precision Comments
        $form->add( 'precisioncomment', TextType::class, array( 'label' => $precisiontranslations[5], 'required' => false ));

        $form->add( 'precisionBtn', SubmitType::class, array( 'label' => $lblPrecisionBtn ));

        return $form->getForm();
    }

    // v0.4.0: add reloc screen management
    public function askrelocScreenAction( Request $request )
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

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RELOC][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

        $commonsettings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->getCommonSettings( $bsUserSession );

        $searchTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABSEARCH, $language );
        $filterTanslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABFILTER, $language );
        $resultTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_TABRESULTS, $language );
        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_RELOCSCREEN, $language );
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );
        $overlay = $this->getDoctrine()
            ->getRepository( 'bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_OVERLAY_VIEWARCHIVE, $language );
        $tableTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsBackoffice::T_ARRAY_FIELD_NAMES, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );

        $whoname = $bsUserSession->getUser()->getFirstname() . ' ' . $bsUserSession->getUser()->getLastname();

        $form = $this->makePrecisionForm( $precisionTranslations, $whoname, 28, $bsUserSession->getUserId() );

        return $this->render( 'bsIDPArchiveBundle:Default:askReloc.html.twig' , array(
            'form' => $form->createView(),
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
            'precisionTranslations' => $precisionTranslations,
            'language' => $language,
            'overlay' => $overlay,
            'currentMenu' => 28 ));
    }

    // v0.4.0: add reloc action management
    public function doAskRelocAction( Request $request )
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

        if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_RELOC][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 1 ));
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array( 'error_redirect' => 'bs_idp_dashboard_homepage', 'error_wait_time' => IDPConstants::ERROR_WAIT_TIME, 'error_type' => 2 )); }

        $translations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_RELOCSCREEN, $language );
        $precisionTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation( bsTranslationIDsArchive::T_PARTIAL_PRECISION, $language );

        $data = array();
        $form = $this->makePrecisionForm( $precisionTranslations, null, 28, $bsUserSession->getUserId() );
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            // $data is a simply array with your form fields
            $data = $form->getData();
            $ids = json_decode( $data['ids'], true );
            $em = $this->getDoctrine()->getManager();
            $rep = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive');
            $sta = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchivesStatus');

            $precisiondate = new DateTime();
            $precisiondate = DateTime::createFromFormat ( 'd/m/Y', $data['precisiondate'] );
            $precisionwhere = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')->findOneById( $data['precisionwhere'] );
            $precisionfloor = $data['precisionfloor'];
            $precisionoffice = $data['precisionoffice'];
            $precisionwho = $data['precisionwho'];
            $precisioncomment = $data['precisioncomment'];
            $now = new DateTime();

            // TODO: not efficient, update this with a better way
            $statusDISI = $sta->findOneByShortname('DISI');
            $statusDISINT = $sta->findOneByShortname('DISINT');
            $statusDISP = $sta->findOneByShortname('DISP');
            $statusCONI = $sta->findOneByShortname('CONI');
            $statusCONINT = $sta->findOneByShortname('CONINT');
            $statusCONP = $sta->findOneByShortname('CONP');
            $statusCRLIDAINT = $sta->findOneByShortname('CRLIDAINT');
            $statusCRLIDAP = $sta->findOneByShortname('CRLIDAP');
            $statusCRLIDAI = $sta->findOneByShortname('CRLIDAI');
            $statusCRLINTDAI = $sta->findOneByShortname('CRLINTDAI');
            $statusCRLINTDAP = $sta->findOneByShortname('CRLINTDAP');
            $statusCRLINTDAINT = $sta->findOneByShortname('CRLINTDAINT');
            $statusCRLPDAI = $sta->findOneByShortname('CRLPDAI');
            $statusCRLPDAINT = $sta->findOneByShortname('CRLPDAINT');
            $statusCRLICAINT = $sta->findOneByShortname('CRLICAINT');
            $statusCRLICAI = $sta->findOneByShortname('CRLICAI');
            // $statusCRLICAP = $sta->findOneByShortname('CRLICAP');
            $statusCRLINTCAI = $sta->findOneByShortname('CRLINTCAI');
            $statusCRLINTCAINT = $sta->findOneByShortname('CRLINTCAINT');
            // $statusCRLINTCAP = $sta->findOneByShortname('CRLINTCAP');
            $statusCRLPCAI = $sta->findOneByShortname('CRLPCAI');
            $statusCRLPCAINT = $sta->findOneByShortname('CRLPCAINT');

            $audit = [];

            foreach( $ids['internal'] as $archid ){
                $idparchive = $rep->findOneById( $archid );

                $idparchive->setLastActionBy( $bsUserSession->getUser() );
                $oldStatus = $idparchive->getStatus();
                $newStatus = null;
                if( $oldStatus == $statusDISI ) $newStatus = $statusCRLIDAI;
                if( $oldStatus == $statusDISINT ) $newStatus = $statusCRLINTDAI;
                if( $oldStatus == $statusDISP ) $newStatus = $statusCRLPDAI;
                if( $oldStatus == $statusCONI ) $newStatus = $statusCRLICAI;
                if( $oldStatus == $statusCONINT ) $newStatus = $statusCRLINTCAI;
                if( $oldStatus == $statusCONP ) $newStatus = $statusCRLPCAI;

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $newStatus===null?null:$newStatus->getId(),
                    'old_str' => null,
                    'old_int' => $oldStatus===null?null:$oldStatus->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null

                ];
                $idparchive->setStatus( $newStatus );

                // E#292 (SAVE)
                if( in_array( $newStatus->getShortname(), IDPConstants::$SAVE_ENTRYDATE_STATUS ) ){
                    $idparchive->setSaveserviceentrydate( $idparchive->getServiceentrydate() );
                    $idparchive->setServiceentrydate( null );
                }

                $idparchive->setModifiedat( $now );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisiondate( $precisiondate );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONOFFICE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionoffice,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

                // Copie localization into oldlocalization field, and empty localization

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATION,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => $idparchive->getLocalization()===null?null:$idparchive->getLocalization()->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => null,
                    'old_str' => $idparchive->getLocalizationfree(),
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_OLDLOCALIZATION,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $idparchive->getLocalization()===null?null:$idparchive->getLocalization()->getId(),
                    'old_str' => null,
                    'old_int' => $idparchive->getOldlocalization()===null?null:$idparchive->getOldlocalization()->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $idparchive->getLocalizationfree(),
                    'new_int' => null,
                    'old_str' => $idparchive->getOldlocalizationfree(),
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setOldlocalization( $idparchive->getLocalization() );
                $idparchive->setOldlocalizationfree( $idparchive->getLocalizationfree() );
                $idparchive->setLocalization( null );
                $idparchive->setLocalizationfree( null );

                $em->persist( $idparchive );
                $em->flush();
            }
            foreach( $ids['intermediate'] as $archid ){
                $idparchive = $rep->findOneById( $archid );

                $idparchive->setLastActionBy( $bsUserSession->getUser() );
                $oldStatus = $idparchive->getStatus();
                $newStatus = null;
                if( $oldStatus == $statusDISI ) $newStatus = $statusCRLIDAINT;
                if( $oldStatus == $statusDISINT ) $newStatus = $statusCRLINTDAINT;
                if( $oldStatus == $statusDISP ) $newStatus = $statusCRLPDAINT;
                if( $oldStatus == $statusCONI ) $newStatus = $statusCRLICAINT;
                if( $oldStatus == $statusCONINT ) $newStatus = $statusCRLINTCAINT;
                if( $oldStatus == $statusCONP ) $newStatus = $statusCRLPCAINT;

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $newStatus===null?null:$newStatus->getId(),
                    'old_str' => null,
                    'old_int' => $oldStatus===null?null:$oldStatus->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setStatus( $newStatus );

                // E#292 (SAVE)
                if( in_array( $newStatus->getShortname(), IDPConstants::$SAVE_ENTRYDATE_STATUS ) ){
                    $idparchive->setSaveserviceentrydate( $idparchive->getServiceentrydate() );
                    $idparchive->setServiceentrydate( null );
                }

                $idparchive->setModifiedat( $now );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisiondate( $precisiondate );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONOFFICE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionoffice,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATION,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => $idparchive->getLocalization()===null?null:$idparchive->getLocalization()->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => null,
                    'old_str' => $idparchive->getLocalizationfree(),
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_OLDLOCALIZATION,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $idparchive->getLocalization()===null?null:$idparchive->getLocalization()->getId(),
                    'old_str' => null,
                    'old_int' => $idparchive->getOldlocalization()===null?null:$idparchive->getOldlocalization()->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $idparchive->getLocalizationfree(),
                    'new_int' => null,
                    'old_str' => $idparchive->getOldlocalizationfree(),
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setOldlocalization( $idparchive->getLocalization() );
                $idparchive->setOldlocalizationfree( $idparchive->getLocalizationfree() );
                $idparchive->setLocalization( null );
                $idparchive->setLocalizationfree( null );

                $em->persist( $idparchive );
                $em->flush();
            }
            foreach( $ids['provider'] as $archid ){
                $idparchive = $rep->findOneById( $archid );

                $idparchive->setLastActionBy( $bsUserSession->getUser() );
                $oldStatus = $idparchive->getStatus();
                $newStatus = null;
                if( $oldStatus == $statusDISINT ) $newStatus = $statusCRLINTDAP;
                if( $oldStatus == $statusDISI ) $newStatus = $statusCRLIDAP;

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_STATUS,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $newStatus===null?null:$newStatus->getId(),
                    'old_str' => null,
                    'old_int' => $oldStatus===null?null:$oldStatus->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null

                ];
                $idparchive->setStatus( $newStatus );

                // E#292 (SAVE)
                if( in_array( $newStatus->getShortname(), IDPConstants::$SAVE_ENTRYDATE_STATUS ) ){
                    $idparchive->setSaveserviceentrydate( $idparchive->getServiceentrydate() );
                    $idparchive->setServiceentrydate( null );
                }

                $idparchive->setLastactionby( $bsUserSession->getUser() );
                $idparchive->setModifiedat( $now );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONDATE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisiondate->format( 'd/m/Y' ),
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisiondate( $precisiondate );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHERE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $precisionwhere===null?null:$precisionwhere->getId(),
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwhere( $precisionwhere );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONFLOOR,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionfloor,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionfloor( $precisionfloor );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONOFFICE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionoffice,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionoffice( $precisionoffice );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONWHO,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisionwho,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisionwho( $precisionwho );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_PRECISIONCOMMENT,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $precisioncomment,
                    'new_int' => null,
                    'old_str' => null,
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $idparchive->setPrecisioncomment( $precisioncomment );

                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATION,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $idparchive->getOldlocalization()===null?null:$idparchive->getOldlocalization()->getId(),
                    'old_str' => null,
                    'old_int' => $idparchive->getLocalization()===null?null:$idparchive->getLocalization()->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $idparchive->getOldlocalizationfree(),
                    'new_int' => null,
                    'old_str' => $idparchive->getLocalizationfree(),
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_OLDLOCALIZATION,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => null,
                    'new_int' => $idparchive->getLocalization()===null?null:$idparchive->getLocalization()->getId(),
                    'old_str' => null,
                    'old_int' => $idparchive->getOldlocalization()===null?null:$idparchive->getOldlocalization()->getId(),
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $audit[] = [
                    'action' => IDPConstants::AUDIT_ACTION_MODIFY,
                    'user_id' => $bsUserSession->getUserId(),
                    'field' => IDPConstants::FIELD_ARCHIVE_LOCALIZATIONFREE,
                    'entity' => IDPConstants::ENTITY_ARCHIVE,
                    'entity_id' => $idparchive->getId(),
                    'new_str' => $idparchive->getLocalizationfree(),
                    'new_int' => null,
                    'old_str' => $idparchive->getOldlocalizationfree(),
                    'old_int' => null,
                    'complete_ua_id' => null,
                    'object_type' => null
                ];
                $tempReloc = $idparchive->getOldlocalization();
                $tempRelocfree = $idparchive->getOldlocalizationfree();
                $idparchive->setOldlocalization( $idparchive->getLocalization() );
                $idparchive->setOldlocalizationfree( $idparchive->getLocalizationfree() );
                $idparchive->setLocalization( $tempReloc );
                $idparchive->setLocalizationfree( $tempRelocfree );

                $em->persist( $idparchive );
                $em->flush();
            }

            $nbAudit = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPAudit')->addMultipleAuditEntry( $audit );
        }

        return $this->redirect($this->generateUrl('bs_idp_archive_askreloc_screen'));
    }


}

