<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BanWordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var BanWord $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $value = strtolower($value);
        foreach ($constraint->bannedWords as $bannedWord) {
            if (str_contains($value, $bannedWord)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ banWord }}', $value)
                    ->addViolation();
                return;
            }
        }


    }
}
