<?php

namespace App\Entity;

use App\Repository\CompRepository;
use Doctrine\ORM\Mapping as ORM;
// use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompRepository::class)
 */
class Comp
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="comps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="json", nullable=true)
     * 
     */
    private $html_data;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $html_template;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $html_content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $styles_template;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $style_content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdCategory(): ?Category
    {
        return $this->idCategory;
    }

    public function setIdCategory(?Category $idCategory): self
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getHtmlData()
    {
        return $this->html_data;
    }

    public function setHtmlData($html_data): self
    {
        $this->html_data = json_decode($html_data, JSON_PRETTY_PRINT);
        return $this;
    }

    public function getHtmlTemplate(): ?string
    {
        return $this->html_template;
    }

    public function setHtmlTemplate(?string $html_template): self
    {
        $this->html_template = $html_template;

        return $this;
    }

    public function getHtmlContent(): ?string
    {
        return $this->html_content;
    }

    public function setHtmlContent(?string $html_content): self
    {
        $this->html_content = $html_content;

        return $this;
    }

    public function getStylesTemplate(): ?string
    {
        return $this->styles_template;
    }

    public function setStylesTemplate(?string $styles_template): self
    {
        $this->styles_template = $styles_template;

        return $this;
    }

    public function getStylesContent(): ?string
    {
        return $this->style_content;
    }

    public function setStylesContent(?string $style_content): self
    {
        $this->style_content = $style_content;

        return $this;
    }
}
