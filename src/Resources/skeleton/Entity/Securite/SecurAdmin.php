<?php

namespace App\Entity\Securite;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'secur_admin')]
class SecurAdmin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private string $role;

    #[ORM\Column]
    private bool $active = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeVerif = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCrea = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateResetPassword = null;

    public function __construct()
    {
        $this->dateCrea = new \DateTime();
    }

    /* ===================== */
    /* ===== GETTERS ======= */
    /* ===================== */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getCodeVerif(): ?string
    {
        return $this->codeVerif;
    }

    public function getDateCrea(): ?\DateTimeInterface
    {
        return $this->dateCrea;
    }

    public function getDateResetPassword(): ?\DateTimeInterface
    {
        return $this->dateResetPassword;
    }

    /* ===================== */
    /* ===== SETTERS ======= */
    /* ===================== */

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function setCodeVerif(?string $codeVerif): self
    {
        $this->codeVerif = $codeVerif;
        return $this;
    }

    public function setDateResetPassword(?\DateTimeInterface $date): self
    {
        $this->dateResetPassword = $date;
        return $this;
    }

    /* ===================== */
    /* === MÉTHODES UTILES == */
    /* ===================== */

    public function requestPasswordReset(): void
    {
        $this->dateResetPassword = new \DateTime();
        $this->active = false;
    }

    public function activateAccount(): void
    {
        $this->active = true;
        $this->codeVerif = null;
    }
}
