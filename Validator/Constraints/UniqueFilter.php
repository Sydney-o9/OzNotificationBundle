<?php

namespace Oz\NotificationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueFilter extends Constraint
{
    public $message = 'The filter already exists.';

    public function validatedBy()
    {
        return 'oz_notification.filter.validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}
