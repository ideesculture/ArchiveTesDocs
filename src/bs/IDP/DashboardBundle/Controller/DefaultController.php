<?php
// (c) Archimage - IDP Consulting 2016
// Author: Cyril PATRIGEON - BeSecure Labs
//

namespace bs\IDP\DashboardBundle\Controller;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\DashboardBundle\Entity\bsMessages;
use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;

use bs\IDP\DashboardBundle\Translation\bsTranslationIDsDashboard;
use bs\Core\TranslationBundle\Translation\bsTranslationIDsCommon;

use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class DefaultController extends Controller
{
    public function indexAction( Request $request )
    {
    	$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
    	if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
    		return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

        $userScale = $bsUserSession->getUserScale();
    	$language = $bsUserSession->getUserExtension()->getLanguage();

        $userRoles = $bsUserSession->getUserRoles();
        $isArchivist = false;
        foreach( $userRoles as $role ){
            if(( $role->getScale() < 100 ))
                $isArchivist = true;
        }

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );

    	$translations = $this->getDoctrine()
    		->getRepository('bsCoreTranslationBundle:bsTranslation')
    		->getArrayTranslation( bsTranslationIDsDashboard::T_PAGE_INDEXSCREEN, $language );
    	$headTranslations = $this->getDoctrine()
    		->getRepository('bsCoreTranslationBundle:bsTranslation')
    		->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_MINI, $language );

    	$globalStatuses = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' )
            ->findOneBy( array( 'id' => 1 ) );

    	$nbs = [ 0, 1, 2, 3, 4, 5 ];
    	$userId = null;
    	$userServices = null;
    	if( $bsUserSession->hasRole( 'USER' ) ) {
            // User is a simple user
            $userId = $bsUserSession->getUserId();
        } else {
    	    // User is an admin or an archivist
            $userServices = $bsUserSession->getUserServices();
        }
        for( $countIdx = 0; $countIdx < sizeof( IDPConstants::$COUNT_STATUS ); $countIdx++ )
            $nbs[$countIdx] = $this->getDoctrine()
                ->getRepository('bsIDPArchiveBundle:IDPArchive')
                ->getNbArchiveWithStatuses( IDPConstants::$COUNT_STATUS[$countIdx], $userId, $isArchivist, $userServices);

    	// Nombre de commandes
    	$nbCMD = '#';

    	// get all not already viewed (bsViewed=false) messages from system (bsFrom=null) to show on dashboard
    	$msgs = $this->getDoctrine()
    		->getRepository('bsIDPDashboardBundle:bsMessages')
    		->findBy(array('bsViewed' => false, 'bsFrom' => null, 'bsTo' => $bsUserSession->getUserId() ), array('sentDate' => 'ASC'));
    	if( ($msgs != null) && (count($msgs) <= 0) )
    		$msgs = null;
    	// TODO each viewed information have to viewed=true

        return $this->render('bsIDPDashboardBundle:Default:index.html.twig', array(
            'nbs' => $nbs,
            'nbCMD' => $nbCMD,
            'systeminfolist' => $msgs,
            'userFilesResume' => $userFilesResume,
            'curruser' => $bsUserSession->getUser(),
            'userscale' => $userScale,
            'headTranslations' => $headTranslations,
            'translations' => $translations,
            'globalStatuses' => $globalStatuses
        ));
    }
}
