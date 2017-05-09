<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class ContactoController extends Controller
{
    /**
     * @Route("/", name="app_proyecto_contacto")
     */
    public function contactoAction()
    {
        $m = $this->getDoctrine()->getManager();
        $userRepo = $m->getRepository('UserBundle:User');

        $users = $userRepo->findAll();

        return $this->render(':Proyecto:contacto.html.twig',
            [
                'users' => $users,
                'title' => 'Users',
            ]
        );
    }

    public function contactAction(Request $query)
    {
        $form = $this->createForm(new ContactoType());

        if ($query->isMethod('POST')) {
            $form->handleRequest($query);

            if ($form->isValid()) {
                $mailer = $this->get('mailer');
                $message = $mailer->createMessage()
                    ->setSubject($form->get('motivo')->getData())
                    ->setFrom('envio@ejemplo.com')
                    ->setTo('recibo@ejemplo.com')
                    ->setBody(
                        $this->renderView(
                            'Proyecto:recibirEmail.html.twig',
                            array(
                                'ip' => $query->getClientIp(),
                                'nombre' => $form->get('nombre')->getData(),
                                'email' => $form->get('email')->getData(),
                                'mensaje' => $form->get('mensaje')->getData()
                            )
                        )
                    );

                $mailer->send($message);

                $query->getSession()->getFlashBag()->add('success', 'Tu email ha sido enviado. Gracias');

                return $this->redirect($this->generateUrl('contact_blog'));
            }
        }

        return $this->render('Proyecto:contacto.html.twig', array(
            'form'   => $form->createView()
        ));
    }
}
