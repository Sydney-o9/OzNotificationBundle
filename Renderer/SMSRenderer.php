<?php

namespace merk\NotificationBundle\Renderer;

use merk\NotificationBundle\Model\NotificationInterface;


/**
 * Renders the template for an SMS notification.
 *
 */
class SMSRenderer extends Renderer
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
     * TODO: consider caching the result of a render
     *
     * @param \merk\NotificationBundle\Model\NotificationInterface $notification
     *
     * @return array(
     *             'subject' => // Subject to be used for the notification,
     *             'body_txt' => // Body Txt of the notification
     *             'body_html' => // Body Html of the notification
     *         )
     */
    public function render(NotificationInterface $notification)
    {
        return parent::render($notification);
    }

}