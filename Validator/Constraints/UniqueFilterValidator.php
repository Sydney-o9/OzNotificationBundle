<?php

namespace merk\NotificationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Symfony\Component\Security\Core\SecurityContextInterface;
use merk\NotificationBundle\model\FilterManagerInterface;

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


    public function __construct(SecurityContextInterface $securityContext, FilterManagerInterface $filterManager){

        $this->securityContext = $securityContext;

        $this->filterManager = $filterManager;

    }

    public function validate($value, Constraint $constraint)
    {
        $user = $this->securityContext->getToken()->getUser();

        $filter = $this->filterManager->getUserFilterByNotificationKey($user,$value->getNotificationKey());

        //Case 1: There are no filters with this Notification Key in the database -> valid
        if ($filter === null){
            return true;
        }

        //Case 2: There is a filter in the db with the same Notification Key -> not-valid
        if($value->getId() != $filter->getId()){
            $this->context->addViolation($constraint->message);
            return false;
        }

        return true;
    }

}