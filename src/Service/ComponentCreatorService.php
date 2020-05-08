<?php

namespace App\Service;

use App\Entity\Component;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use App\Service\ComponentDataService;

class ComponentCreatorService {

  private $rootPath;
  private $finder;
  private $filesystem;
  private $basedir;
  private $compS;

  public function __construct(string $rootPath, ComponentDataService $compS) {
    $this->compS = $compS;
    $this->rootPath = $rootPath;
    $this->basedir = $rootPath .DIRECTORY_SEPARATOR.'templates';
    $folder = 'components';
    $components = $this->basedir.DIRECTORY_SEPARATOR.$folder;

    $this->filesystem = new Filesystem();

    $this->finder = new Finder();
  }

  public function create(Component $component){
    if(!empty($component)){
      $base = $this->basedir.DIRECTORY_SEPARATOR;
      $folder = explode('/', $component->getHtmlTemplate())[1];
      $path = $base.$component->getHtmlTemplate();

      try {
        $this->filesystem->dumpFile($path, $component->getHtmlContent());
      }catch(IOException $e){
        dump($e);
      }

      $styles_path = $base.$component->getStylesTemplate();
      try {
        $this->filesystem->dumpFile($styles_path, $component->getStylesContent());
      }catch(IOException $e){
        dump($e);
      }

      // html.data
      $this->setDataInYaml($component);
    }
  }

  protected function setDataInYaml(Component $component, bool $remove = false) {
    $comp_path = explode(DIRECTORY_SEPARATOR, $component->getPath());
    $key = end($comp_path);

    $yamlpath = $this->compS->getPath();
    $data_comp = $this->compS->getDataForComponent($key);

    $data_json = $component->getHtmlData();
    $data = json_decode($data_json, true);
    $pattern = array(
      $key => [
        "active"=> true,
        "data" => $data,
      ],
    );

    // dump($data); dump($data_comp);die();

    $alldata = $this->compS->getData();
    if(!is_null($data)){
      if(!is_null($data_comp)){
        if($remove){
          unset($alldata[$key]);
        } else {
          $alldata[$key] = $pattern[$key];
        }
        file_put_contents($yamlpath, null);
        $this->put_content($yamlpath, $alldata);
  
      } else {
        // there is not an entry in file, add to file
        $this->put_content($yamlpath, $pattern);  
      }

    } else {
      if(!is_null($data_comp)){  
        // there is an entry in file, we must remove it!
        unset($alldata[$key]);  
        file_put_contents($yamlpath, null);
        $this->put_content($yamlpath, $alldata);  
      }
    }
  }

  private function put_content($yamlpath, $alldata) {
    $yaml = Yaml::dump($alldata, 4, 2);
    file_put_contents($yamlpath, $yaml, FILE_APPEND);
  }

  public function delete(Component $component){
    if(!empty($component)){

      $base = $this->basedir.DIRECTORY_SEPARATOR;
      $folder = explode('/', $component->getHtmlTemplate());
      $path = $base.$folder[0].DIRECTORY_SEPARATOR.$folder[1];

      $this->setDataInYaml($component, true);

      if($this->filesystem->exists($path)){
        $this->filesystem->remove($path);
      }
    }
  }
}