<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ComponentCreatorService;
use App\Service\FileDataService;
use App\Entity\Component;
use App\Form\Type\ComponentType;

class ComponentController extends AbstractController
{
    private $ccserv;
    private $fdserv;

    public function __construct(ComponentCreatorService $ccserv, FileDataService $fdserv) {
      $this->ccserv = $ccserv;
      $this->fdserv = $fdserv;
    }

    /**
     * @Route("/component", name="component")
     */
    public function index() {
      return $this->render('pages/index.html.twig', []);
    }

    /**
     * @Route("/component/change/{path}", name="component_change")
     */
    public function change(Request $request, string $path = null) {
      if(is_null($path)) {
        $comp = new Component();
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

        return $this->render('pages/component.html.twig', [
          'form' => $form->createView(),
        ]);
      }
      throw $this->createNotFoundException('The component does not exist');
    }

    /**
     * @Route("/component/delete/{path}", name="component_delete")
     */
    public function delete(Request $request, string $path = null) {
      $comp = $this->fdserv->getComponent($path);
      if(!is_null($comp)){

        $form = $this->createForm(ComponentType::class, $comp);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
          $comp = $form->getData();
          $this->ccserv->delete($comp);
          return $this->redirectToRoute('index');
        }
        return $this->render('pages/component.html.twig', [
          'form' => $form->createView(),
        ]);
      }
      throw $this->createNotFoundException('The component does not exist');
    }
}