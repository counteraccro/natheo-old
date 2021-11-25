<?php

namespace App\Entity\Modules;

use App\Entity\Modules\FAQ\FaqQuestionAnswerTag;
use App\Repository\Modules\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ORM\Table(name="`cms_tag`")
 */
#[UniqueEntity(['name'])]
class Tag
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
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=FaqQuestionAnswerTag::class, mappedBy="tag", orphanRemoval=true)
     */
    private $faqQuestionAnswerTags;

    public function __construct()
    {
        $this->faqQuestionAnswerTags = new ArrayCollection();
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|FaqQuestionAnswerTag[]
     */
    public function getFaqQuestionAnswerTags(): Collection
    {
        return $this->faqQuestionAnswerTags;
    }

    public function addFaqQuestionAnswerTag(FaqQuestionAnswerTag $faqQuestionAnswerTag): self
    {
        if (!$this->faqQuestionAnswerTags->contains($faqQuestionAnswerTag)) {
            $this->faqQuestionAnswerTags[] = $faqQuestionAnswerTag;
            $faqQuestionAnswerTag->setTag($this);
        }

        return $this;
    }

    public function removeFaqQuestionAnswerTag(FaqQuestionAnswerTag $faqQuestionAnswerTag): self
    {
        if ($this->faqQuestionAnswerTags->removeElement($faqQuestionAnswerTag)) {
            // set the owning side to null (unless already changed)
            if ($faqQuestionAnswerTag->getTag() === $this) {
                $faqQuestionAnswerTag->setTag(null);
            }
        }

        return $this;
    }
}
