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
    private $page_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta_description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_keyword;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_extra_metatags;

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
        return $this->page_title;
    }

    public function setPageTitle(?string $page_title): self
    {
        $this->page_title = $page_title;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(?string $meta_description): self
    {
        $this->meta_description = $meta_description;

        return $this;
    }

    public function getMetaKeyword(): ?string
    {
        return $this->meta_keyword;
    }

    public function setMetaKeyword(?string $meta_keyword): self
    {
        $this->meta_keyword = $meta_keyword;

        return $this;
    }

    public function getMetaExtraMetatags(): ?string
    {
        return $this->meta_extra_metatags;
    }

    public function setMetaExtraMetatags(?string $meta_extra_metatags): self
    {
        $this->meta_extra_metatags = $meta_extra_metatags;

        return $this;
    }
}
