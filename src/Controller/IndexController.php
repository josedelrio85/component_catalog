<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use App\Service\FileDataService;

class IndexController extends AbstractController
{

  private $fdserv;

  public function __construct(FileDataService $fdserv) {
    $this->fdserv = $fdserv;
  }


  // data:
  //   path:
  //   html:
  //     template:
  //     data:
  //     content:
  //   styles:
  //     template:
  //     content:


  public function index() {

    $data = $this->fdserv->getData();
    // dump($data);die();

    return $this->render('pages/index.html.twig', [
      'controller_name' => 'IndexController',
      'data' => $data,
    ]);
  }
}
