<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Project;
use App\Form\MessageType;
use App\Repository\ContributorRepository;
use App\Repository\ImageRepository;
use App\Repository\ProjectRepository;
use App\Repository\TechnoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

/**
 * @Route("/", name="index_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function index(Request $request, MailerInterface $mailer , ProjectRepository $projectRepository, ImageRepository $imageRepository): Response
    {
        $message = new Message();
        $formContact = $this->createForm(MessageType::class, $message);
        $formContact->handleRequest($request);
        if ($formContact->isSubmitted() && $formContact->isValid()) {
            $mailerFrom = $this->getParameter('mailer_from');
            $mailerTo = $this->getParameter('mailer_to');
            $email = (new Email())
            ->from($mailerFrom)
            ->to($mailerTo)
            ->subject('Vous avez un nouveau message de la part d\'un visiteur du site.')
            ->html($this->renderView('message/new_message_email.html.twig', ['message' => $message]));
            $mailer->send($email);

            $this->addFlash('success', 'Merci pour votre message, je vous répondrais le plus rapidement possible.');

            return $this->redirectToRoute('index_home');
        }
        return $this->render('index/index.html.twig', [
            'formContact' => $formContact->createView(),
            'projects' => $projectRepository->findAll(),
            'image' => $imageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/projet/{id}", name="project_show", methods={"GET","POST"})
     */
    public function show(Project $project, Request $request, TechnoRepository $technoRepository, ContributorRepository $contributorRepository): Response
    {
        return $this->render('index/show_project.html.twig', [
            'project' => $project,
            'contributors' => $contributorRepository->findAll(),
            'technos' => $technoRepository->findAll(),
        ]);
    }
}
