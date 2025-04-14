<?php

namespace bs\IDP\BackofficeBundle\Controller;

use bs\IDP\BackofficeBundle\Entity\IDPGlobalSettings;
use bs\IDP\BackofficeBundle\Entity\IDPUserColumnsSettings;
use bs\IDP\BackofficeBundle\Entity\IDPUserPagesSettings;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;


class UserSettingsJsonController extends Controller
{
    private function jsonResponse( $return, $code = 200 ){
        $response = new Response(json_encode($return), $code );
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    // bs_idp_backoffice_ajax_usersettings_get
    public function getUserSettingsAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : User not logged !'), 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        $user_id = $bsUserSession->getUserId();

//        if($request->isXmlHttpRequest()) {

            // GET
            $parameters = $request->query;
            // POST
            // $parameters = $request->request

            if( $parameters->has( 'page' ) )
                $page_id = $parameters->get('page');
            else
                return $this->jsonResponse( array('message' => 'System Error : No page specified !'), 400);

            $userPageSettings = $this->getDoctrine()
                ->getRepository( 'bsIDPBackofficeBundle:IDPUserPagesSettings' )
                ->getOne( $page_id, $user_id );
            if( !$userPageSettings || count( $userPageSettings ) != 1 )
                return $this->jsonResponse( array( 'message' => 'System Error: No settings for current user !' ), 417 );
            $userPageSettings = $userPageSettings[0];

            $columns = null;
            $userColumnsSettings = null;
            // For BDD there are no column configuration
            if(!( $page_id >= IDPUserPagesSettings::PAGE_BDD_ENTRY_SERVICES )&( $page_id <= IDPUserPagesSettings::PAGE_BDD_PROVIDERS ) ) {
                $columns = $this->getDoctrine()
                    ->getRepository('bsIDPBackofficeBundle:IDPColumns')
                    ->getAllIndexedOnID();

                $userColumnsSettings = $this->getDoctrine()
                    ->getRepository('bsIDPBackofficeBundle:IDPUserColumnsSettings')
                    ->getAllForThisPage($userPageSettings['id']);
                if (!$userColumnsSettings || count($userColumnsSettings) <= 0)
                    return $this->jsonResponse(array('message' => 'System Error: Settings incomplete for current user !'), 417);
            }

            $return = array( 'userPageSettings' => $userPageSettings, 'columns' => $columns, 'userColumnsSettings' => $userColumnsSettings );

            return $this->jsonResponse( array('message' => 'OK', 'data' => $return ) );
//        }else
//            return $this->jsonResponse( array('message' => 'System Error : Only Ajax Request Allowed !'), 419);
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
                // Get all default columns settings for the defaultPageSettings
                $defaultColumnsSettings = $ucsRep->findBy( array( 'user_page_settings' => $defaultPageSettings ) );
                if( $defaultColumnsSettings && count( $defaultColumnsSettings ) >= 1 ) {
                    foreach ( $defaultColumnsSettings as $defaultColumnSettings ){
                        $countColumns++;

                        // Create same settings for new page
                        $newColumnSettings = clone ( $defaultColumnSettings );
                        $newColumnSettings->setUserpagesettings( $newPageSettings );
                        $newColumnSettings->setUserid( $userID );

                        $em->persist( $newColumnSettings );
                        if( $fullLog ){
                            $em->flush();
                            $columnSettingID = $newColumnSettings->getId();
                        }

                        if( $fullLog ){
                            if( $columnSettingID <= 0 )
                                array_push( $log, " - Error while copying Column Settings ".$defaultPageSettings->getId() );
                            else
                                array_push( $log, " - Column Settings ".$defaultColumnSettings->getId()." successfully copyied in $columnSettingID" );
                        }
                    }
                    if( !$fullLog )
                        $em->flush();
                }
            } else
                array_push( $log, "Error while copying Page Settings ".$defaultPageSettings->getId() );
        }
        array_push( $log, "$countColumns Columns created in $countPages Pages" );
        return true;
    }

    public function createForUserAction( Request $request ){

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 ));

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
        $parameters = $request->query;
        // POST
        // $parameters = $request->request

        if( $parameters->has( 'userID' ) )
            $user_id = $parameters->get('userID');
        else
            return $this->render('bsIDPBackofficeBundle:UserSettings:createForUser.html.twig', array('result' => false, 'log' => array('Error, no parameter userID found'), 'userID' => null));

        $log = [];
        $result = $this->createUserSettings( $user_id, false, $log );

        return $this->render('bsIDPBackofficeBundle:UserSettings:createForUser.html.twig', array('result' => $result, 'log' => $log, 'userID' => $user_id));
    }

    protected function deleteUserSettings( $userID, $fullLog, &$log ){

        $upsRep = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPUserPagesSettings' );
        $ucsRep = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPUserColumnsSettings' );

        // Verify if settings exists
        $settings = $upsRep->findBy( array( 'user_id' => $userID ) );
        if( !$settings && count( $settings ) <= 1 ){
            array_push( $log, "User Page Settings doesn't exist for user: $userID" );
            return false;
        }
        $columns = $ucsRep->findBy( array( 'user_id' => $userID ) );
        if( !$columns && count( $columns ) <= 1 ){
            array_push( $log, "User Columns Settings doesn't exist for user: $userID" );
            return false;
        }

        $em = $this->getDoctrine()->getManager();
        $countPages = 0;
        $countColumns = 0;

        foreach ( $columns as $columnSettings ){
            $countColumns++;
            $columnSettingID = $columnSettings->getId();

            // delete settings
            $em->remove( $columnSettings );

            if( $fullLog ){
                array_push( $log, " - Column Settings $columnSettingID successfully deleted" );
            }
        }
        $em->flush();

        foreach ( $settings as $pageSettings ){
            $countPages++;
            $pageSettingsID = $pageSettings->getId();

            $em->remove( $pageSettings );
            if( $fullLog )
                array_push( $log, " * Page Settings $pageSettingsID successfully deleted" );
        }
        $em->flush();

        array_push( $log, "$countColumns Columns and $countPages Pages deleted" );
        return true;
    }

    public function deleteForUserAction( Request $request ){

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 ));

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
        $parameters = $request->query;
        // POST
        // $parameters = $request->request

        if( $parameters->has( 'userID' ) )
            $user_id = $parameters->get('userID');
        else
            return $this->render('bsIDPBackofficeBundle:UserSettings:deleteForUser.html.twig', array('result' => false, 'log' => array('Error, no parameter userID found'), 'userID' => null));

        $log = [];
        $result = $this->deleteUserSettings( $user_id, false, $log );

        return $this->render('bsIDPBackofficeBundle:UserSettings:deleteForUser.html.twig', array('result' => $result, 'log' => $log, 'userID' => $user_id));
    }

    // bs_idp_backoffice_usersettings_modify_column
    public function modifyColumnAction( Request $request ){

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : User not logged !'), 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//        if($request->isXmlHttpRequest()) {

            // GET
            $parameters = $request->query;
            // POST
            // $parameters = $request->request

            $user_id = $bsUserSession->getUserId();
            
            if( $parameters->has( 'page' ) )
                $page_id = $parameters->get('page');
            else
                return $this->jsonResponse( array('message' => 'System Error : No page specified !'), 400);
            $page = $this->getDoctrine()
                ->getRepository( 'bsIDPBackofficeBundle:IDPUserPagesSettings' )
                ->findBy( array( 'user_id' => $user_id, 'page_id' => $page_id ) );
            if( !$page || count( $page )<=0 )
                return $this->jsonResponse( array( 'message' => 'System Error: No Page found !'), 417 );
            $page = $page[0];
            
            if( $parameters->has( 'column' ) )
                $column_name = $parameters->get('column');
            else
                return $this->jsonResponse( array('message' => 'System Error : No column specified !'), 400);
            $column = $this->getDoctrine()
                ->getRepository( 'bsIDPBackofficeBundle:IDPColumns' )
                ->findBy( array( 'field_name' => $column_name ) );
            $column_id = null;
            if( !$column || count( $column ) <= 0 )
                return $this->jsonResponse( array( 'message' => 'System Error: Wrong column name specified !'), 417 );
            $column = $column[0];
            
            if( $parameters->has( 'field' ) )
                $field = $parameters->get('field');
            else
                return $this->jsonResponse( array('message' => 'System Error : No field specified !'), 400);
            
            if( $parameters->has( 'value' ) )
                $new_value = $parameters->get('value');
            else
                return $this->jsonResponse( array('message' => 'System Error : No value specified !'), 400);
            
            if( $parameters->has( 'onlyone' ) )
                $only_one = $parameters->get('onlyone');
            else
                $only_one = false;

            if( $only_one && ( $field == IDPUserColumnsSettings::USER_SETTINGS_MODIF_COLUMN_SORTED )){
                $this->getDoctrine()
                    ->getRepository( 'bsIDPBackofficeBundle:IDPUserColumnsSettings' )
                    ->resetAllSortedColumn( $page->getId() );
            }

            $userColumnSettings = $this->getDoctrine()
                ->getRepository( 'bsIDPBackofficeBundle:IDPUserColumnsSettings' )
                ->findBy( array( 'column' => $column, 'user_page_settings' => $page ) );
            if( !$userColumnSettings || count( $userColumnSettings )<=0 ){
                return $this->jsonResponse( array('message' => 'System Error : No $userColumnSettings found !'), 417);
            }
            else
                $userColumnSettings = $userColumnSettings[0];

            $chgMessage = '['.$userColumnSettings->getId().'].';

            switch( $field ){
                case IDPUserColumnsSettings::USER_SETTINGS_MODIF_COLUMN_VISIBLE:
                    $userColumnSettings->setVisible( strtolower($new_value)==='true'?true:false );
                    $chgMessage .= 'visible:';
                    break;
                case IDPUserColumnsSettings::USER_SETTINGS_MODIF_COLUMN_SORTED:
                    $userColumnSettings->setSorted( strtolower($new_value)==='true'?true:false );
                    $chgMessage .= 'sorted:';
                    break;
                case IDPUserColumnsSettings::USER_SETTINGS_MODIF_COLUMN_SORT_TYPE_ASC:
                    $userColumnSettings->setSorttypeasc( strtolower($new_value)==='true'?true:false );
                    $chgMessage .= 'sort_type_asc:';
                    break;
                default:
                    return $this->jsonResponse( array( 'message', 'System Error: wrong field !'), 417 );
                    break;
            }
            $chgMessage .= strtolower($new_value)==='true'?true:false;


            $em = $this->getDoctrine()->getManager();
            $em->persist( $userColumnSettings );
            $em->flush();

//        return $this->render('bsIDPArchiveBundle:Default:debug.html.twig', array( 'message' => 'ok' ));

        return $this->jsonResponse( array('message' => 'OK .'.json_encode($chgMessage)), 200);

//        }else
//            return $this->jsonResponse( array('message' => 'System Error : Only Ajax Request Allowed !'), 419);

    }

    public function modifyPageAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : User not logged !'), 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//        if($request->isXmlHttpRequest()) {

            // GET
            $parameters = $request->query;
            // POST
            // $parameters = $request->request

            $user_id = $bsUserSession->getUserId();
            
            if( $parameters->has( 'page' ) )
                $page_id = $parameters->get('page');
            else
                return $this->jsonResponse( array('message' => 'System Error : No page specified !'), 400);
            if( $parameters->has( 'field' ) )
                $field = $parameters->get('field');
            else
                return $this->jsonResponse( array('message' => 'System Error : No field specified !'), 400);
            if( $parameters->has( 'value' ) )
                $new_value = $parameters->get('value');
            else
                return $this->jsonResponse( array('message' => 'System Error : No value specified !'), 400);

            $this->getDoctrine()
                ->getRepository( 'bsIDPBackofficeBundle:IDPUserPagesSettings' )
                ->modifyPage( $user_id, $page_id, $field, $new_value );

            return $this->jsonResponse( array('message' => 'OK'), 200);

//        }else
//            return $this->jsonResponse( array('message' => 'System Error : Only Ajax Request Allowed !'), 419);

    }

    // bs_idp_backoffice_usersettings_modify_column_order
    public function modifyColumnOrderAction( Request $request ){
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ?$this->container->get('logger'):null;
        if( $logger ) $logger->info( '-> Begin modifyColumnOrderAction' );

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : User not logged !'), 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//        if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request

        $user_id = $bsUserSession->getUserId();

        if( $parameters->has( 'page' ) )
            $page_id = $parameters->get('page');
        else
            return $this->jsonResponse( array('message' => 'System Error : No page specified !'), 400);
        $page = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPUserPagesSettings' )
            ->findBy( array( 'user_id' => $user_id, 'page_id' => $page_id ) );
        if( !$page || count( $page )<=0 )
            return $this->jsonResponse( array( 'message' => 'System Error: No Page found !'), 417 );
        $page = $page[0];
        if( $logger ) $logger->info( ' > Page: '. $page->getId() );

        if( $parameters->has( 'neworder' ) )
            $columns_new_order = json_decode( $parameters->get('neworder') );
        else
            return $this->jsonResponse( array('message' => 'System Error : No columns order specified !'), 400);
        if( $logger ) $logger->info( ' > neworder: '. json_encode($columns_new_order) );

        // Get back Columns
        $columnsOrderedByFieldname = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPColumns' )
            ->getAllObjectsIndexedOnFieldname( );
        if( !$columnsOrderedByFieldname || count( $columnsOrderedByFieldname )<=0 )
            return $this->jsonResponse( array('message' => 'System Error : No $userColumnSettings found !'), 417);
        if( $logger ) $logger->info( ' > $columnsOrderedByFieldname: '. json_encode($columnsOrderedByFieldname) );

        // Get back User Columns Settings for this page
        $userColumnsSettings = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPUserColumnsSettings' )
            ->getAllForThisPageIndexedOnColumnID( $page->getId() );
        if( !$userColumnsSettings || count( $userColumnsSettings )<=0 )
            return $this->jsonResponse( array('message' => 'System Error : No $userColumnSettings found !'), 417);
        if( $logger ) $logger->info( ' > $userColumnsSettings: '. json_encode($userColumnsSettings) );

        $em = $this->getDoctrine()->getManager();
        $i = 0;
        foreach( $columns_new_order as $column_new_order_name ){
            if( strcmp( $column_new_order_name, 'state' ) != 0 ) {  // column 'state' is the checkbox column
                $column = $columnsOrderedByFieldname[$column_new_order_name];
                $userColumnSetting = $userColumnsSettings[$column->getId()];
                $userColumnSetting->setColumnOrder($i);
                $em->persist($userColumnSetting);
                $i++;
            }
        }
        $em->flush();

//        return $this->render('bsIDPArchiveBundle:Default:debug.html.twig', array( 'message' => 'ok' ));

        return $this->jsonResponse( array('message' => 'OK .'), 200);

//        }else
//            return $this->jsonResponse( array('message' => 'System Error : Only Ajax Request Allowed !'), 419);

    }

}
