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

    $test = $this->getParameter('test');
    $leads_url = $this->getParameter('leads_url');
    $app_env = $this->getParameter('app_env');

    $probas = [
      'test' => $test,
      'leads_url' => $leads_url,
      'app_env' => $app_env,
    ];

    $list = $this->fdserv->getComponentsList();
    $data = $this->fdserv->getData();

    return $this->render('pages/index.html.twig', [
      'controller_name' => 'IndexController',
      'list' => $list,
      'data' => $data,
      'probas' => $probas,
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