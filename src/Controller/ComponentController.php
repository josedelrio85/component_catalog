<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use App\Form\ComponentType;
use App\Entity\Comp;
use App\Entity\Category;
use App\Service\FileDataService;

class ComponentController extends AbstractController
{
  private $fds;

  public function __construct(FileDataService $fds){
    $this->fds = $fds;
  }

  /**
   * @Route("/", name="component")
   */
  public function index()
  {
    $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
    $components = [];
    foreach($categories as $cat){
      $components[$cat->getId()] = $this->getDoctrine()->getRepository(Comp::class)->getCompsByCat($cat->getId());
    }

    return $this->render('site-components/index.html.twig', [
      'list' => $categories,
      'listcomp' => $components,
    ]);
  }

  /**
   * @Route("/component_edit/{id}", name="component_edit")
   * 
   * @Security("is_granted('ROLE_ADMIN')")
   * 
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
   * 
   * @Security("is_granted('ROLE_ADMIN')")
   * 
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
        $em->persist($form->getData());
        $em->flush();

        $output = $this->fds->createTemplate($comp);

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
   * 
   * @Security("is_granted('ROLE_ADMIN')")
   * 
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

  /**
   * @Route("/data-component", name="data-component")
   */
  public function dataComponent(Request $request) {
    $idcomp = $request->get("idcomp");
    
    $em = $this->getDoctrine()->getManager();
    $comp = $em->getRepository(Comp::class)->find($idcomp);
    if(!$comp){
      return new JsonResponse([
        'success' => false,
        'template' => null,
      ]);
    }
    
    $output = $this->fds->createTemplate($comp);
    $template = $this->renderView('component/component.html.twig', [
      'component' => $comp,
      'template' => $output['template'],
      'data' => $comp->getHtmlData(),
    ]);

    return new JsonResponse([
      'success' => true,
      'template' => $template,
    ]);
  }

  /**
   * @Route("/data-category", name="data-category")
   */
  public function dataCategory(Request $request) {
    $idcat = $request->get("idcat");
    
    $em = $this->getDoctrine()->getManager();
    $list = $em->getRepository(Comp::class)->getCompsByCat($idcat);

    if(!$list){
      return new JsonResponse([
        'success' => false,
        'template' => null,
      ]);
    }
    
    $template = $this->renderView('site-components/sidebar-child/sidebar-child.html.twig', [
      'list' => $list,
    ]);

    // dump($template);die();
    return new JsonResponse([
      'success' => true,
      'template' => $template,
    ]);
  }

  /**
   * @Route("/initialize", name="initialize")
   */
  public function initialize(Request $request){
    $comps = $this->getDoctrine()->getRepository(Comp::class)->findAll();

    $this->fds->initializeTemplates($comps);

    return $this->redirectToRoute('component');
  }
}