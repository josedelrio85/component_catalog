<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ComponentType;
use App\Entity\Comp;

class ComponentController extends AbstractController
{
    /**
     * @Route("/component", name="component")
     */
    public function index()
    {
        $components = $this->getDoctrine()->getRepository(Comp::class)->findAll();

        return $this->render('component/index.html.twig', [
            'list' => $components,
        ]);
    }

    /**
     * @Route("/component_edit/{id}", name="component_edit")
     */
    public function edit(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $components = $em->getRepository(Comp::class)->findAll();

        $comp = $em->getRepository(Comp::class)->find($id);
        if(!$comp){
            throw $this->creatNotFoundException('The component '.$id.' does not exists');
        }

        $form = $this->createForm(ComponentType::class, $comp);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('component');
        }

        $title = "Edit";

        return $this->render('component/change.html.twig', [
            'list' => $components,
            'title' => $title,
            'comp'  => $comp,
            'form'  => $form->createView(),
        ]);
    }


     /**
     * @Route("/component_create/", name="component_create")
     */
    public function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $components = $em->getRepository(Comp::class)->findAll();

        $comp = new Comp();
        $form = $this->createForm(ComponentType::class, $comp);
        $title = 'Create component';

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // die(dump($form->getData()));
            // $comp = $form->getData();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirectToRoute('component');
        }

        return $this->render('component/change.html.twig', [
            'list' => $components,
            'title' => $title,
            'comp'  => $comp,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route("/component_delete/{id}", name="component_delete")
     */
    public function delete(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $components = $em->getRepository(Comp::class)->findAll();

        $comp = $em->getRepository(Comp::class)->find($id);
        if(!$comp){
            throw $this->creatNotFoundException('The component '.$id.' does not exists');
        }

        $form = $this->createForm(ComponentType::class, $comp);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->remove($form->getData());
            $em->flush();
            return $this->redirectToRoute('component');
        }
        $title = "Delete";

        return $this->render('component/change.html.twig', [
            'list' => $components,
            'title' => $title,
            'comp'  => $comp,
            'form'  => $form->createView(),
        ]);
    }
}
