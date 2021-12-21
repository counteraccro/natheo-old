<?php

namespace App\Entity\Modules\Menu;

use App\Entity\User;
use App\Repository\Modules\Menu\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 * @ORM\Table(name="`cms_menu`")
 */
class Menu
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="menus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createOn;

    /**
     * @ORM\OneToMany(targetEntity=MenuElement::class, mappedBy="menu", orphanRemoval=true)
     */
    private $menuElements;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disabled;

    public function __construct()
    {
        $this->menuElements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreateOn(): ?\DateTimeInterface
    {
        return $this->createOn;
    }

    public function setCreateOn(\DateTimeInterface $createOn): self
    {
        $this->createOn = $createOn;

        return $this;
    }

    /**
     * @return Collection|MenuElement[]
     */
    public function getMenuElements(): Collection
    {
        return $this->menuElements;
    }

    public function addMenuElement(MenuElement $menuElement): self
    {
        if (!$this->menuElements->contains($menuElement)) {
            $this->menuElements[] = $menuElement;
            $menuElement->setMenu($this);
        }

        return $this;
    }

    public function removeMenuElement(MenuElement $menuElement): self
    {
        if ($this->menuElements->removeElement($menuElement)) {
            // set the owning side to null (unless already changed)
            if ($menuElement->getMenu() === $this) {
                $menuElement->setMenu(null);
            }
        }

        return $this;
    }

    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }
}
