<?php

namespace App\Entity\Admin\Page;

use App\Entity\Modules\Menu\MenuElement;
use App\Entity\User;
use App\Repository\Admin\Page\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 * @ORM\Table(name="`cms_page`")
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Page::class, mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\Column(type="boolean")
     */
    private $can_have_children;

    /**
     * @ORM\Column(type="boolean")
     */
    private $can_edit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $can_delete;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_on;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $edited_on;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $base;

    /**
     * @ORM\OneToMany(targetEntity=PageTranslation::class, mappedBy="page", orphanRemoval=true, cascade={"persist"})
     */
    private $pageTranslations;

    /**
     * @ORM\OneToMany(targetEntity=PageTag::class, mappedBy="page", orphanRemoval=true, cascade={"persist"})
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=PageMedia::class, mappedBy="page", orphanRemoval=true, cascade={"persist"})
     */
    private $pageMedia;

    /**
     * @ORM\OneToMany(targetEntity=MenuElement::class, mappedBy="page")
     */
    private $menuElements;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->pageTranslations = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->pageMedia = new ArrayCollection();
        $this->menuElements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getCanHaveChildren(): ?bool
    {
        return $this->can_have_children;
    }

    public function setCanHaveChildren(bool $can_have_children): self
    {
        $this->can_have_children = $can_have_children;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreateOn(): ?\DateTimeInterface
    {
        return $this->create_on;
    }

    public function setCreateOn(\DateTimeInterface $create_on): self
    {
        $this->create_on = $create_on;

        return $this;
    }

    public function getEditedOn(): ?\DateTimeInterface
    {
        return $this->edited_on;
    }

    public function setEditedOn(?\DateTimeInterface $edited_on): self
    {
        $this->edited_on = $edited_on;

        return $this;
    }

    public function getBase(): ?string
    {
        return $this->base;
    }

    public function setBase(string $base): self
    {
        $this->base = $base;

        return $this;
    }

    /**
     * @return Collection|PageTranslation[]
     */
    public function getPageTranslations(): Collection
    {
        return $this->pageTranslations;
    }

    public function addPageTranslation(PageTranslation $pageTranslation): self
    {
        if (!$this->pageTranslations->contains($pageTranslation)) {
            $this->pageTranslations[] = $pageTranslation;
            $pageTranslation->setPage($this);
        }

        return $this;
    }

    public function removePageTranslation(PageTranslation $pageTranslation): self
    {
        if ($this->pageTranslations->removeElement($pageTranslation)) {
            // set the owning side to null (unless already changed)
            if ($pageTranslation->getPage() === $this) {
                $pageTranslation->setPage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PageTag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(PageTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->setPage($this);
        }

        return $this;
    }

    public function removeTag(PageTag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            if ($tag->getPage() === $this) {
                $tag->setPage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PageMedia[]
     */
    public function getPageMedia(): Collection
    {
        return $this->pageMedia;
    }

    public function addPageMedium(PageMedia $pageMedium): self
    {
        if (!$this->pageMedia->contains($pageMedium)) {
            $this->pageMedia[] = $pageMedium;
            $pageMedium->setPage($this);
        }

        return $this;
    }

    public function removePageMedium(PageMedia $pageMedium): self
    {
        if ($this->pageMedia->removeElement($pageMedium)) {
            // set the owning side to null (unless already changed)
            if ($pageMedium->getPage() === $this) {
                $pageMedium->setPage(null);
            }
        }

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
            $menuElement->setPage($this);
        }

        return $this;
    }

    public function removeMenuElement(MenuElement $menuElement): self
    {
        if ($this->menuElements->removeElement($menuElement)) {
            // set the owning side to null (unless already changed)
            if ($menuElement->getPage() === $this) {
                $menuElement->setPage(null);
            }
        }

        return $this;
    }
}
