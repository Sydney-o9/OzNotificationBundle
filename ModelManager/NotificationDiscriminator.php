<?php

namespace merk\NotificationBundle\ModelManager;

use Symfony\Component\Form\FormFactoryInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Form\Factory\FormFactory;


/**
 * Description of UserDiscriminator
 *
 * @author leonardo proietti (leonardo.proietti@gmail.com)
 * @author eux (eugenio@netmeans.net)
 */
class NotificationDiscriminator
{
    /**
     *
     * @var array
     */
    protected $conf = array();


    /**
     * @param array $notificationTypes
     */
    public function __construct(array $notificationTypes)
    {
        $this->buildConfig($notificationTypes);
    }


    /**
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
     *
     * @return array
     */
    public function getFactories()
    {
        $classes = array();

        foreach ($this->conf as $notificationType) {
            $classes[] = $notificationType['factory'];
        }

        return $classes;
    }


    /**
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
     *
     * @param $method
     * @throws \InvalidArgumentException
     * @return type
     */
    public function createNotification($method)
    {
        if (!in_array($method, $this->getMethods())){
            throw new \InvalidArgumentException(sprintf('Method "%s" doesnt exist', $method));
        }
        $class = $this->getClass($method);

        $factory = $this->getNotificationFactory($method);
        $notification    = $factory::build($class);

        return $notification;
    }


    /**
     *
     * @param $method
     * @throws \InvalidArgumentException
     * @return string
     */
    public function getNotificationFactory($method)
    {
        if (!in_array($method, $this->getMethods())){
            throw new \InvalidArgumentException(sprintf('Method "%s" doesnt exist', $method));
        }

        return $this->conf[$method]['factory'];
    }


    /**
     * Build configuration from config file
     *
     * @param array $notificationTypes
     * @throws \InvalidArgumentException
     */
    protected function buildConfig(array $notificationTypes)
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
