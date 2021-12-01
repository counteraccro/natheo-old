<?php

namespace App\Entity\Modules\FAQ;

use App\Repository\Modules\FAQ\FaqQuestionAnswerTranslationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FaqQuestionAnswerTranslationRepository::class)
 * @ORM\Table(name="`cms_faq_question_answer_translation`")
 */
class FaqQuestionAnswerTranslation
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
    private $question;

    /**
     * @ORM\Column(type="text")
     */
    private $answer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pageTitle;

    /**
     * @ORM\ManyToOne(targetEntity=FaqQuestionAnswer::class, inversedBy="faqQuestionAnswerTranslations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $FaqQuestionAnswer;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $language;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
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

    public function getFaqQuestionAnswer(): ?FaqQuestionAnswer
    {
        return $this->FaqQuestionAnswer;
    }

    public function setFaqQuestionAnswer(?FaqQuestionAnswer $FaqQuestionAnswer): self
    {
        $this->FaqQuestionAnswer = $FaqQuestionAnswer;

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
