<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class ImageController extends AbstractController
{
    
    /**
     * @Route("/modifier/image/{id}", name="image_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Image $image): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_show', ['id' => $image->getProject()->getId()]);
        }

        return $this->renderForm('admin/project/show.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/supprimer/image/{id}", name="image_delete", methods={"POST"})
     */
    public function delete(Request $request, Image $image): Response
    {
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_project_show', ['id' => $image->getProject()->getId()]);
    }
}