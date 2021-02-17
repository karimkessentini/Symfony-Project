<?php

namespace App\Controller;

use App\Entity\Editeur;
use App\Form\EditeurType;
use App\Repository\EditeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/editeur")
 */
class EditeurController extends AbstractController
{
    /**
     * @Route("/", name="editeur_index", methods={"GET"})
     */
    public function index(EditeurRepository $editeurRepository): Response
    {
        return $this->render('editeur/index.html.twig', [
            'test' => false,
            'titre'=> 'Editeur',
            'soustitre' => 'Index',
            'editeurs' => $editeurRepository->findAll(),
            'lien' => $this->generateUrl('editeur_index'),
        ]);
    }

    /**
     * @Route("/warning", name="editeur_index_warning", methods={"GET"})
     */
    public function indexWarning(EditeurRepository $editeurRepository): Response
    {
        return $this->render('editeur/index.html.twig', [
            'test' => true,
            'titre'=> 'Editeur',
            'soustitre' => 'Index',
            'editeurs' => $editeurRepository->findAll(),
            'lien' => $this->generateUrl('editeur_index'),
        ]);
    }

    /**
     * @Route("/new", name="editeur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $editeur = new Editeur();
        $form = $this->createForm(EditeurType::class, $editeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($editeur);
            $entityManager->flush();

            return $this->redirectToRoute('editeur_index');
        }

        return $this->render('editeur/new.html.twig', [
            'titre'=> 'Editeur',
            'soustitre' => 'Nouveau',
            'editeur' => $editeur,
            'form' => $form->createView(),
            'lien' => $this->generateUrl('editeur_index'),
        ]);
    }

    /**
     * @Route("/{id}", name="editeur_show", methods={"GET"})
     */
    public function show(Editeur $editeur): Response
    {
        return $this->render('editeur/show.html.twig', [
            'titre'=> 'Editeur',
            'soustitre' => '',
            'editeur' => $editeur,
            'lien' => $this->generateUrl('editeur_index'),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="editeur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Editeur $editeur): Response
    {
        $form = $this->createForm(EditeurType::class, $editeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('editeur_index');
        }

        return $this->render('editeur/edit.html.twig', [
            'titre'=> 'Editeur',
            'soustitre' => 'Editer',
            'editeur' => $editeur,
            'form' => $form->createView(),
            'lien' => $this->generateUrl('editeur_index'),
        ]);
    }

    /**
     * @Route("/{id}", name="editeur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Editeur $editeur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$editeur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($editeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('editeur_index');
    }


    /**
     * @Route("/supprimer/{id}", name="editeur_delete_2")
     */
    public function supprimer(Request $request,int $id = -1): Response
    {
        if ($id > 0) {
            $editeur = $this->getDoctrine()->getRepository(Editeur::class)->findOneBy(['id' => $id]);
            if(count($editeur->getLivres())<=0){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($editeur);
                $entityManager->flush();
                return $this->redirectToRoute('editeur_index');
            }else{
                return $this->redirectToRoute('editeur_index_warning');
            }
        }else
        {return $this->redirectToRoute('editeur_index');}
    }
}
