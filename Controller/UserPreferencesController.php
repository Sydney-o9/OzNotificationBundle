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
     * Provides editing capability for users to edit their notification
     * preferences.
     *
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {

        $form = $this->container->get('merk_notification.user_preferences.form.factory')->create();
        $formHandler = $this->container->get('merk_notification.user_preferences.form.handler');

        if ($userPreferences = $formHandler->process($form)) {

            $this->container->get('session')->setFlash('merk_notification_success', 'user_preferences.flash.updated');
            $redirectUrl = $this->container->get('router')->generate('merk_notification_user_preferences');
            return new RedirectResponse($redirectUrl);

        }

        return $this->container->get('templating')->renderResponse('merkNotificationBundle:UserPreferences:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
