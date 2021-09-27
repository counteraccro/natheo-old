<?php

namespace App\Entity\Admin;

use App\Repository\Admin\TranslationKeyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TranslationKeyRepository::class)
 * @ORM\Table(name="`cms_translation_key`")
 */
class TranslationKey
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $application;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $module;

    /**
     * @ORM\OneToMany(targetEntity=TranslationLabel::class, mappedBy="translationKey", orphanRemoval=true)
     */
    private $translationLabels;

    public function __construct()
    {
        $this->translationLabels = new ArrayCollection();
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

    public function getApplication(): ?string
    {
        return $this->application;
    }

    public function setApplication(string $application): self
    {
        $this->application = $application;

        return $this;
    }

    public function getModule(): ?string
    {
        return $this->module;
    }

    public function setModule(string $module): self
    {
        $this->module = $module;

        return $this;
    }

    /**
     * @return Collection|TranslationLabel[]
     */
    public function getTranslationLabels(): Collection
    {
        return $this->translationLabels;
    }

    public function addTranslationLabel(TranslationLabel $translationLabel): self
    {
        if (!$this->translationLabels->contains($translationLabel)) {
            $this->translationLabels[] = $translationLabel;
            $translationLabel->setTranslationKey($this);
        }

        return $this;
    }

    public function removeTranslationLabel(TranslationLabel $translationLabel): self
    {
        if ($this->translationLabels->removeElement($translationLabel)) {
            // set the owning side to null (unless already changed)
            if ($translationLabel->getTranslationKey() === $this) {
                $translationLabel->setTranslationKey(null);
            }
        }

        return $this;
    }
}
