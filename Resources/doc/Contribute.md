OzNotificationBundle - Contribute
=================================

You can help us improve this bundle
------------------------------------

* By opening an issue if you face a problem, complain about the existing code or simply propose new features

Features yet to be added include:

 * Setting NotificationBundle to listen for events sent with Symfony2's Event Dispatcher
 * Metadata (annotations, yml, xml) definitions on objects that will automatically trigger notification events
 * Additional sending methods, besides email and internal notifications (twitter, sms, facebook)

To do
-----

* At the moment the event that is triggered cannot have a null subject.
There are some cases where we don't want to have a subject in the notification.
We could have a field hasSubject or isSubjectible or whatever in the NotificationKey,
a boolean that would be true if we want absolutely want the event to have a subject,
and false otherwise.
This would avoid to have notification that fail because the subject was not loaded.
At the moment the logic is implemented in the notifier, see trigger method.

* At this time, only a Doctrine ORM implementation is provided. 
