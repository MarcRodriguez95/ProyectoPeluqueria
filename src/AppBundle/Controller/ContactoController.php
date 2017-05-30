<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

use AppBundle\Entity\Contact;

class ContactoController extends Controller
{

    /**
     * @Route("/", name="app_proyecto_contacto")
     */
    public function indexAction()
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


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/enviar-email", name="app_proyecto_email")
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request)
    {
        $contact = new Contact;



        # Add form fields
        $form = $this->createFormBuilder($contact)
            ->add('name', TextType::class)
            ->add('email', TextType::class)
            ->add('subject', TextType::class)
            ->add('message', TextareaType::class)
            ->add('Save', SubmitType::class)
            ->getForm();

        # Handle form response
        $form->handleRequest($request);



        # check if form is submitted and Recaptcha response is success
        if($form->isSubmitted() &&  $form->isValid()) {
            $name = $form['name']->getData();
            $email = $form['email']->getData();
            $subject = $form['subject']->getData();
            $message = $form['message']->getData();


            # set form data
            $contact->setName($name);
            $contact->setEmail($email);
            $contact->setSubject($subject);
            $contact->setMessage($message);

            # finally add data in database
            $sn = $this->getDoctrine()->getManager();
            $sn->persist($contact);
            $sn->flush();

            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($email)
                ->setTo('andres.perruquers@gmail.com')
                ->setBody($this->renderView(':Proyecto:enviarEmail.html.twig',array('name' => $name, 'email' => $email, 'message' => $message)),'text/html');
            $this->get('mailer')->send($message);

            return $this->redirectToRoute('app_proyecto_contacto');
        }


        return $this->render(':Proyecto:formEmail.html.twig',[
            'form' => $form -> createView()
        ]);
    }

    /*
    public function contactoAction(Request $request)
    {

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->add('mensaje', TextareaType::class)
            ->add('enviar', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();
            $mensaje = \Swift_Message::newInstance()
                ->setSubject('Support Form Submission')
                ->setFrom($data['email'])
                ->setTo('andres.perruquers@gmail.com')
                ->setBody(
                    $form->getData()['mensaje'],
                    'text/plain'
                );
            $this->get('mailer')->send($mensaje);
        }

        return $this->render(':Proyecto:contacto.html.twig',[
            'form_email' => $form -> createView()
        ]);
    }

    */


    /*
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
                            ':Proyecto:Mail:enviarEmail.html.twig',
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

        return $this->render(':Proyecto:Mail:mensajesEmail.html.twig', array(
            'form'   => $form->createView()
        ));
    }
    */
}
