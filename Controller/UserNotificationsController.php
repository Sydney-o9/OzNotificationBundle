<?php
namespace merk\NotificationBundle\Controller;

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
     * @return \merk\NotificationBundle\Provider\ProviderInterface
     */
    protected function getProvider()
    {
        return $this->container->get('merk_notification.notification.provider');
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
        $emailNotifications = $this->getProvider()->getEmailNotifications(30);

        $smsNotifications = $this->getProvider()->getSMSNotifications(30);

        $internalNotifications = $this->getProvider()->getInternalNotifications(30);

        $nbUnreadInternalNotifications = $this->getProvider()->getNbUnreadInternalNotifications();

        return $this->container->get('templating')->renderResponse('merkNotificationBundle:UserNotifications:show.html.twig', array(
            'email_notifications' => $emailNotifications,
            'sms_notifications' => $smsNotifications,
            'internal_notifications' => $internalNotifications,
            'nb_internal_notifications'=> $nbUnreadInternalNotifications
        ));
    }

}
