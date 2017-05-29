<?php

namespace AppBundle\Controller;

use AppBundle\Form\MensajeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;
use Trascastro\UserBundle\Form\UserBundle;

class PostController extends Controller
{
    /**
     * @Route (path="/", name="app_proyecto_mensajes")
     */
    public function indexAction(Request $request)
    {
        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:Post');
        $a->flush();
        $queryExperiencia = $repositorio->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $queryExperiencia,
            $request->query->getInt('page', 1),
            2
        );
        return $this->render(':Proyecto:ExperienciaPeluqueria.html.twig',
            [
                'post' => $pagination,
            ]);



    }

    /**
     * @Route(path="/add", name="app_proyecto_addExperiencia")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction(){
        $post = new Post();
        $form = $this->createForm(MensajeType::class, $post);
        return $this->render(':Proyecto:formExperienciaPeluqueria.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_proyecto_doAddExperiencia'),
            ]);
    }

    /**
     * @param Request $request
     * @Route (path="/doAdd", name="app_proyecto_doAddExperiencia")
     * @Security("has_role('ROLE_USER')")
     */
    public function doAddAction(Request $request){

        $post = new Post();
        $form = $this->createForm(MensajeType::class, $post);
        $form ->handleRequest($request);

        if ($form -> isValid()){
            $user = $this->getUser();
            $post->setUser($user);
            $m = $this->getDoctrine()->getManager();
            $m->persist($post);
            $m->flush();
            return $this->redirectToRoute('app_proyecto_mensajes');
        }

        return $this->render(':Proyecto:formExperienciaPeluqueria.html.twig',
            [
                'form'  => $form->createView(),
                'action'  => $this->generateUrl('app_proyecto_doAddExperiencia')
            ]);
    }

    /**
     * @Route (path="/update/{id}", name="app_proyecto_updateExperiencia")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction($id)
    {
        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:Post');
        $post = $repositorio->find($id);

        $form = $this->createForm(MensajeType::class, $post);

        return $this->render(':Proyecto:formExperienciaPeluqueria.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_proyecto_doUpdateExperiencia', ['id' => $id]),
            ]);
    }

    /**
     * @Route (path="/doUpdate/{id}", name="app_post_doUpdateExperiencia")
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     */
    public function doUpdateAction($id, Request $request)
    {
        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:Post');
        $post = $repositorio->find($id);
        $form = $this->createForm(MensajeType::class, $post);

        $form->handleRequest($request);
        if ($form->isValid()){
            $a->flush();

            return $this->redirectToRoute('app_proyecto_mensajes');
        }

        return $this->render(':Proyecto:formExperienciaPeluqueria.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_proyecto_doUpdateExperiencia', ['id' => $id]),
            ]);

    }

    /**
     * @Route (path="/remove/{id}", name="app_proyecto_removeExperiencia")
     * @Security("has_role('ROLE_USER')")
     */
    public function removeAction($id)
    {
        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:Post');

        $post = $repositorio->find($id);
        $a->remove($post);
        $a->flush();

        $this->addFlash('messages', 'post eliminado');

        return $this->redirectToRoute('app_proyecto_mensajes');
    }




}
