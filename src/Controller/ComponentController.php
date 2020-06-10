<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ComponentCreatorService;
use App\Service\FileDataService;
use App\Entity\Component;
use App\Form\ComponentType;

class ComponentController extends AbstractController
{
    private $ccserv;
    private $fdserv;

    public function __construct(ComponentCreatorService $ccserv, FileDataService $fdserv) {
      $this->ccserv = $ccserv;
      $this->fdserv = $fdserv;
    }

    /**
     * @Route("/component/change/{path}", name="component_change")
     */
    public function change(Request $request, string $path = null) {
      $list = $this->fdserv->getComponentsList();
      $title = 'Edit';

      if(is_null($path)) {
        $comp = new Component();
        $title = 'Create component';
      } else {
        $comp = $this->fdserv->getComponent($path);
      } 

      if(!is_null($comp)){
        $form = $this->createForm(ComponentType::class, $comp);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
          $comp = $form->getData();
          $this->ccserv->create($comp);
          return $this->redirectToRoute('index');
        }

        return $this->render('pages/component_change.html.twig', [
          'form'  => $form->createView(),
          'list'  => $list,
          'title' => $title,
          'comp'  => $comp,
        ]);
      }
      throw $this->createNotFoundException('The component does not exist');
    }

    /**
     * @Route("/component/delete/{path}", name="component_delete")
     */
    public function delete(Request $request, string $path = null) {
      $list = $this->fdserv->getComponentsList();
      $title = 'Delete';
      
      $comp = $this->fdserv->getComponent($path);
      if(!is_null($comp)){

        $form = $this->createForm(ComponentType::class, $comp);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
          $comp = $form->getData();
          $this->ccserv->delete($comp);
          return $this->redirectToRoute('index');
        }
        return $this->render('pages/component_change.html.twig', [
          'form' => $form->createView(),
          'list' => $list,
          'title' => $title,
          'comp'  => $comp,
        ]);
      }
      throw $this->createNotFoundException('The component does not exist');
    }
}
