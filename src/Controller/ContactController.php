<?php
// src/Controller/ContactController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm('App\Form\ContactType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('contact'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            if($form->isValid()){
                // Send mail
                $formData = $form->getData();

                $message = (new \Swift_Message($formData['Sujet']))
                    ->setFrom($formData['email'])
                    ->setTo('davidgoubau@gmail.com')
                    ->setBody(
                        $this->renderView(
                            'pages/contact/mail.html.twig',
                                ['name' => $formData['Nom'],
                                'message' => $formData['message']]
                        ),
                        'text/html'
                    );

                    if($mailer->send($message))
                    {
                        $request->getSession()->getFlashBag()->add('success', 'Votre mail a bien été envoyé.');
                        
                    }
                    else{
                        $request->getSession()->getFlashBag()->add('ERROR', 'Votre mail n\'a pas pus être envoyé.');
                    }
                    // Everything OK, redirect to wherever you want ! :
                    
                    return $this->redirectToRoute('contact');
            }else{
                // An error ocurred, handle
                var_dump("Errooooor :(");
            }
        }

        return $this->render('pages/contact/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }
}