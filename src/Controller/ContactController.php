<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ContactTypeForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactTypeForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $email = (new Email())
                ->from("formulaire-blog@saalahmed.me")
                ->to('saal.ahmed@hotmail.com')
                ->cc($data['email'])
                ->subject('Nouveau message de contact')
                ->text("Nom: {$data['name']}\nEmail: {$data['email']}\n\nMessage:\n{$data['message']}");

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a été envoyé avec succès ! Une copie vous a été envoyée.');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('contact/about.html.twig', []);
    }

}
