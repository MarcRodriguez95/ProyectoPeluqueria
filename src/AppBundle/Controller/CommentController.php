<?php

namespace AppBundle\Controller;

use AppBundle\Form\ComentarioType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Comentario;
use AppBundle\Entity\Post;
use Trascastro\UserBundle\Form\UserBundle;

class CommentController extends Controller
{


    /**
     * @Route(path="/", name="app_comment_index")
     *
     */
    public function indexAction()
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Comentario');
        $m->flush();
        $comentarios = $repo->findAll();
        return $this->render(':productosTemplates:indice.html.twig', [
            'comentarios' => $comentarios
        ]);
    }

    /**
     * @Route (path="/commAdd/{id}", name="app_comment_add")
     * @Security("has_role('ROLE_USER')")
     */
    public function commentAddAction($id)
    {
        $Comentario = new Comentario();
        $form = $this->createForm(ComentarioType::class, $Comentario);
        return $this->render(':index:formComentario.html.twig',
            [
                'form'      => $form->createView(),
                'action'    => $this->generateUrl('app_comment_doAdd', ['id' => $id])
            ]
        );
    }

    /**
     * @Route (path="/commDoAdd/{id}", name="app_comment_doAdd")
     * @Security("has_role('ROLE_USER')")
     */
    public function commentDoAddAction(Request $request, Post $id)
    {
        $a = $this->getDoctrine()->getManager();
        $r = $a->getRepository('AppBundle:Post');
        $Comentario =  new Comentario();
        $post = $r->find($id);
        $form = $this->createForm(ComentarioType::class, $Comentario);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $Comentario->setPost($post);
            $user = $this->getUser();
            $Comentario->setUsuario($user);
            $m = $this->getDoctrine()->getManager();
            $m->persist($Comentario);
            $m->flush();
            return $this->redirectToRoute('app_index_index');
        }
        return $this->render(':index:listadoPosts.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_comment_doAdd', ['id' => $id])
            ]);
    }



    /**
     * @Route (path="/commUpdate/{id}", name="app_comment_update")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction($id)
    {
        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:Comentario');
        $comment = $repositorio->find($id);

        $form = $this->createForm(ComentarioType::class, $comment);

        return $this->render(':index:formComentario.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_comment_doUpdate', ['id' => $id]),
            ]);
    }


    /**
     * @Route (path="/commDoUpdate/{id}", name="app_comment_doUpdate")
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     */
    public function doUpdateAction($id, Request $request)
    {
        $Post = $comentario->getPost();

        if ($this->getUser() == $comentario->getUsuario() or $this->getUser() == 'Marc' or $this->getUser() == $Post->getUsuario()) {
            $a = $this->getDoctrine()->getManager();
            $repositorio = $a->getRepository('AppBundle:Comentario');
            $comment = $repositorio->find($id);
            $form = $this->createForm(ComentarioType::class, $comment);

            $form->handleRequest($request);
            if ($form->isValid()) {
                $a->flush();

                return $this->redirectToRoute('app_index_index');
            }

            return $this->render(':index:formComentario.html.twig',
                [
                    'form' => $form->createView(),
                    'action' => $this->generateUrl('app_post_doUpdate', ['id' => $id]),
                ]);
        }else{
            return $this->redirectToRoute('app_index_index');
        }
    }

    /**
     * @Route (path="/commRemove/{id}", name="app_comment_remove")
     * @Security("has_role('ROLE_USER')")
     */
    public function removeAction(Comentario $comentario)
    {

        $Post = $comentario->getPost();

        if ($this->getUser() == $comentario->getUsuario() or $this->getUser() == 'Marc' or $this->getUser() == $Post->getUsuario()) {
            $a = $this->getDoctrine()->getManager();
            $a->remove($comentario);
            $a->flush();

            $this->addFlash('messages', 'comentario eliminado');

            return $this->redirectToRoute('app_index_index');
        }else{
            return $this->redirectToRoute('app_index_index');
        }
    }
}
