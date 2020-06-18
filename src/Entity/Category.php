<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\OneToMany(targetEntity=Comp::class, mappedBy="idCategory")
     */
    private $comps;

    public function __construct()
    {
        $this->comps = new ArrayCollection();
    }

    public function setId(int $id): ?int
    {
        $this->id = $id;

        return $this;
    }

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

    /**
     * @return Collection|Comp[]
     */
    public function getComps(): Collection
    {
        return $this->comps;
    }

    public function addComp(Comp $comp): self
    {
        if (!$this->comps->contains($comp)) {
            $this->comps[] = $comp;
            $comp->setIdCategory($this);
        }

        return $this;
    }

    public function removeComp(Comp $comp): self
    {
        if ($this->comps->contains($comp)) {
            $this->comps->removeElement($comp);
            // set the owning side to null (unless already changed)
            if ($comp->getIdCategory() === $this) {
                $comp->setIdCategory(null);
            }
        }

        return $this;
    }
}
