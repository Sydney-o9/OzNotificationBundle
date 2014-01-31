<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Sydney-o9 <http://github.com/Sydney-o9>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;



class UserNotificationsController extends ContainerAware
{

    /**
     * Get Notification Provider
     *
     * @return \Oz\NotificationBundle\Provider\ProviderInterface
     */
    protected function getProvider()
    {
        return $this->container->get('oz_notification.notification.provider');
    }

    /**
     * Show all notifications to user.
     *
     *
     * @param Request $request
     * @return Response
     */
    public function showAction(Request $request)
    {
        $emailNotifications = $this->getProvider()->getEmailNotifications(10);

        $smsNotifications = $this->getProvider()->getSMSNotifications(10);

        $internalNotifications = $this->getProvider()->getInternalNotifications(10);

        $nbUnreadInternalNotifications = $this->getProvider()->getNbUnreadInternalNotifications();

        return $this->container->get('templating')->renderResponse('OzNotificationBundle:UserNotifications:show.html.twig', array(
            'email_notifications' => $emailNotifications,
            'sms_notifications' => $smsNotifications,
            'internal_notifications' => $internalNotifications,
            'nb_internal_notifications'=> $nbUnreadInternalNotifications
        ));
    }

}
