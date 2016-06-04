<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Sydney-o9 <http://github.com/Sydney-o9>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Controller\Admin;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\FormType\Admin\NotificationKeyType;
use Doctrine\Common\Collections\ArrayCollection;

class NotificationKeyController extends ContainerAware
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
    public function indexAction(Request $request)
    {
        $notificationKeys = $this->container->get('oz_notification.notification_key.manager')
            ->findAll();

        return $this->container->get('templating')->renderResponse('OzNotificationBundle:Admin/NotificationKey:index.html.twig', array(
            'notificationKeys' => $notificationKeys
        ));
    }

    /**
     * Create a new Notification Key
     *
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {

        $class = $this->container
            ->getParameter('oz_notification.notification_key.class');
        $notificationKey = new $class;

        $form = $this->container
            ->get('form.factory')
            ->create('oz_notification_admin_notification_key', $notificationKey);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->container->get('oz_notification.notification_key.manager')
                ->update($notificationKey);

            return new RedirectResponse($this->container->get('router')->generate('oz_notification_admin_notification_key'));
        }

        return $this->container->get('templating')->renderResponse('OzNotificationBundle:Admin/NotificationKey:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Create a new Notification Key
     *
     *
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request, $id)
    {

        $notificationKey = $this->container->get('oz_notification.notification_key.manager')
                ->find($id);


        $originalMethodMetadata = new ArrayCollection();

        // Create an ArrayCollection of the current MethodMetadata objects in the database
        foreach ($notificationKey->getMethodMetadata() as $m) {
            $originalMethodMetadata->add($m);
        }

        $form = $this->container
            ->get('form.factory')
            ->create('oz_notification_admin_notification_key', $notificationKey);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->container->get('doctrine')->getManager();

            // remove the relationship between the tag and the Task
            foreach ($originalMethodMetadata as $m) {
                if (false === $notificationKey->getMethodMetadata()->contains($m)) {
                    $notificationKey->removeMethodMetadata($m);
                    $em->remove($m);
                }
            }

            $em->persist($notificationKey);
            $em->flush();

            return new RedirectResponse($this->container->get('router')->generate('oz_notification_admin_notification_key'));
        }

        return $this->container->get('templating')->renderResponse('OzNotificationBundle:Admin/NotificationKey:edit.html.twig', array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    /**
     * Delete Notification Key
     *
     *
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        $this->container->get('oz_notification.notification_key.manager')
                ->remove($id);

        return new RedirectResponse($this->container->get('router')->generate('oz_notification_admin_notification_key'));
    }

}
