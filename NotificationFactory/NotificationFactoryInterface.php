<?php

namespace Oz\NotificationBundle\NotificationFactory;

interface NotificationFactoryInterface
{
    /**
     *
     * @param string $class
     * @throws \Exception
     * @return \Oz\NotificationBundle\Model\Notification (a child of)
     */
    public function build($class);


    /**
     * Set class for notification
     *
     * @param $class
     */
    public function setClass($class);

    /**
     * Set the renderer
     *
     * @param \Oz\NotificationBundle\Renderer\RendererInterface $renderer
     */
    public function setRenderer($renderer);

    /**
     * Create notification using the filter used by the user
     *
     * @param $event
     * @param $filter
     * @return array \Oz\NotificationBundle\Model\Notification
     */
    public function createFromFilter($event, $filter);

    /**
     * Create notification using the user
     *
     * @param $event
     * @param $user
     * @return array \Oz\NotificationBundle\Model\Notification
     */
    public function createFromUser($event, $user);

}
