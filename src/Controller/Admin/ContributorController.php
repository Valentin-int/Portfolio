<?php

namespace App\Controller\Admin;

use App\Entity\Contributor;
use App\Form\ContributorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class ContributorController extends AbstractController
{
    
    /**
     * @Route("/edit/contributeur/{id}", name="contributor_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contributor $contributor): Response
    {
        $formContributorEdit = $this->createForm(ContributorType::class, $contributor);
        $formContributorEdit->handleRequest($request);

        if ($formContributorEdit->isSubmitted() && $formContributorEdit->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_show', ['id' => $contributor->getProject()->getId()]);
        }

        return $this->renderForm('admin/project/show.html.twig', [
            'contributor' => $contributor,
            'formContributorEdit' => $formContributorEdit,
        ]);
    }

    /**
     * @Route("/supprimer/contributeur/{id}", name="contributor_delete", methods={"POST"})
     */
    public function delete(Request $request, Contributor $contributor): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contributor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contributor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_project_show', ['id' => $contributor->getProject()->getId()]);
    }
}