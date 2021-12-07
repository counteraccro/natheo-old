<?php

namespace App\Entity\Admin\Page;

use App\Entity\Admin\page\Page;
use App\Entity\Media\Media;
use App\Entity\User;
use App\Repository\Admin\Page\PageMediaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageMediaRepository::class)
 * @ORM\Table(name="`cms_page_media`")
 */
class PageMedia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="pageMedia")
     * @ORM\JoinColumn(nullable=false)
     */
    private $page;

    /**
     * @ORM\ManyToOne(targetEntity=Media::class, inversedBy="pageMedia")
     * @ORM\JoinColumn(nullable=false)
     */
    private $media;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_on;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pageMedia")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
