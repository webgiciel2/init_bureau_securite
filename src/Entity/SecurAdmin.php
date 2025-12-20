<?php

namespace Webgiciel2\InitBureauSecurite\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'secur_admin')]
class SecurAdmin implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeVerif = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCrea = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $passwordResetAt = null;

    /* ==========================
       GETTERS / SETTERS
       ========================== */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
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

    public function getRoles(): array
    {
        $roles = $this->roles ?? [];
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getCodeVerif(): ?string
    {
        return $this->codeVerif;
    }

    public function setCodeVerif(?string $codeVerif): self
    {
        $this->codeVerif = $codeVerif;
        return $this;
    }

    public function getDateCrea(): ?\DateTimeInterface
    {
        return $this->dateCrea;
    }

    public function setDateCrea(\DateTimeInterface $dateCrea): self
    {
        $this->dateCrea = $dateCrea;
        return $this;
    }

    public function getPasswordResetAt(): ?\DateTimeInterface
    {
        return $this->passwordResetAt;
    }

    public function setPasswordResetAt(?\DateTimeInterface $passwordResetAt): self
    {
        $this->passwordResetAt = $passwordResetAt;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // Rien à faire pour l’instant
    }

}
