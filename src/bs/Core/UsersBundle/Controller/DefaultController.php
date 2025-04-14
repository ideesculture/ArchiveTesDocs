<?php

namespace bs\Core\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@bsCoreUsers/Default/index.html.twig');
    }
}
