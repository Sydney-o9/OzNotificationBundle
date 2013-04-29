<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Entity;

use Oz\NotificationBundle\Model\NotificationEvent AS BaseNotificationEvent;

/**
 * Doctrine ORM implementation of the NotificationEvent class.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
abstract class NotificationEvent extends BaseNotificationEvent
{
    /**
     * A temporary subject object variable. Used by Doctrine ORM listener
     * to convert it into a FullyQualifiedClassName/identifiers.
     *
     * @var mixed $subject
     */
    protected $subject;

    /**
     * An array of identifiers used to identify the subject object.
     *
     * @var array $subjectIdentifiers
     */
    protected $subjectIdentifiers;

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubjectClass()
    {
        return $this->notificationKey->getSubjectClass();
    }

    /**
     * @param array $subjectIdentifiers
     */
    public function setSubjectIdentifiers(array $subjectIdentifiers = null)
    {
        $this->subjectIdentifiers = $subjectIdentifiers;
    }

    /**
     * @return array
     */
    public function getSubjectIdentifiers()
    {
        return $this->subjectIdentifiers;
    }
}
