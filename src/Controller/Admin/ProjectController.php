<?php

namespace App\Controller\Admin;

use App\Entity\Contributor;
use App\Entity\Image;
use App\Entity\Project;
use App\Entity\Techno;
use App\Form\ContributorType;
use App\Form\ImageType;
use App\Form\ProjectType;
use App\Form\TechnoType;
use App\Repository\ContributorRepository;
use App\Repository\ImageRepository;
use App\Repository\ProjectRepository;
use App\Repository\TechnoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/mes-projets", name="project_index", methods={"GET"})
     */
    public function index(ProjectRepository $projectRepository, ImageRepository $imageRepository): Response
    {
        return $this->render('admin/project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
            'image' => $imageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="project_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('admin_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="project_show", methods={"GET","POST"})
     */
    public function show(
        Project $project,
        Request $request,
        TechnoRepository $technoRepository,
        ContributorRepository $contributorRepository
        ): Response
    {
        $image = new Image();
        $image->setProject($project);
        $formImage = $this->createForm(ImageType::class, $image);
        $formImage->handleRequest($request);

        $contributor = new Contributor();
        $contributor->setProject($project);
        $formContributor = $this->createForm(ContributorType::class, $contributor);
        $formContributor->handleRequest($request);

        $techno = new Techno();
        $techno->setProject($project);
        $formTechno = $this->createForm(TechnoType::class, $techno);
        $formTechno->handleRequest($request);

        if ($formImage->isSubmitted() && $formImage->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($image);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        if ($formContributor->isSubmitted() && $formContributor->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contributor);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        if ($formTechno->isSubmitted() && $formTechno->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($techno);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }
        return $this->render('admin/project/show.html.twig', [
            'project' => $project,
            'formImage' => $formImage->createView(),
            'formContributor' => $formContributor->createView(),
            'formTechno' => $formTechno->createView(),
            'contributors' => $contributorRepository->findAll(),
            'technos' => $technoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_index');
        }

        return $this->renderForm('admin/project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="project_delete", methods={"POST"})
     */
    public function delete(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_project_index');
    }
}
