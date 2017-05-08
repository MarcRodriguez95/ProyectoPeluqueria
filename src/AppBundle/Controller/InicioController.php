<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
    /**
     * @Route("/", name="app_proyecto_inicio")
     */
    public function inicioAction()
    {
        $m = $this->getDoctrine()->getManager();
        $userRepo = $m->getRepository('UserBundle:User');

        $users = $userRepo->findAll();

        return $this->render(':Proyecto:inicio.html.twig',
            [
                'users' => $users,
                'title' => 'Users',
            ]
        );
    }
}
