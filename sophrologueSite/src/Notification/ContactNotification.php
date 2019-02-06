<?php

namespace App\Notification;
use App\Entity\Contact;
use App\Form\ContactType;

class ContactNotification
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;


    public function __construct( \Swift_Mailer $mailer) 
    {
        $this->mailer = $mailer;
    }

    public function notify(Contact $contact) 
    {
        //Génération d'un nouveau message avec l'instance de Swift_Mailer
        $message = (new \Swift_Message('Nouveau message de votre formulaire de contact'))
            ->setFrom($contact->getEmail())
            ->setTo('vanessa.roux891@orange.fr')
            //->setReplyTo($contact->getEmail())
            ->setBody(
                $this->renderView(
                    'app/mailContact.html.twig', 
                    ['contact' => $contact]
                ),
                'text/html'
                );
        $mailer->send($message);

    }
}