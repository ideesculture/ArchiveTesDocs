<?php

namespace bs\IDP\BackofficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('bsIDPBackofficeBundle:Default:index.html.twig', array('name' => $name));
    }
}
