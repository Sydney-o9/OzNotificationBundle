<?php

namespace merk\NotificationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsNoneChoice extends Constraint
{
    public $message = 'You cannot use sms at the moment.';


    public function validatedBy()
    {

        return get_class($this).'Validator';
    }
}