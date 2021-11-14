<?php

namespace App\Entity\Module\FAQ;

use App\Repository\Module\FAQ\FaqQuestionAnswerTranslationRepository;
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
}
