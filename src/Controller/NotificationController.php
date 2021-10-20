<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/invoice/create")
     */
    public function create(NotifierInterface $notifier): Response
    {
        // ...

        // Create a Notification that has to be sent
        // using the "email" channel
        $notification = (new Notification('New Invoice', ['email']))
            ->content('You got a new invoice for 15 EUR.');

        // The receiver of the Notification
        $recipient = new Recipient(
            'testmail@testmail.com'
        );

        // Send the notification to the recipient
        $notifier->send($notification, $recipient);

        // ...

        return new Response(
            '<html><body>First Response</body></html>'
        );
    }
}
