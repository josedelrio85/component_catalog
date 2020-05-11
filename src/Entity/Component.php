<?php

namespace App\Entity;

class Component {

  private $path;
  private $html_template;
  private $html_data;
  private $html_content;
  private $styles_template;
  private $styles_content;
  
  public function __construct(){}

  public function getPath() {
    return $this->path;
  }

  public function setPath($path) {
    $this->path = $path;
  }

  public function getHtmlTemplate() {
    return $this->html_template;
  }

  public function setHtmlTemplate($html_template) {
    $this->html_template = $html_template;
  }

  public function getHtmlData() {
    return $this->html_data;
  }

  public function setHtmlData($html_data) {
    $this->html_data = $html_data;
  }

  public function getHtmlContent() {
    return $this->html_content;
  }

  public function setHtmlContent($html_content) {
    $this->html_content = $html_content;
  }


  public function getStylesTemplate() {
    return $this->styles_template;
  }

  public function setStylesTemplate($styles_template) {
    $this->styles_template = $styles_template;
  }

  public function getStylesContent() {
    return $this->styles_content;
  }

  public function setStylesContent($styles_content) {
    $this->styles_content = $styles_content;
  }
}