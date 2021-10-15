<?php

namespace App\Entity\Admin;

use App\Entity\User;
use App\Repository\Admin\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 * @ORM\Table(name="`cms_role`")
 */
#[UniqueEntity(['name', 'shortLabel'])]
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
    private string $name;

    /**
     * @ORM\Column(type="string", length=30)
     */
    #[Assert\Length(
        min: 3,
        max: 10,
    )]
    private string $shortLabel;

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

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $can_update;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="rolesCms")
     */
    private $users;

    public function __construct()
    {
        $this->routeRights = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getCanUpdate(): ?bool
    {
        return $this->can_update;
    }

    public function setCanUpdate(bool $can_update): self
    {
        $this->can_update = $can_update;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addRolesCms($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeRolesCms($this);
        }

        return $this;
    }
}
