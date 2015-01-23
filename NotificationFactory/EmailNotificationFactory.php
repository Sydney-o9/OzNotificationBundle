<?php

namespace Oz\NotificationBundle\NotificationFactory;

use Oz\NotificationBundle\Exception\NoSubjectFoundException;

class EmailNotificationFactory extends NotificationFactory
{

    /**
     * {@inheritDoc}
     */
    public function createFromUser($event, $user){

        $notification = $this->build($this->class);

        $notification->setEvent($event);
        $notification->setUser($user);

        $notification->setRecipientName($user->getUsername());
        $notification->setRecipientEmail($user->getEmail());

        $template = $this->renderer->render($notification);
        $notification->setSubject($template['subject']);
        $notification->setBodyText($template['body_text']);
        $notification->setBodyHtml($template['body_html']);

        return array($notification);

    }


}
