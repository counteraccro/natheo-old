<?php

namespace App\Entity\Modules\FAQ;

use App\Repository\Modules\FAQ\FaqCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FaqCategoryRepository::class)
 * @ORM\Table(name="`cms_faq_category`")
 */
class FaqCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_on;

    /**
     * @ORM\OneToMany(targetEntity=FaqCategoryTranslation::class, mappedBy="FaqCategory", orphanRemoval=true, cascade={"persist"})
     */
    private $faqCategoryTranslations;

    /**
     * @ORM\OneToMany(targetEntity=FaqQuestionAnswer::class, mappedBy="FaqCategory", orphanRemoval=true, cascade={"persist"})
     */
    private $faqQuestionAnswers;

    public function __construct()
    {
        $this->faqCategoryTranslations = new ArrayCollection();
        $this->faqQuestionAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

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

    /**
     * @return Collection|FaqCategoryTranslation[]
     */
    public function getFaqCategoryTranslations(): Collection
    {
        return $this->faqCategoryTranslations;
    }

    public function addFaqCategoryTranslation(FaqCategoryTranslation $faqCategoryTranslation): self
    {
        if (!$this->faqCategoryTranslations->contains($faqCategoryTranslation)) {
            $this->faqCategoryTranslations[] = $faqCategoryTranslation;
            $faqCategoryTranslation->setFaqCategory($this);
        }

        return $this;
    }

    public function removeFaqCategoryTranslation(FaqCategoryTranslation $faqCategoryTranslation): self
    {
        if ($this->faqCategoryTranslations->removeElement($faqCategoryTranslation)) {
            // set the owning side to null (unless already changed)
            if ($faqCategoryTranslation->getFaqCategory() === $this) {
                $faqCategoryTranslation->setFaqCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FaqQuestionAnswer[]
     */
    public function getFaqQuestionAnswers(): Collection
    {
        return $this->faqQuestionAnswers;
    }

    public function addFaqQuestionAnswer(FaqQuestionAnswer $faqQuestionAnswer): self
    {
        if (!$this->faqQuestionAnswers->contains($faqQuestionAnswer)) {
            $this->faqQuestionAnswers[] = $faqQuestionAnswer;
            $faqQuestionAnswer->setFaqCategory($this);
        }

        return $this;
    }

    public function removeFaqQuestionAnswer(FaqQuestionAnswer $faqQuestionAnswer): self
    {
        if ($this->faqQuestionAnswers->removeElement($faqQuestionAnswer)) {
            // set the owning side to null (unless already changed)
            if ($faqQuestionAnswer->getFaqCategory() === $this) {
                $faqQuestionAnswer->setFaqCategory(null);
            }
        }

        return $this;
    }
}
