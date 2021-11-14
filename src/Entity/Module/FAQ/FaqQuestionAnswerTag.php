<?php

namespace App\Entity\Module\FAQ;

use App\Entity\Module\Tag;
use App\Entity\User;
use App\Repository\Module\FAQ\FaqQuestionAnswerTagRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FaqQuestionAnswerTagRepository::class)
 * @ORM\Table(name="`cms_faq_question_answer_tag`")
 */
class FaqQuestionAnswerTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=FaqQuestionAnswer::class, inversedBy="Tags", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $FaqQuestionAnswer;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="faqQuestionAnswerTags", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $tag;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_on;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="faqQuestionAnswerTags")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

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
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
