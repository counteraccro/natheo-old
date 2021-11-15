<?php

namespace App\Entity\Admin;

use App\Repository\Admin\ThemeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThemeRepository::class)
 * @ORM\Table(name="`cms_theme`")
 */
class Theme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $appVersion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $folderRef;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_depreciate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_selected;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_on;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppVersion(): ?string
    {
        return $this->appVersion;
    }

    public function setAppVersion(string $appVersion): self
    {
        $this->appVersion = $appVersion;

        return $this;
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

    public function getFolderRef(): ?string
    {
        return $this->folderRef;
    }

    public function setFolderRef(string $folderRef): self
    {
        $this->folderRef = $folderRef;

        return $this;
    }

    public function getIsDepreciate(): ?bool
    {
        return $this->is_depreciate;
    }

    public function setIsDepreciate(bool $is_depreciate): self
    {
        $this->is_depreciate = $is_depreciate;

        return $this;
    }

    public function getIsSelected(): ?bool
    {
        return $this->is_selected;
    }

    public function setIsSelected(bool $is_selected): self
    {
        $this->is_selected = $is_selected;

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
}
