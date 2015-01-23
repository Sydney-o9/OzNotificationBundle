<?php

namespace Oz\NotificationBundle\Renderer;

use Oz\NotificationBundle\Model\NotificationInterface;


/**
 * Renders the template for an SMS notification.
 *
 */
class SMSRenderer extends Renderer
{

    /**
     * Renders SMS notification template
     *
     * @param \Oz\NotificationBundle\Model\NotificationInterface $notification
     *
     * @return array(
     *             'subject' => // Subject of the SMS notification,
     *         )
     */
    public function render(NotificationInterface $notification)
    {
        return parent::render($notification);
    }

}
