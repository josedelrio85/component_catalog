<?php

namespace App\Service;

use Symfony\Component\Finder\Finder;
use App\Service\ComponentDataService;
use App\Entity\Component;

class FileDataService {

  private $rootPath;
  private $compS;
  private $output;

  public function __construct(string $rootPath, ComponentDataService $compS) {
    $this->rootPath = $rootPath;
    $this->compS = $compS;
    $basedir = $rootPath .DIRECTORY_SEPARATOR.'templates';
    $folder = 'components';
    $components = $basedir.DIRECTORY_SEPARATOR.$folder;

    $componentData = $this->compS->getComponentData();
    $this->output = [];

    $finder = new Finder();
    $iterator  = $finder->files()->in($components);
    foreach($iterator as $file){
      $filepath = $file->getRealpath();
      $pathinfo = pathinfo($filepath);

      $dirname = explode(DIRECTORY_SEPARATOR, $pathinfo['dirname']);
      $foldername = end($dirname);
      $content = file_get_contents($filepath);

      $path = $folder.DIRECTORY_SEPARATOR.$foldername;
      $template = $path.DIRECTORY_SEPARATOR.$pathinfo['basename'];

      $this->output[$foldername]['path'] = $path;
      if($pathinfo['extension'] != 'scss') {
        $this->output[$foldername]['html']['template'] = $template;
        $this->output[$foldername]['html']['data'] = array_key_exists($foldername, $componentData) ? $componentData[$foldername] : null;
        $this->output[$foldername]['html']['content'] = $content;
      } else {
        $this->output[$foldername]['styles']['template'] = $template;
        $this->output[$foldername]['styles']['content'] = $content;
      }
    }
  }

  public function getData() { 
    return $this->output;
  }

  public function getComponentsList() {
    return array_keys($this->output);
  }

  public function getDataFromComponent($comp) {
    if(array_key_exists($comp, $this->output)){
      return $this->output[$comp];
    }
  }

  public function getComponent($pathname) {
    if(array_key_exists($pathname, $this->output)){
      $data = $this->output[$pathname];

      $comp = new Component();

      $comp->setPath($data['path']);
      $comp->setHtmlTemplate($data['html']['template']);
      $html_data = $data['html']['data'];
      if(is_array($html_data)) {
        $html_data_json = json_encode($html_data);
        $comp->setHtmlData($html_data_json);
      }
      $comp->setHtmlContent($data['html']['content']);

      $comp->setStylesTemplate($data['styles']['template']);
      $comp->setStylesContent($data['styles']['content']);
      return $comp;
    }
    return null;
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
}