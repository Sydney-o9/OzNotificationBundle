<?php

namespace merk\NotificationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Symfony\Component\Security\Core\SecurityContextInterface;
use merk\NotificationBundle\modelManager\FilterManagerInterface;

/**
 * Validator on the hole entity Filter.
 * Makes sure the filters are unique for each notificationKey.
 *
 */
class UniqueFilterValidator extends ConstraintValidator{


    /**
     * @var SecurityContextInterface $securityContext
     */
    private $securityContext;

    /**
     * @var FilterManagerInterface $filterManager
     */
    private $filterManager;

    /**
     * To record the name of the filter once it has been validated
     * and avoid duplicates
     *
     * @var array
     */
    private static $filterNames = array();


    public function __construct(SecurityContextInterface $securityContext, FilterManagerInterface $filterManager){

        $this->securityContext = $securityContext;

        $this->filterManager = $filterManager;

    }

    public function validate($value, Constraint $constraint)
    {
        $user = $this->securityContext->getToken()->getUser();

        $filter = $this->filterManager->getUserFilterByNotificationKey($user,$value->getNotificationKey());

        //Case 1: There is a duplicate in the filters that have been submitted
        if (in_array((string)$value->getNotificationKey(), self::$filterNames)) {

            $this->context->addViolation($constraint->message);
            return false;
        }

        array_push(self::$filterNames, (string)$value->getNotificationKey());

        //Case 2: There are no filters with this Notification Key in the database -> valid
        if ($filter === null){
            return true;
        }

        //Case 3: There is a filter in the db with the same Notification Key -> not-valid
        if($value->getId() != $filter->getId()){

            $this->context->addViolation($constraint->message);
            return false;

        }

        return true;
    }

}