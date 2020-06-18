<?php

namespace App\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Entity\Comp;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FileDataService {

  private $rootPath;
  private $filesystem;
  private $app_env;

  public function __construct(string $rootPath, string $app_env) {
    $basedir = $rootPath .DIRECTORY_SEPARATOR.'templates';
    $folder = 'helper';
    $path = $basedir.DIRECTORY_SEPARATOR.$folder;

    $helper = [
      'rootPath' => $rootPath,
      'basedir' => $basedir,
      'folder' => $folder,
      'path' => $path,
    ];

    $this->app_env = $app_env;
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
      if(!$this->filesystem->exists($style)){
        $this->filesystem->dumpFile($style, $comp->getStylesContent());

        $filename = $rand.'.scss';
        if($this->addImport($filename)){
          $this->launchNpm();
        }
      }
    }catch(IOException $e){
      throw $e;
      return array();
    }

    $info = [
      'template' =>  $this->helper['folder'].DIRECTORY_SEPARATOR.$rand.'.html.twig',
      'style' =>  $this->helper['folder'].DIRECTORY_SEPARATOR.$rand.'.scss',
    ];
    return $info;
  }

  private function addImport($filename){
    $basedir = $this->helper['rootPath'].DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR;

    try{
      // $main = $basedir.'main.scss';
      $main = $basedir.'_dynamic.scss';
      $import = '@import "../../templates/helper/'.$filename.'";';
      $this->filesystem->appendToFile($main, $import);
      $this->filesystem->appendToFile($main, "\n");
      return true;
    }catch(IOException $e){
      return false;
    }
    return false;
  }

  private function reset(){
    $basedir = $this->helper['rootPath'].DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR;

    try{
      $main = $basedir.'_dynamic.scss';
      $this->filesystem->dumpFile($main, "");
    }catch(IOException $e){
      return false;
    }
    return true;
  }

  private function launchNpm(){
    $cmdpath = $this->helper['rootPath'].DIRECTORY_SEPARATOR.'node_modules/.bin/npm';
    $cmd = "dev";
    if($this->app_env === "prod"){
      $cmd = "build";
    }

    $process = new Process([$cmdpath, 'run-script', $cmd]);
    $process->start();

    while ($process->isRunning()) {
      // waiting for process to finish
    }

    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }

    $output = $process->getOutput();
  }

  private function resetHelper() {
    $finder = new Finder();
    $iterator = $finder->files()->in($this->helper['path']);
    foreach($iterator as $file) {
      $path = $file->getRealpath();
      $this->filesystem->remove($path); 
    }
  }

  public function initializeTemplates(array $comps){
    // reset _dynamic.scss
    $this->reset();

    // empty helper
    $this->resetHelper();
    // return;

    foreach($comps as $comp){
      $rand = $comp->getId();
      $template = $this->helper['path'].DIRECTORY_SEPARATOR.$rand.'.html.twig';
      try {
        if(!$this->filesystem->exists($template)){
          $this->filesystem->dumpFile($template, $comp->getHtmlContent());
        }
      }catch(IOException $e){
        throw $e;
        return array();
      }
  
      $style = $this->helper['path'].DIRECTORY_SEPARATOR.$rand.'.scss';
      try {
        if(!$this->filesystem->exists($style)){
          $this->filesystem->dumpFile($style, $comp->getStylesContent());
  
          $filename = $rand.'.scss';
          if($this->addImport($filename)){
          }
        }
      }catch(IOException $e){
        throw $e;
        return array();
      }
    }
    $this->launchNpm();
  }
}
