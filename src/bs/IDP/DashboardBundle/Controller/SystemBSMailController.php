<?php

namespace bs\IDP\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SystemBSMailController extends Controller
{
	public function viewedAction( )
	{
		$request = $this->getRequest();

		if($request->isXmlHttpRequest()) {

			$id = $request->request->get('id');
			if( $id == null )
				$return = array('error' => true);
			$msg = $this->getDoctrine()
        		->getRepository('bsIDPDashboardBundle:bsMessages')
        		->find($id);

			if($msg == null){
				$return = array('error' => true);
			}
			else{
				$msg->setBsViewed( true );

				$em = $this->getDoctrine()->getManager();
				$em->persist($msg);
				$em->flush();

				$return = array('error' => false);
			}

			$response = new Response(json_encode($return));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}else{
			return new Response('Not Ajax');
		}
	}
}
