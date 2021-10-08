<?php

namespace App\Entity\Admin;

use App\Repository\Admin\RouteRightRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RouteRightRepository::class)
 */
class RouteRight
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="routeRights")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity=Route::class, inversedBy="routeRights")
     * @ORM\JoinColumn(nullable=false)
     */
    private $route;

    /**
     * @ORM\Column(type="boolean")
     */
    private $can_read;

    /**
     * @ORM\Column(type="boolean")
     */
    private $can_edit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $can_delete;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }

    public function setRoute(?Route $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getCanRead(): ?bool
    {
        return $this->can_read;
    }

    public function setCanRead(bool $can_read): self
    {
        $this->can_read = $can_read;

        return $this;
    }

    public function getCanEdit(): ?bool
    {
        return $this->can_edit;
    }

    public function setCanEdit(bool $can_edit): self
    {
        $this->can_edit = $can_edit;

        return $this;
    }

    public function getCanDelete(): ?bool
    {
        return $this->can_delete;
    }

    public function setCanDelete(bool $can_delete): self
    {
        $this->can_delete = $can_delete;

        return $this;
    }
}
