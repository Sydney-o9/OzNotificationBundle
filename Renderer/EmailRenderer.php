<?php

namespace merk\NotificationBundle\Renderer;

use merk\NotificationBundle\Model\NotificationInterface;


/**
 * Renders the template for an email notification.
 *
 */
class EmailRenderer extends Renderer
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
     * @param \merk\NotificationBundle\Model\NotificationInterface $notification
     *
     * @return array(
     *             'subject' => // Subject to be used for the notification,
     *             'body_txt' => // Body Text of the notification
     *             'body_html' => // Body Html of the notification
     *         )
     */
    public function render(NotificationInterface $notification)
    {
        $template = $this->resolveTemplateName($notification);

        $subject = $this->twig->loadTemplate($template)
            ->renderBlock('subject', array('notification' => $notification));

        $body_text = $this->twig->loadTemplate($template)
            ->renderBlock('body_text', array('notification' => $notification));

        $body_html = $this->twig->loadTemplate($template)
            ->renderBlock('body_html', array('notification' => $notification));

        return compact('subject', 'body_text', 'body_html');

    }

}