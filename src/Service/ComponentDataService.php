<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;
use App\Loader\YamlProductLoader;

class ComponentDataService {

  private $compdata;

  public function __construct(string $rootPath) {
    $configDir = $rootPath .DIRECTORY_SEPARATOR.'config';
    $this->compdata = Yaml::parseFile($configDir.DIRECTORY_SEPARATOR.'component_data.yaml');
  }

  public function getComponentData() {
    $compdata = [];
    foreach($this->compdata['components'] as $i => $comp){
      if ($comp['active']){
        $compdata[$i] = $comp['data'];
      }
    }
    return $compdata;
  }
}