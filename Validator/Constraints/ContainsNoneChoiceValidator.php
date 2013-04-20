<?php

namespace Oz\NotificationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class ContainsNoneChoiceValidator extends ConstraintValidator{


    public function validate($value, Constraint $constraint)
    {
        return true;

        $this->context->addViolation($constraint->message);

        ladybug_dump_die($value);

        if (!preg_match('/^[a-zA-Za0-9]+$/', $value, $matches)) {
            $this->setMessage($constraint->message, array('%string%' => $value));

            return false;
        }

        return true;
    }
}