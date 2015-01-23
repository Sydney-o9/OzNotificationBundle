<?php

namespace Oz\NotificationBundle\Renderer;

use Oz\NotificationBundle\Model\NotificationInterface;


/**
 * Renders the template for an iOS Push Notification.
 *
 */
class iOSPushRenderer extends Renderer
{

    /**
     * Renders the template required for the notification
     *
     * @param \Oz\NotificationBundle\Model\NotificationInterface $notification
     *
     * @return array(
     *             'subject' => // Subject of the notification,
     *             'message' => // Message of the notification
     *         )
     */
    public function render(NotificationInterface $notification)
    {
        $template = $this->resolveTemplateName($notification);

        $subject = $this->twig->loadTemplate($template)
            ->renderBlock('subject', array('notification' => $notification));

        $message = $this->twig->loadTemplate($template)
            ->renderBlock('message', array('notification' => $notification));

        return compact('subject', 'message');
    }

}
