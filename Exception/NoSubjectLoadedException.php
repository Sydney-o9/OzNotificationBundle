<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney_o9 <https://github.com/Sydney-o9>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Exception;

use Oz\NotificationBundle\Model\NotificationEventInterface;

/**
 * NoSubjectLoadedException.
 */
class NoSubjectLoadedException extends \Exception implements ExceptionInterface
{
    /**
     * @param NotificationEventInterface $event
     */
    public function __construct(NotificationEventInterface $event, $code = 0, Exception $previous = null) {

        $message = $this->buildMessage($event);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Construct the message to display from the information available in the $event object
     *
     * @param NotificationEventInterface $event
     */
    public function buildMessage($event)
    {
        return  sprintf('Impossible to load subject for an event associated with the notification key "%s": the event does not contain subject identifiers.',
            (string)$event->getNotificationKey()
        );
    }
}
