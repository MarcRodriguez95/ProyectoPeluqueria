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
     * @Route (path="/", name="app_post_listado")
     */
    public function indexAction()
    {
        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:Post');
        $a->flush();
        $post = $repositorio->findAll();
        return $this->render(':index:listadoPosts.html.twig',
            [
                'post' => $post
            ]);
    }

    /**
     * @Route(path="/add", name="app_post_add")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction(){
        $post = new Post();
        $form = $this->createForm(MensajeType::class, $post);
        return $this->render(':index:form.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_post_doAdd'),
            ]);
    }

    /**
     * @param Request $request
     * @Route (path="/doAdd", name="app_post_doAdd")
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
            return $this->redirectToRoute('app_index_index');
        }

        return $this->render(':index:form.html.twig',
            [
                'form'  => $form->createView(),
                'action'  => $this->generateUrl('app_post_doAdd')
            ]);
    }

    /**
     * @Route (path="/update/{id}", name="app_post_update")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction($id)
    {
        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:Post');
        $post = $repositorio->find($id);

        $form = $this->createForm(MensajeType::class, $post);

        return $this->render(':index:form.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_post_doUpdate', ['id' => $id]),
            ]);
    }

    /**
     * @Route (path="/doUpdate/{id}", name="app_post_doUpdate")
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

            return $this->redirectToRoute('app_index_index');
        }

        return $this->render(':index:form.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_post_doUpdate', ['id' => $id]),
            ]);

    }

    /**
     * @Route (path="/remove/{id}", name="app_post_remove")
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

        return $this->redirectToRoute('app_index_index');
    }

}
