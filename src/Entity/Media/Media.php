<?php

namespace App\Entity\Media;

use App\Entity\Admin\Page\PageMedia;
use App\Repository\Media\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 * @ORM\Table(name="`cms_media`")
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $extension;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disabled;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_on;

    /**
     * @ORM\ManyToOne(targetEntity=Folder::class, inversedBy="media")
     * @ORM\JoinColumn(nullable=false)
     */
    private $folder;

    /**
     * @ORM\Column(type="text")
     */
    private $path;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\OneToMany(targetEntity=PageMedia::class, mappedBy="media", orphanRemoval=true)
     */
    private $pageMedia;

    public function __construct()
    {
        $this->pageMedia = new ArrayCollection();
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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

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

    public function getCreateOn(): ?\DateTimeInterface
    {
        return $this->create_on;
    }

    public function setCreateOn(\DateTimeInterface $create_on): self
    {
        $this->create_on = $create_on;

        return $this;
    }

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(?Folder $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

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
            $pageMedium->setMedia($this);
        }

        return $this;
    }

    public function removePageMedium(PageMedia $pageMedium): self
    {
        if ($this->pageMedia->removeElement($pageMedium)) {
            // set the owning side to null (unless already changed)
            if ($pageMedium->getMedia() === $this) {
                $pageMedium->setMedia(null);
            }
        }

        return $this;
    }
}
