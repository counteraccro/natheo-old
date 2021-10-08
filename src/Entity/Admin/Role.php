<?php

namespace App\Entity\Admin;

use App\Repository\Admin\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 * @ORM\Table(name="`cms_role`")
 */
class Role
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $shortLabel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=RouteRight::class, mappedBy="role", orphanRemoval=true)
     */
    private $routeRights;

    public function __construct()
    {
        $this->routeRights = new ArrayCollection();
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

    public function getShortLabel(): ?string
    {
        return $this->shortLabel;
    }

    public function setShortLabel(string $shortLabel): self
    {
        $this->shortLabel = $shortLabel;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|RouteRight[]
     */
    public function getRouteRights(): Collection
    {
        return $this->routeRights;
    }

    public function addRouteRight(RouteRight $routeRight): self
    {
        if (!$this->routeRights->contains($routeRight)) {
            $this->routeRights[] = $routeRight;
            $routeRight->setRole($this);
        }

        return $this;
    }

    public function removeRouteRight(RouteRight $routeRight): self
    {
        if ($this->routeRights->removeElement($routeRight)) {
            // set the owning side to null (unless already changed)
            if ($routeRight->getRole() === $this) {
                $routeRight->setRole(null);
            }
        }

        return $this;
    }
}
