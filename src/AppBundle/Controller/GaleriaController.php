<?php

namespace AppBundle\Controller;

use AppBundle\Form\DescripcionImagenType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use AppBundle\Entity\starratingsystem;
use AppBundle\Entity\DescripcionImagen;
use Trascastro\UserBundle\Form\UserBundle;
use AppBundle\Entity\items;
class GaleriaController extends Controller
{
    /**
     * @Route (path="/", name="app_proyecto_galeria")
     */
    public function indexAction(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:items');
        $m->flush();

        $listItems = $repo->findBy(
            array(),
            array('id' => 'asc'),
            null,
            null
        );


        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:DescripcionImagen');
        $a->flush();
        $queryGaleria = $repositorio->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $queryGaleria,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render(':Proyecto:galeria.html.twig',
            [
                'galeria' => $pagination,
                'listItems' => $listItems,
            ]);
    }

    /**
     * @Route(path="/add", name="app_imagen_add")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction(){
        $descripcion_imagen = new DescripcionImagen();
        $form = $this->createForm(DescripcionImagenType::class, $descripcion_imagen);
        return $this->render(':Proyecto:form.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_imagen_doAdd'),
            ]);
    }

    /**
     * @param Request $request
     * @Route (path="/doAdd", name="app_imagen_doAdd")
     * @Security("has_role('ROLE_USER')")
     */
    public function doAddAction(Request $request){

        $descripcion_imagen = new DescripcionImagen();
        $form = $this->createForm(DescripcionImagenType::class, $descripcion_imagen);
        $form ->handleRequest($request);

        if ($form -> isValid()){
            $user = $this->getUser();
            $descripcion_imagen->setUsuario($user);
            $m = $this->getDoctrine()->getManager();
            $m->persist($descripcion_imagen);
            $m->flush();
            return $this->redirectToRoute('app_proyecto_galeria');
        }

        return $this->render(':Proyecto:form.html.twig',
            [
                'form'  => $form->createView(),
                'action'  => $this->generateUrl('app_imagen_doAdd')
            ]);
    }


    /**
     * @Route (path="/update/{id}", name="app_imagen_update")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction($id)
    {
        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:DescripcionImagen');
        $imagen = $repositorio->find($id);

        $form = $this->createForm(DescripcionImagenType::class, $imagen);

        return $this->render(':Proyecto:form.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_imagen_doUpdate', ['id' => $id]),
            ]);
    }

    /**
     * @Route (path="/doUpdate/{id}", name="app_imagen_doUpdate")
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     */
    public function doUpdateAction($id, Request $request)
    {
        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:DescripcionImagen');
        $imagen = $repositorio->find($id);
        $form = $this->createForm(DescripcionImagenType::class, $imagen);

        $form->handleRequest($request);
        if ($form->isValid()){
            $a->flush();

            return $this->redirectToRoute('app_proyecto_galeria');
        }

        return $this->render(':Proyecto:form.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_imagen_doUpdate', ['id' => $id]),
            ]);

    }

    /**
     * @Route (path="/remove/{id}", name="app_imagen_remove")
     * @Security("has_role('ROLE_USER')")
     */
    public function removeAction($id)
    {
        $a = $this->getDoctrine()->getManager();
        $repositorio = $a->getRepository('AppBundle:DescripcionImagen');

        $imagen = $repositorio->find($id);
        $a->remove($imagen);
        $a->flush();

        $this->addFlash('messages', 'imagen eliminada');

        return $this->redirectToRoute('app_proyecto_galeria');
    }


    /**
     * @return mixed
     */
    public function updateStar(Request $request)
    {
        $mediaId = $request->get('mediaId');
        $rate = $request->get('rate');

        $em = $this->getDoctrine()->getManager();

        $rateExists = $em->createQuery('SELECT s.id FROM AppBundle:starratingsystem s WHERE s.media = :media')
            ->setParameter('media', $mediaId)
            ->getResult();

        if ($rateExists != null) {
            $q = $em->createQuery('UPDATE AppBundle:starratingsystem s SET s.rate = s.rate + '.$rate.', s.nbrrate = s.nbrrate + 1 WHERE s.media = ?1')
                ->setParameter(1, $mediaId);
            $q->execute();
        } else {
            $newRate = new starratingsystem;
            $newRate->setMedia($mediaId);
            $newRate->setRate($rate);
            $newRate->setNbrrate(1);
            $em->persist($newRate);
            $em->flush();
        }

        $query = $em->createQuery('SELECT s.rate, s.nbrrate FROM AppBundle:starratingsystem s WHERE s.media = :media')
            ->setParameter('media', $mediaId);
        $result = $query->getResult();

        $response = new JsonResponse();
        $response->setData(array('avg' => round($result[0]['rate'] / $result[0]['nbrrate'], 2), 'nbrRate' => $result[0]['nbrrate']));
        return $response;
    }

}
