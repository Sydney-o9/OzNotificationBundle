<?php

namespace Oz\NotificationBundle\Twig\Extension;

use Oz\NotificationBundle\Model\InternalNotificationInterface;

/**
 * Twig extension
 *
 * @author Sydney-o9
 */
class InternalNotificationExtension extends \Twig_Extension
{
    /**
     * @var InternalRenderer
     */
    private $internalRenderer;

    /**
     * @param internalRenderer
     */
    public function __construct($internalRenderer)
    {
        $this->internalRenderer  = $internalRenderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'notification_component_render' => new \Twig_Function_Method($this, 'renderNotificationComponent', array('is_safe' => array('html'))),
        );
    }

    /**
     * Resolve Notification
     *
     * @param object $entity entity
     * @param string $method method
     *
     * @return string
     */
    protected function resolveNotification($entity, $method)
    {
        if ($entity instanceof InternalNotificationInterface) {
            return $entity ;
        } else {
            throw new \InvalidArgumentException(sprintf('Method "%s" only accepts an InternalNotification', $method));
        }
    }

    /**
     * @param object      $action   What Action to render
     * @param string|null $template Force template path
     *
     * @return string
     */
    public function renderNotification($notification, $template = null)
    {
        $notification = $this->resolveNotification($notification, __METHOD__);

        if (null === $template) {
            $template = $this->getDefaultTemplate($action);
        }

        $parameters = array(
            'notification' => $notification,
        );

        try {
            return $this->twig->render($template, $parameters);
        } catch (\Twig_Error_Loader $e) {
            if (null !== $this->config['fallback']) {
                return $this->twig->render($this->config['fallback'], $parameters);
            }

            throw $e;
        }
    }

    /**
     * Render an action component
     *
     * @param  object  $notification  Notification
     * @param  string  $component Component to render (subject, message)
     * @return string
     */
    public function renderNotificationComponent($notification, $component)
    {
        $notification = $this->resolveNotification($notification, __METHOD__);

        return $this->internalRenderer
          ->renderNotificationComponent($notification, $component);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'notification_render';
    }
}
