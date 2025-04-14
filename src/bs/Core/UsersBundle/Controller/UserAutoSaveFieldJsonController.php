<?php

namespace bs\Core\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\Core\AdminBundle\Security\IDPArchimageRights;

use bs\Core\UsersBundle\Entity\IDPUserAutoSaveFields;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class UserAutoSaveFieldJsonController extends Controller
{
    const ASF_SERVICE               = 1;
    const ASF_LEGALENTITY           = 2;
    const ASF_BUDGETCODE            = 3;
    const ASF_DOCUMENTNATURE        = 4;
    const ASF_DOCUMENTTYPE          = 5;
    const ASF_DESCRIPTION1          = 6;
    const ASF_DESCRIPTION2          = 7;
    const ASF_CLOSUREYEAR           = 8;
    const ASF_DESTRUCTIONYEAR       = 9;
    const ASF_FILENUMBER            = 10;
    const ASF_BOXNUMBER             = 11;
    const ASF_CONTAINERNUMBER       = 12;
    const ASF_PROVIDER              = 13;
    const ASF_LIMITSDATE            = 14;
    const ASF_LIMITSNUM             = 15;
    const ASF_LIMITSALPHA           = 16;
    const ASF_LIMITSALPHANUM        = 17;
    const ASF_NAME                  = 18;

    private function jsonResponse($return, $code = 200)
    {
        $response = new Response(json_encode($return), $code);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    // bs_core_user_asf_update
    public function updateAsfAction( Request $request )
    {
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : User not logged') , 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//        if($request->isXmlHttpRequest()) {

            // GET
            $parameters = $request->query;
            // POST
            // $parameters = $request->request;

            $user_id = $parameters->get('user_id');
            $field_id = $parameters->get('field_id');
            $new_value = $parameters->get('new_value');
            $new_value = ( strcmp( $new_value, "true" ) == 0 );

            // Get User ASF
            $asf = $this->getDoctrine()
                ->getRepository('bsCoreUsersBundle:IDPUserAutoSaveFields')
                ->findOneBy( array( 'user_id' => $user_id ));
            if( ! $asf )
                return $this->jsonResponse( array('message' => 'System Error : User has no ASF') , 419);

            switch( $field_id ){
                case self::ASF_SERVICE:
                    $asf->setAsfService( $new_value );
                    break;
                case self::ASF_LEGALENTITY:
                    $asf->setAsfLegalentity( $new_value );
                    break;
                case self::ASF_BUDGETCODE:
                    $asf->setAsfBudgetcode( $new_value );
                    break;
                case self::ASF_DOCUMENTNATURE:
                    $asf->setAsfDocumentnature( $new_value );
                    break;
                case self::ASF_DOCUMENTTYPE:
                    $asf->setAsfDocumenttype( $new_value );
                    break;
                case self::ASF_DESCRIPTION1:
                    $asf->setAsfDescription1( $new_value );
                    break;
                case self::ASF_DESCRIPTION2:
                    $asf->setAsfDescription2( $new_value );
                    break;
                case self::ASF_CLOSUREYEAR:
                    $asf->setAsfClosureyear( $new_value );
                    break;
                case self::ASF_DESTRUCTIONYEAR:
                    $asf->setAsfDestructionyear( $new_value );
                    break;
                case self::ASF_FILENUMBER:
                    $asf->setAsfFilenumber( $new_value );
                    break;
                case self::ASF_BOXNUMBER:
                    $asf->setAsfBoxnumber( $new_value );
                    break;
                case self::ASF_CONTAINERNUMBER:
                    $asf->setAsfContainernumber( $new_value );
                    break;
                case self::ASF_PROVIDER:
                    $asf->setAsfProvider( $new_value );
                    break;
                case self::ASF_LIMITSDATE:
                    $asf->setAsfLimitsdate( $new_value );
                    break;
                case self::ASF_LIMITSNUM:
                    $asf->setAsfLimitsnum( $new_value );
                    break;
                case self::ASF_LIMITSALPHA:
                    $asf->setAsfLimitsalpha( $new_value );
                    break;
                case self::ASF_LIMITSALPHANUM:
                    $asf->setAsfLimitsalphanum( $new_value );
                    break;
                case self::ASF_NAME:
                    $asf->setAsfName( $new_value );
                    break;
                default:
                    return $this->jsonResponse( array('message' => 'System Error : Field unknown') , 419);
                    break;
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist( $asf );
            $em->flush();

            return $this->jsonResponse( array( 'message' => 'Success' ), 200 );

//        }else{
//            $content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//            return new Response($content, 419);
//        }
    }
}