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
      $template = $component->getHtmlTemplate();
      $base = $this->basedir.DIRECTORY_SEPARATOR;
      // TODO check owner of the files!!!
      $folder = "components/{$template}";

      if(!$this->filesystem->exists($folder)){
        $this->filesystem->mkdir($base.$folder);
      }

      //html.template + html.content
      $path = $folder.DIRECTORY_SEPARATOR."{$template}.html.twig";
      try {
        $this->filesystem->dumpFile($base.$path, $component->getHtmlContent());
      }catch(IOException $e){
        dump($e);
      }

      //styles.template + styles.content
      $styles_template = $component->getStylesTemplate();
      $styles_path = $folder.DIRECTORY_SEPARATOR."{$styles_template}.scss";
      try {
        $this->filesystem->dumpFile($base.$styles_path, $component->getStylesContent());
      }catch(IOException $e){
        dump($e);
      }

      // html.data
      // $this->test();

    }
  }

  public function test(Component $component) {
    // html.data
    $comp_path = explode(DIRECTORY_SEPARATOR, $component->getPath());
    $key = end($comp_path);

    $yamlpath = $this->compS->getPath();
    if(!is_null($this->compS->getDataForComponent($key))){
      // there is an entry in file, we must update it!
      // think about it!!!
    } else {
      // there is not an entry in file, add to file
      $data_json = $component->getHtmlData();
      $data = json_decode($data_json, true);
      $pattern = array(
        $key => [
          "active"=> true,
          "data" => $data,
        ],
      );
      $yaml = Yaml::dump($pattern, 4, 2);
      file_put_contents($yamlpath, $yaml, FILE_APPEND);
      // file_put_contents($yamlpath, PHP_EOL, FILE_APPEND);
    }
  }

  public function delete(Component $component){
    if(!empty($component)){
      $template = $component->getHtmlTemplate();
      $base = $this->basedir.DIRECTORY_SEPARATOR;
      $folder = "components/{$template}";

      if(!$this->filesystem->exists($folder)){
        $this->filesystem->delete($base.$folder);
      }
    }
  }
}