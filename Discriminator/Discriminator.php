<?php

namespace merk\NotificationBundle\Discriminator;

use merk\NotificationBundle\Renderer\RendererInterface;
use merk\NotificationBundle\NotificationFactory\NotificationFactoryInterface;

/**
 * The discriminator helps choosing the right notification, notificationFactory, renderer
 * to use depending on the method of sending (email, sms, internal messaging, ...).
 *
 */
class Discriminator
{

    /**
     * Parameters loaded from the config file
     *
     * @var array
     */
    protected $conf = array();


    /**
     * An array of notification factories. Key is the alias.
     *
     * @var array
     */
    protected $notificationFactories;

    /**
     * An array of renderers. Key is the alias.
     *
     * @var array
     */
    protected $renderers;

    /**
     *
     * @param array $notificationTypes
     * @param array $notificationFactories
     * @param array $renderers
     * @throws \InvalidArgumentException
     */
    public function __construct(array $notificationTypes, array $notificationFactories, array $renderers)
    {
        foreach ($renderers as $renderer) {
            if (!$renderer instanceof RendererInterface) {
                throw new \InvalidArgumentException(sprintf('Renderer %s must implement RendererInterface', get_class($renderer)));
            }
        }

        foreach ($notificationFactories as $notificationFactory) {
            if (!$notificationFactory instanceof NotificationFactoryInterface) {
                throw new \InvalidArgumentException(sprintf('Notification Factory %s must implement NotificationFactoryInterface', get_class($notificationFactory)));
            }
        }

        $this->buildConfiguration($notificationTypes);

        $this->notificationFactories = $notificationFactories;

        $this->renderers = $renderers;

    }


    /**
     * Get methods
     *
     * @return array
     */
    public function getMethods()
    {
        $methods = array();
        foreach ($this->conf as $notificationType => $conf) {
            $methods[] = $notificationType;

        }
        return $methods;
    }


    /**
     * Get classes
     *
     * @return array
     */
    public function getClasses()
    {
        $classes = array();

        foreach ($this->conf as $notificationType) {
            $classes[] = $notificationType['entity'];
        }

        return $classes;
    }


    /**
     * Get the class for a notification method
     *
     * @param $method
     * @throws \InvalidArgumentException
     * @return string
     */
    public function getClass($method)
    {
        if (!in_array($method, $this->getMethods())){
            throw new \InvalidArgumentException(sprintf('Method "%s" doesnt exist', $method));
        }

        return $this->conf[$method]['entity'];
    }



//    /**
//     * Create notification for a method
//     *
//     * @param $method
//     * @throws \InvalidArgumentException
//     * @return type
//     */
//    public function createNotification($method)
//    {
//        if (!in_array($method, $this->getMethods())){
//            throw new \InvalidArgumentException(sprintf('Method "%s" doesnt exist', $method));
//        }
//        $class = $this->getClass($method);
//
//        $factory = $this->getNotificationFactory($method);
//
//        $notification    = $factory::build($class);
//
//        return $notification;
//    }

    /**
     * Returns an array of renderer aliases that can be used to render
     * a notification.
     *
     * @return array
     */
    public function getRendererAliases()
    {
        return array_keys($this->renderers);
    }


    /**
     * Gets a renderer by its alias, or throws an exception when
     * the renderer does not exist.
     *
     * If no alias is mentioned, the renderer by default will be used
     *
     * @param string $alias
     * @return \merk\NotificationBundle\Sender\Agent\AgentInterface
     * @throws \InvalidArgumentException when the alias doesnt exist
     */
    protected function getRenderer($alias = 'default')
    {
        if (!isset($this->renderers[$alias])) {
            throw new \InvalidArgumentException(sprintf('Alias "%s" does not exist', $alias));
        }

        return $this->renderers[$alias];
    }


    /**
     * Returns an array of notification factory aliases that can be used to create
     * a notification.
     *
     * @return array
     */
    public function getNotificationFactoryAliases()
    {
        return array_keys($this->notificationFactories);
    }

    /**
     * Gets a notification factory by its alias, or throws an exception when
     * the renderer does not exist.
     *
     * If no alias is mentioned, the renderer by default will be used
     *
     * @param string $alias
     * @return \merk\NotificationBundle\Sender\Agent\AgentInterface
     * @throws \InvalidArgumentException when the alias doesnt exist
     */
    public function getNotificationFactory($alias = 'default')
    {
        if (!isset($this->notificationFactories[$alias])) {
            throw new \InvalidArgumentException(sprintf('Alias "%s" does not exist', $alias));
        }

        $notificationFactory = $this->notificationFactories[$alias];

        $notificationClass = $this->getClass($alias);

        $notificationFactory->setClass($notificationClass);

        return $notificationFactory;
    }


//    /**
//     *
//     * @param $method
//     * @param $notification
//     * @throws \InvalidArgumentException
//     * @return type
//     */
//    public function render($method, $notification)
//    {
//        if (!in_array($method, $this->getMethods())){
//            throw new \InvalidArgumentException(sprintf('Method "%s" doesnt exist', $method));
//        }
//
//        $renderer = $this->getRenderer($method);
//
//        $template = $renderer->render($notification);
//
//        return $template;
//    }

    /**
     * Build configuration from config file
     *
     * @param array $notificationTypes
     * @throws \InvalidArgumentException
     */
    protected function buildConfiguration(array $notificationTypes)
    {
        foreach ($notificationTypes as $notificationType) {

            $class = $notificationType['entity'];

            if (!class_exists($class)) {
                throw new \InvalidArgumentException(sprintf('UserDiscriminator, configuration error : "%s" not found', $class));
            }

        }

        $this->conf = $notificationTypes;

    }
}
