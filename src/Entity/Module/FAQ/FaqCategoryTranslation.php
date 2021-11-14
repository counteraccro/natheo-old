<?php

namespace App\Entity\Module\FAQ;

use App\Repository\Module\FAQ\FaqCategoryTranslationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FaqCategoryTranslationRepository::class)
 * @ORM\Table(name="`cms_faq_category_translation`")
 */
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
}
