<?php

namespace App\Entity\Admin\Page;

use App\Repository\Admin\Page\PageTranslationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageTranslationRepository::class)
 * @ORM\Table(name="`cms_page_translation`")
 */
class PageTranslation
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
    private $pageTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $navigationTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaKeyword;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaExtraMetaTags;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="pageTranslations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $page;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageTitle(): ?string
    {
        return $this->pageTitle;
    }

    public function setPageTitle(string $pageTitle): self
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getNavigationTitle(): ?string
    {
        return $this->navigationTitle;
    }

    public function setNavigationTitle(string $navigationTitle): self
    {
        $this->navigationTitle = $navigationTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getMetaKeyword(): ?string
    {
        return $this->metaKeyword;
    }

    public function setMetaKeyword(?string $metaKeyword): self
    {
        $this->metaKeyword = $metaKeyword;

        return $this;
    }

    public function getMetaExtraMetaTags(): ?string
    {
        return $this->metaExtraMetaTags;
    }

    public function setMetaExtraMetaTags(?string $metaExtraMetaTags): self
    {
        $this->metaExtraMetaTags = $metaExtraMetaTags;

        return $this;
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
}
