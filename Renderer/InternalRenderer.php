<?php

namespace Oz\NotificationBundle\Renderer;

use Oz\NotificationBundle\Model\NotificationInterface;


/**
 * Renders the template for an email notification.
 *
 */
class InternalRenderer extends Renderer
{

    /**
     * Renders the template required for the notification
     *
     * @param \Oz\NotificationBundle\Model\NotificationInterface $notification
     *
     * @return array(
     *             'subject' => // Subject to be used for the notification,
     *             'message' => // Body Text of the notification
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

    /**
     * Renders individual block inside the template
     *
     * @param \Oz\NotificationBundle\Model\NotificationInterface $notification
     * @param string Name of the block
     *
     * @return string The block rendered
     */
    public function renderNotificationComponent(NotificationInterface $notification, $component)
    {
        $template = $this->resolveTemplateName($notification);

        $component = $this->twig->loadTemplate($template)
            ->renderBlock($component, array('notification' => $notification));

        return $component;
    }
}
