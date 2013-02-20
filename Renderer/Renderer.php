<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\Renderer;

use merk\NotificationBundle\Model\NotificationInterface;


/**
 * Renders the template for a notification.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class Renderer implements RendererInterface
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
        $template = $this->resolveTemplateName($notification);

        $subject = $this->twig->loadTemplate($template)
            ->renderBlock('subject', array('notification' => $notification));

        $body = $this->twig->loadTemplate($template)
            ->renderBlock('body', array('notification' => $notification));


        return compact('subject', 'body');

    }

    /**
     * Resolves the template name to be used for the supplied notification.
     *
     * Starts by using the entire notification event key, while reducing it
     * to be less specific if the template is not found. If there is no
     * template found the base template for the sending method will be used.
     *
     *     some.event.key => some.event.key.email.txt.twig
     *                    => some.event.email.txt.twig
     *                    => some.email.txt.twig
     *                    => base.email.txt.twig
     *
     * @param \merk\NotificationBundle\Model\NotificationInterface $notification
     * @return string
     */
    protected function resolveTemplateName(NotificationInterface $notification)
    {
        $key = explode('.', $notification->getEvent()->getNotificationKey());

        while (count($key)) {
            $templateName = $this->buildTemplateName(
                implode('.', $key),
                $notification->getType(),
                'txt'
            );

            if ($this->exists($templateName)) {
                return $templateName;
            }

            array_pop($key);
        }

        return $this->buildTemplateName('base', $notification->getType(), 'txt');
    }

    /**
     * Combines given parameters into the template name template.
     *
     * @param string $notificationKey
     * @param string $method
     * @param string $format
     * @return string
     */
    protected function buildTemplateName($notificationKey, $method, $format = 'txt')
    {
        return sprintf($this->nameTemplate,
            $notificationKey,
            $method,
            $format
        );
    }

    /**
     * Checks if template exists
     *
     * @param $template
     * @return bool
     */
    protected function exists($template){
        try {
            $this->twig->loadTemplate($template);
        } catch (\Twig_Error_Loader $e) {
            return false;
        }
        return true;

    }
}