<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Project;
use App\Entity\Techno;
use App\Form\MessageType;
use App\Repository\ContributorRepository;
use App\Repository\ImageRepository;
use App\Repository\ProjectRepository;
use App\Repository\TechnoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="index_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function index(Request $request, ProjectRepository $projectRepository, ImageRepository $imageRepository): Response
    {
        $message = new Message();
        $formContact = $this->createForm(MessageType::class, $message);
        $formContact->handleRequest($request);
        if ($formContact->isSubmitted() && $formContact->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirect($request->getUri());
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
