<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Preference Controller
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class UserPreferencesController extends ContainerAware
{
    /**
     * Returns the user preferences manager.
     *
     * @return \merk\NotificationBundle\ModelManager\UserPreferencesManagerInterface
     */
    protected function getUserPreferencesManager()
    {
        return $this->container->get('merk_notification.user_preferences.manager');
    }


    /**
     * TODO: delete before commit
     */
    protected function getFilterManager()
    {
        return $this->container->get('merk_notification.filter.manager');
    }

    /**
     * TODO: delete before commit
     */
    protected function getNotificationKeyManager()
    {
        return $this->container->get('merk_notification.notification_key.manager');
    }

    /**
     * TODO: delete before commit
     */
    protected function getNotificationEventManager()
    {
        return $this->container->get('merk_notification.notification_event.manager');
    }

    /**
     * Returns the user preferences object for the supplied user. If no user
     * is supplied, it uses the currently logged in user.
     *
     * @param null|\Symfony\Component\Security\Core\User\UserInterface $user
     * @return \merk\NotificationBundle\Model\UserPreferencesInterface
     */
    protected function getUserPreferences(UserInterface $user = null)
    {
        if (null === $user) {
            $token = $this->container->get('security.context')->getToken();

            if (!$token->getUser() instanceof UserInterface) {
                throw new \RuntimeException('No user found in the security context');
            }

            $user = $token->getUser();
        }

        $userPreferences = $this->getUserPreferencesManager()->getUserPreferences($user);


        return $userPreferences;
    }

    /**
     * Provides editing capability for users to edit their notification
     * preferences.
     *
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $preferences = $this->getUserPreferences();

        /** @var \Symfony\Component\Form\FormFactory $formBuilder  */
        $formBuilder = $this->container->get('form.factory');
        $form = $formBuilder->createNamed('merk_notification_user_preferences', 'merk_notification_user_preferences', $preferences);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {

                $this->getUserPreferencesManager()->update($preferences);

                $this->container->get('session')->setFlash('merk_notification_success', 'user_preferences.flash.updated');
                $redirectUrl = $this->container->get('router')->generate('merk_notification_user_preferences');

                return new RedirectResponse($redirectUrl);
            }
        }

        return $this->container->get('templating')->renderResponse('merkNotificationBundle:UserPreferences:edit.html.twig', array(
            'form' => $form->createView(),
            'preferences' => $preferences,
        ));
    }


    /**
     * Just for tests
     */
    public function testAction()
    {

        $user = $this->container->get('security.context')->getToken()->getUser();

        ladybug_dump_die($this->getUserPreferences($user));


        $notificationKey = 'job.created';
        $actor =$user;
        $subject = new \Acme\JobBundle\Entity\Job();
        $verb = 'patata';

        $event = $this->getNotificationEventManager()->create($notificationKey, $subject, $verb, $actor);
//        $this->getNotificationEventManager()->update($event);

        ladybug_dump_die($event);


//        $PreferencesManager = $this->getUserPreferencesManager()->getUserPreferences($user);

        $filterManager = $this->getFilterManager();

        $filters = $filterManager->findByUser($user);

        ladybug_dump($filters);

        $preferences = $this->getUserPreferencesManager()->create();

        $user = $this->container->get('security.context')->getToken()->getUser();

        $preferences->setUser($user);


        return $this->container->get('templating')->renderResponse('merkNotificationBundle:UserPreferences:test.html.twig', array(
            'preferences' => $preferences,
        ));
    }
}