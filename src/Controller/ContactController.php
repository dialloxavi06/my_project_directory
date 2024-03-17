<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $data = new ContactDTO();
        $data->name = 'Your name';
        $data->email = 'Your email';
        $data->message = 'Your message';
        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try{
                $email = (new Email())
                ->from($data->email)
                ->to($data->service)
                ->from($data->email)
                ->subject($data->subject)
                ->text($data->message)
                ->html('<p>'.$data->message.'</p>');
            $mailer->send($email);
            $this->addFlash('success', 'Votre message a bien été envoyé. Merci !');
            return $this->redirectToRoute('contact');

            }
            catch(\Exception $e){
                $this->addFlash('error', 'Une erreur est survenue. Veuillez réessayer.');
            }
            
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
