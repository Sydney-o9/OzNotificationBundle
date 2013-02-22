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
     * Returns the currently logged in user.
     *
     *
     * @throws \RuntimeException
     * @return \merk\NotificationBundle\Model\UserPreferencesInterface
     */
    protected function getUser()
    {

        $token = $this->container->get('security.context')->getToken();

        if (!$token->getUser() instanceof UserInterface) {
            throw new \RuntimeException('No user found in the security context');
        }

        $user = $token->getUser();

        return $user;
    }

    /**
     * Get Notification Manager
     *
     * @return \merk\NotificationBundle\ModelManager\NotificationManagerInterface
     */
    protected function getNotificationManager()
    {
        return $this->container->get('merk_notification.notification.manager');
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
        $user = $this->getUser();

        $emailNotifications = $this->getNotificationManager()->findNotificationsForUserByType($user, 'email');

        $smsNotifications = $this->getNotificationManager()->findNotificationsForUserByType($user, 'sms');

        $internalNotifications = $this->getNotificationManager()->findNotificationsForUserByType($user, 'internal');

        return $this->container->get('templating')->renderResponse('merkNotificationBundle:UserNotifications:show.html.twig', array(
            'email_notifications' => $emailNotifications,
            'sms_notifications' => $smsNotifications,
            'internal_notifications' => $internalNotifications,
        ));
    }

}