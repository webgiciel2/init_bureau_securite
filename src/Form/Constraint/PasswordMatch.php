<?php

namespace Webgiciel2\InitBureauSecurite\Form\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class PasswordMatch extends Constraint
{
    public string $message = 'Les mots de passe ne correspondent pas.';
}