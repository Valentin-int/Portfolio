<?php

namespace App\Controller\Admin;

use App\Entity\Techno;
use App\Form\TechnoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class TechnoController extends AbstractController
{
    
    /**
     * @Route("/modifier/techno/{id}", name="techno_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Techno $techno): Response
    {
        $form = $this->createForm(TechnoType::class, $techno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_show', ['id' => $techno->getProject()->getId()]);
        }

        return $this->renderForm('admin/project/show.html.twig', [
            'techno' => $techno,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/supprimer/techno/{id}", name="techno_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Techno $techno): Response
    {
        if ($this->isCsrfTokenValid('delete' . $techno->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($techno);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_project_show', ['id' => $techno->getProject()->getId()]);
    }
}