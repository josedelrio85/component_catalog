<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;
use App\Service\FileDataService;

class IndexController extends AbstractController
{

  private $fdserv;

  public function __construct(FileDataService $fdserv) {
    $this->fdserv = $fdserv;
  }

  /**
   * @Route("/", name="index")
   */
  public function index() {

    $list = $this->fdserv->getComponentsList();

    return $this->render('pages/index.html.twig', [
      'list' => $list,
    ]);
  }

  /**
   * @Route("/data-component", name="data-component")
   */
  public function dataComponent(Request $request) {
    $compname = $request->get("compname");
    $comp = $this->fdserv->getDataFromComponent($compname);
    $template = $this->renderView('pages/component.html.twig', [
      'component' => $comp,
    ]);

    return new JsonResponse([
      'success' => true,
      'template' => $template,
    ]);
  }

}