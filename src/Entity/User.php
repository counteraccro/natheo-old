<?php

namespace App\Entity;

use App\Entity\Admin\Role;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`cms_user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $publicationName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $avatar;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastPasswordUpdae;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password_strenght;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     * @ORM\JoinTable(name="cms_user_role")
     */
    private $rolesCms;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDisabled;

    public function __construct()
    {
        $this->rolesCms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPublicationName(): ?string
    {
        return $this->publicationName;
    }

    public function setPublicationName(string $publicationName): self
    {
        $this->publicationName = $publicationName;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getLastPasswordUpdae(): ?\DateTimeInterface
    {
        return $this->lastPasswordUpdae;
    }

    public function setLastPasswordUpdae(?\DateTimeInterface $lastPasswordUpdae): self
    {
        $this->lastPasswordUpdae = $lastPasswordUpdae;

        return $this;
    }

    public function getPasswordStrenght(): ?string
    {
        return $this->password_strenght;
    }

    public function setPasswordStrenght(string $password_strenght): self
    {
        $this->password_strenght = $password_strenght;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRolesCms(): Collection
    {
        return $this->rolesCms;
    }

    public function addRolesCms(Role $rolesCm): self
    {
        if (!$this->rolesCms->contains($rolesCm)) {
            $this->rolesCms[] = $rolesCm;
        }

        return $this;
    }

    public function removeRolesCms(Role $rolesCm): self
    {
        $this->rolesCms->removeElement($rolesCm);

        return $this;
    }

    public function getIsDisabled(): ?bool
    {
        return $this->isDisabled;
    }

    public function setIsDisabled(bool $isDisabled): self
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }
}
