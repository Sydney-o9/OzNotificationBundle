<?php

namespace merk\NotificationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueFilter extends Constraint
{
    public $message = 'The filter already exists.';

    public function validatedBy()
    {
        return 'merk_notification.filter.validator';
    }


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}