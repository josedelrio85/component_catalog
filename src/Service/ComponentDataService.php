<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;
use App\Loader\YamlProductLoader;

class ComponentDataService {

  private $compdata;
  private $rootPath;

  public function __construct(string $rootPath) {
    $this->rootPath = $rootPath;
    $this->compdata = Yaml::parseFile($this->getPath());
  }

  public function getPath() {
    $configDir = $this->rootPath .DIRECTORY_SEPARATOR.'config';
    return $configDir.DIRECTORY_SEPARATOR.'component_data.yaml';
  }

  public function getComponentData() {
    $compdata = [];
    foreach($this->compdata as $i => $comp){
      if ($comp['active']){
        $compdata[$i] = $comp['data'];
      }
    }
    return $compdata;
  }

  public function getDataForComponent($key) {
    foreach($this->compdata as $i => $comp){
      if ($comp['active'] && $i === $key){
        return $comp['data'];
      }
    }
    return null;
  }
}