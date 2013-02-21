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
     * Renders SMS notification template
     *
     * @param \merk\NotificationBundle\Model\NotificationInterface $notification
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