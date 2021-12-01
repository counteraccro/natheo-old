<?php

namespace App\Entity\Modules\FAQ;

use App\Repository\Modules\FAQ\FaqCategoryTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FaqCategoryTranslationRepository::class)
 * @ORM\Table(name="`cms_faq_category_translation`")
 */
#[UniqueEntity(['slug'])]
class FaqCategoryTranslation
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity=FaqCategory::class, inversedBy="faqCategoryTranslations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $FaqCategory;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pageTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaKeyword;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaExtraMetaTags;

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

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getFaqCategory(): ?FaqCategory
    {
        return $this->FaqCategory;
    }

    public function setFaqCategory(?FaqCategory $FaqCategory): self
    {
        $this->FaqCategory = $FaqCategory;

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

    public function getPageTitle(): ?string
    {
        return $this->pageTitle;
    }

    public function setPageTitle(?string $pageTitle): self
    {
        $this->pageTitle = $pageTitle;

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
}
