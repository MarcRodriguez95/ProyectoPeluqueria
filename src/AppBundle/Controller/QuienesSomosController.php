<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
class QuienesSomosController extends Controller
{
    /**
     * @Route("/", name="app_proyecto_quienesSomos")
     */
    public function quienesSomosAction()
    {
        $m = $this->getDoctrine()->getManager();
        $userRepo = $m->getRepository('UserBundle:User');

        $users = $userRepo->findAll();

        return $this->render(':Proyecto:quienesSomos.html.twig',
            [
                'users' => $users,
                'title' => 'Users',
            ]
        );
    }
}
