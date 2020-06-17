<?php

namespace App\Service;

// use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Entity\Comp;

class FileDataService {

  private $rootPath;
  private $filesystem;

  public function __construct(string $rootPath) {
    $basedir = $rootPath .DIRECTORY_SEPARATOR.'templates';
    $folder = 'helper';
    $path = $basedir.DIRECTORY_SEPARATOR.$folder;

    $helper = [
      'rootPath' => $rootPath,
      'basedir' => $basedir,
      'folder' => $folder,
      'path' => $path,
    ];

    $this->helper = $helper;
    $this->filesystem = new Filesystem();
  }

  public function createTemplate(Comp $comp) {
    $info = [];

    $rand = $comp->getId();
    $template = $this->helper['path'].DIRECTORY_SEPARATOR.$rand.'.html.twig';
    try {
      if(!$this->filesystem->exists($template)){
        $this->filesystem->dumpFile($template, $comp->getHtmlContent());
      }
    }catch(IOException $e){
      dump($e);
    }

    $style = $this->helper['path'].DIRECTORY_SEPARATOR.$rand.'.scss';
    try {
      if(!$this->filesystem->exists($template)){
        $this->filesystem->dumpFile($style, $comp->getStylesContent());
      }
    }catch(IOException $e){
      dump($e);
    }

    $info = [
      'template' =>  $this->helper['folder'].DIRECTORY_SEPARATOR.$rand.'.html.twig',
      'style' =>  $this->helper['folder'].DIRECTORY_SEPARATOR.$rand.'.scss',
    ];
    return $info;
  }

  function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
  }
}