<?php

namespace bs\IDP\BackofficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

class JsonController extends Controller
{
    private function jsonResponse( $return, $code = 200 ){
        $response = new Response(json_encode($return), $code );
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function getServicesListAction(Request $request)
	{
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        if($request->isXmlHttpRequest()) {

            $serviceList = [];

			// Retreive linked table objects
			$services = $this->getDoctrine()
				->getRepository('bsIDPBackofficeBundle:IDPServices')
				->findall();

			foreach( $services as $service ){
				array_push( $serviceList, array( 'id' => $service->getId(), 'longname' =>$service->getLongname() ));
			}

			$response = new Response(json_encode($serviceList));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}else{
			$content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
			return new Response($content, 419);
		}
	}

    public function getLocalizationsListAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        if($request->isXmlHttpRequest()) {

            $localizationsList = [];

            // Retreive linked table objects
            $localizations = $this->getDoctrine()
                ->getRepository('bsIDPBackofficeBundle:IDPLocalizations')
                ->findBy(array(), array('longname' => 'ASC'));

            foreach( $localizations as $localization ){
                array_push( $localizationsList, array( 'id' => $localization->getId(), 'longname' =>$localization->getLongname() ));
            }

            $response = new Response(json_encode($localizationsList));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }else{
            $content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
            return new Response($content, 419);
        }
    }

    public function getAddressesListAction(Request $request)
    {
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

        if($request->isXmlHttpRequest()) {

            $return = [];

            // Retreive linked table objects
            $addresses = $this->getDoctrine()
                ->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')
                ->findall();

            $addressList = [];
            foreach( $addresses as $address ){
                array_push( $return, array( 'id' => $address->getId(), 'longname' =>$address->getLongname() ));
            }

            $response = new Response(json_encode($return));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }else{
            $content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
            return new Response($content, 419);
        }
    }

    public function getSettingsAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//        if( $request->isXmlHttpRequest()) {
            $return = [];

            // GET
            $parameters = $request->query;
            // POST
            // $parameters = $request->request;

            if( $parameters->has( 'serviceid' ) )
                $serviceId = $parameters->get('serviceid');
            else
                $serviceId = 0;

            $settings = $this->getDoctrine()
                ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
                ->arrayFindOneByService( $serviceId );

            if( !$settings )
                return $this->jsonResponse( array('message' => 'System Error : settings load error') , 419);

            $return = $settings[0];

            return $this->jsonResponse( $return );
//        }else{
//            $content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//            return new Response($content, 419);
//        }
    }

    public function getBasketsSettingsAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged() )
            return $this->jsonResponse( array('message' => 'System Error : User not logged'), 403);

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->jsonResponse( array( 'message' => 'Acces interdit, rapprochement des stocks en cours !' ), 403 ); }

//        if( $request->isXmlHttpRequest()) {
        $return = [];

        // GET
        $parameters = $request->query;
        // POST
        // $parameters = $request->request;

        $allowedServices =  '';
        $userServices = $bsUserSession->getUserServices();
        foreach( $userServices as $userService )
            $allowedServices .= (strlen($allowedServices)<=0?'(':',') . $userService->getService()->getId();
        $allowedServices .= ')';

        $configs = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPServiceSettings' )
            ->getAllIn( $allowedServices );

        $basketsSettings = [
            'view_transfer_internal_basket' => false,
            'view_transfer_intermediate_basket' => false,
            'view_transfer_provider_basket' => false,
            'view_reloc_internal_basket' => false,
            'view_reloc_intermediate_basket' => false,
            'view_reloc_provider_basket' => false ];

        foreach( $configs as $config ){
            $basketsSettings['view_transfer_internal_basket'] |= $config['view_transfer_internal_basket' ];
            $basketsSettings['view_transfer_intermediate_basket'] |= $config['view_transfer_intermediate_basket' ];
            $basketsSettings['view_transfer_provider_basket'] |= $config['view_transfer_provider_basket' ];
            $basketsSettings['view_reloc_internal_basket'] |= $config['view_reloc_internal_basket' ];
            $basketsSettings['view_reloc_intermediate_basket'] |= $config['view_reloc_intermediate_basket' ];
            $basketsSettings['view_reloc_provider_basket'] |= $config['view_reloc_provider_basket' ];
        }

        return $this->jsonResponse( $basketsSettings );
//        }else{
//            $content = json_encode(array('message' => 'System Error : Only Ajax Request Allowed'));
//            return new Response($content, 419);
//        }
    }
}
