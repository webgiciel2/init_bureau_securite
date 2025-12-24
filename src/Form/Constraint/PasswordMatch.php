<?php

namespace Webgiciel2\InitBureauSecurite\Form\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class PasswordMatch extends Constraint
{
    public string $message = 'Les mots de passe ne correspondent pas.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}
