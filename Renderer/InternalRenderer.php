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
     * @var string
     */
    protected $nameTemplate;

    /**
     * @param \Twig_Environment $twig
     * @param string $nameTemplate
     */
    public function __construct(\Twig_Environment $twig, $nameTemplate)
    {
        $this->twig    = $twig;

        $this->nameTemplate = $nameTemplate;
    }

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

}