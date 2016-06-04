<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney-o9 <https://github.com/Sydney-o9/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Discriminator;

use Oz\NotificationBundle\Renderer\RendererInterface;
use Oz\NotificationBundle\NotificationFactory\NotificationFactoryInterface;

/**
 * The discriminator helps choosing the right notification, notificationFactory, renderer
 * to use depending on the method of sending (email, internal messaging, ...).
 *
 */
class NotificationDiscriminator implements NotificationDiscriminatorInterface
{

    /**
     * Parameters loaded from the config file
     *
     * @var array
     */
    protected $conf = array();


    /**
     *
     * @param array $notificationTypes
     * @throws \InvalidArgumentException
     */
    public function __construct(array $notificationTypes)
    {
        $this->buildConfiguration($notificationTypes);
    }


    /**
     * Get methods
     *
     * @return array
     */
    public function getMethods()
    {
        return array_keys($this->conf);

    }


    /**
     * Get classes
     *
     * @return array
     */
    public function getClasses()
    {
        $classes = array();

        foreach ($this->conf as $method => $conf) {
            $classes[] = $conf['entity'];
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

    /**
     * Gets a notification factory by its alias, or throws an exception when
     * the renderer does not exist.
     *
     * If no alias is mentioned, the renderer by default will be used
     *
     * @param string $method
     * @return \Oz\NotificationBundle\Sender\Agent\AgentInterface
     * @throws \InvalidArgumentException when the alias doesnt exist
     */
    public function getNotificationFactory($method)
    {
        if (!in_array($method, $this->getMethods())){
            throw new \InvalidArgumentException(sprintf('Method "%s" doesnt exist', $method));
        }

        $notificationFactory = $this->conf[$method]['notification_factory'];

        return $notificationFactory;
    }



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
                throw new \InvalidArgumentException(sprintf('NotificationDiscriminator, configuration error : "%s" not found', $class));
            }

        }

        $this->conf = $notificationTypes;

    }
}
