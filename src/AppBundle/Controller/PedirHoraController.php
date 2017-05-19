<?php

namespace AppBundle\Controller;

use AppBundle\Form\PedirHoraType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\PedirHora;
use Trascastro\UserBundle\Form\UserBundle;
use AppBundle\Event\HorarioEvent;

class PedirHoraController extends Controller
{
    /**
     * @Route (path="/", name="app_proyecto_pedirHora")
     * @param Request $request
     */
    public function indexAction()
    {
        return $this->render('Proyecto/pedirHora.html.twig');
    }
}
