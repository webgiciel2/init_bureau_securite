<?php

namespace Webgiciel2\InitBureauSecurite\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordMatchValidator extends ConstraintValidator
{
    public function validate($object, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordMatch) {
            throw new UnexpectedTypeException($constraint, PasswordMatch::class);
        }

        $password = $object->getPassword();
        $confirm = $object->getPasswordConfirm();

        if ($password !== $confirm) {
            $this->context->buildViolation($constraint->message)
                ->atPath('passwordConfirm')
                ->addViolation();
        }
    }
}