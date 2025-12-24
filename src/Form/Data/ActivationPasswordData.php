<?php

namespace Webgiciel2\InitBureauSecurite\Form\Data;

use Webgiciel2\InitBureauSecurite\Form\Constraint\PasswordMatch;

#[PasswordMatch]
class ActivationPasswordData
{
    private ?string $password = null;
    private ?string $passwordConfirm = null;

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(?string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;
        return $this;
    }
}
