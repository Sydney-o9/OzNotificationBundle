<?php

namespace Oz\NotificationBundle\Model;

/**
 * Each entity that is notifiable must implement the NotifiableInterface
 *
 * e.g:
 * To send a notification when an item (for example \Entity\Item) has been bought,
 * The \Entity\Item must be an instance of NotifiableInterface
 *
 */
interface NotifiableInterface
{
}
