<?php

namespace bs\IDP\ArchiveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;

use \DateTime;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;
use bs\IDP\ArchiveBundle\Form\Type\IDPArchiveType;
use bs\IDP\ArchiveBundle\Form\Type\IDPCustTransferType;
use bs\IDP\DashboardBundle\Entity\bsMessages;

use bs\IDP\BackofficeBundle\Entity\IDPServices;
use bs\IDP\BackofficeBundle\Entity\IDPLocalizations;

use Symfony\Component\Filesystem\Filesystem;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class ManageDBLocalizationsJsonController extends Controller
{

    private function jsonResponse( $return, $code = 200 ){
        $response = new Response(json_encode($return), $code );
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    // =========================================================================
    // -- MANAGE DB -- LOCALIZATIONS --

    public function localizationslistAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        //		if($request->isXmlHttpRequest()) {

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
            $sort = 'longname';
        if( $parameters->has( 'order' ) )
            $order = $parameters->get('order');
        else
            $order = 'asc';
        if( $parameters->has( 'search' ) )
            $search = $parameters->get('search');
        else
            $search = null;

        // Ask databse for total localizations
        $totalLocalizations = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPLocalizations' )->countLocalizations($search);
        // Ask database for localization range
        $localizations = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPLocalizations' )->loadLocalizationsDatas( $search,$sort, $order, $limit, $offset );

        $response_data = array();

        foreach( $localizations as $localization ){

            $cansuppress = true;
            $archive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive')
                ->findOneBy( array( 'localization' => $localization->getId() ) );
            if( $archive ) $cansuppress = false;
            $archive = $this->getDoctrine()->getRepository( 'bsIDPArchiveBundle:IDPArchive')
                ->findOneBy( array( 'oldlocalization' => $localization->getId() ) );
            if( $archive ) $cansuppress = false;

            $line = array(
                'id' => $localization->getId(),
                'longname' => $localization->getLongname(),
                'cansuppress' => $cansuppress
            );
            array_push( $response_data, $line );
        }

        $return = array( 'total' => intval( $totalLocalizations ), 'rows' => $response_data );

        return $this->jsonResponse( $return );

        //		}else{
        //			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
        //			return new Response($content, 419);
        //		}
    }

    public function localizationsdeleteAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        //		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request

        $id = $parameters->get('id');

        $localization = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPLocalizations')
            ->find($id);

        if($localization == null){
            return $this->jsonResponse( array( 'message' => 'System Error: Localization Id does not exist !'), 417 );
        }
        else{
            $localizationDir = __DIR__.'/../../../../../web/img/providers/';
            $em = $this->getDoctrine()->getManager();

            if( $localization->getLogo() != null ){
                // first remove old file
                $oldfile = $localizationDir . $localization->getLogo();
                $fs = new Filesystem();

                if( $fs->exists( $oldfile ) ){
                    $fs->remove( $oldfile );
                }
            }

            $em->remove($localization);
            $em->flush();

            return $this->jsonResponse( array('success' => true) );
        }

        //		}else{
        //			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
        //			return new Response($content, 419);
        //		}
    }

    public function localizationsaddAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        //		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request

        $name = $parameters->get('name');

        $localization = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPLocalizations' )
            ->findBy( array( 'longname' => $name ) );

        if( $localization != null ){
            return $this->jsonResponse( array( 'message' => "La localisation $name existe déjà !"), 417 );
        }

        $localization = new IDPLocalizations();
        $localization->setLongname( $name );

        $em = $this->getDoctrine()->getManager();
        $em->persist($localization);
        $em->flush();

        return $this->jsonResponse( array( 'success', true ) );

        //		}else{
        //			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
        //			return new Response($content, 419);
        //		}
    }

    public function localizationsModifyAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        //		if($request->isXmlHttpRequest()) {

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request

        $id = $parameters->get('id');
        $name = $parameters->get('name');

        $localization = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPLocalizations' )
            ->find( $id );

        if( $localization == null ){
            return $this->jsonResponse( array( 'message' => "System Error: Localization Id does not exist !"), 417 );
        }

        $localization->setLongname( $name );

        $em = $this->getDoctrine()->getManager();
        $em->persist($localization);
        $em->flush();

        return $this->jsonResponse( array( 'success', true ) );

        //		}else{
        //			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
        //			return new Response($content, 419);
        //		}
    }


    public function localizationschangelogoAction( Request $request ){

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() ){
            return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403 );
        }

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        //		if($request->isXmlHttpRequest()) {

        // GET
        // $parameters = $request->query;
        // POST
        $parameters = $request->request;

        if( $parameters->has( 'localizationId' ) )
            $localizationId = $parameters->get('localizationId');
        else
            return $this->jsonResponse( array( 'message' => 'System Error : localizationId to modify needed' ), 400 );

        $file = $request->files->get('LogoInputFile');
        // If a file was uploaded
        if( is_null( $file ) ){
            return $this->jsonResponse( array( 'message' => 'System Error : no file uploaded' ), 400 );
        }

        $localization = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPLocalizations')
            ->find( $localizationId );
        if( !$localization ){
            return $this->jsonResponse( array( 'message' => 'System Error : no localization with id '.$localizationId), 417 );
        }

        $em = $this->getDoctrine()->getManager();
        $localizationDir = __DIR__.'/../../../../../web/img/providers/';

        if( $localization->getLogo() != null ){
            // first remove old file
            $oldfile = $localizationDir . $localization->getLogo();
            $fs = new Filesystem();

            if( $fs->exists( $oldfile ) ){
                $fs->remove( $oldfile );
            }
            $localization->setLogo( null );
            $em->persist( $localization );
            $em->flush();
        }

        // move new file in the provider directory
        $now = new DateTime();
        $newFileName = sprintf( "%s-%s", $now->getTimestamp(), $file->getClientOriginalName() );

        $file->move( $localizationDir, $newFileName );

        $localization->setLogo( $newFileName );
        $em->persist( $localization );
        $em->flush();

        return $this->jsonResponse( array( 'message' => 'Logo changed successfully', 'filename' => $newFileName ));

        //		} else
        //			return $this->jsonResponse( array( 'message' => 'System Error : Only Ajax Request Allowed' ), 419 );

    }

}