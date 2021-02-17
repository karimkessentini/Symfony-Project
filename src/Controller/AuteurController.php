<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;


/**
 * @Route("/auteur", name="auteur")
 */
class AuteurController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $repAuteur = $this->getDoctrine()->getRepository(Auteur::class);
        $lesAuteurs = $repAuteur->findAll();

        return $this->render('auteur/index.html.twig', [
            'test' => false,
            'titre' => 'Auteur',
            'soustitre' => 'Index',
            'listeAuteurs' => $lesAuteurs,
            'lien' => $this->generateUrl('auteurindex'),
        ]);
    }


    /**
     * @Route("/warning", name="index_warning")
     */
    public function indexWarning(): Response
    {
        $repAuteur = $this->getDoctrine()->getRepository(Auteur::class);
        $lesAuteurs = $repAuteur->findAll();

        return $this->render('auteur/index.html.twig', [
            'test' => true,
            'titre' => 'Auteur',
            'soustitre' => 'Index',
            'listeAuteurs' => $lesAuteurs,
            'lien' => $this->generateUrl('auteurindex'),
        ]);
    }

    /**
     * @Route("/nouveau",name="nouvel_auteur")
     */
    public function nouveau(Request $request)
    {

        $auteur = new Auteur();
        $frm = $this->createForm(AuteurType::class, $auteur);
        $frm->add('Valider', SubmitType::class);

        $frm->handleRequest($request);
        if ($frm->isSubmitted() && $frm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($auteur);
            $em->flush();

            return $this->redirectToRoute('auteurnouvel_auteur');
        }

        return $this->render('auteur/nouveau.html.twig', ['frm' => $frm->createView(),
            'titre' => 'Auteur',
            'soustitre' => 'Nouveau','lien' => $this->generateUrl('auteurindex'),]);
    }

    /**
     * @Route("/afficher/{id}",name="afficher_auteur")
     */
    public function afficher(int $id = -1)
    {

        if ($id <= 0) {
            return $this->redirectToRoute('auteurindex');
        } else {
            $repAuteur = $this->getDoctrine()->getRepository(Auteur::class);
            $unAuteur = $repAuteur->findOneBy(['id' => $id]);
            return $this->render('auteur/afficher.html.twig', ['auteur' => $unAuteur, 'titre' => 'Auteur',
                'soustitre' => '','lien' => $this->generateUrl('auteurindex'),]);
        }

    }

    /**
     * @Route("/edit/{id}",name="edit_auteur")
     */
    public function edit(int $id = -1, Request $request)
    {

        if ($id <= 0) {
            return $this->redirectToRoute('auteurindex');
        } else {

            $repAuteur = $this->getDoctrine()->getRepository(Auteur::class);
            $unAuteur = $repAuteur->findOneBy(['id' => $id]);

            $frm = $this->createForm(AuteurType::class, $unAuteur);
            $frm->add('Modifier', SubmitType::class);
            $frm->handleRequest($request);
            if ($frm->isSubmitted() && $frm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute('auteurindex');
            }
            return $this->render('auteur/edit.html.twig', ['frm' => $frm->createView(), 'titre' => 'Auteur',
                'soustitre' => 'Editer',
                'lien' => $this->generateUrl('auteurindex'),]);
        }


    }

    /**
     * @Route("/supprimer/{id}",name="supprimer_auteur")
     */
    public function supprimer(int $id)
    {

        if ($id <= 0) {
            return $this->redirectToRoute('auteurindex');
        } else {
            $repAuteur = $this->getDoctrine()->getRepository(Auteur::class);
            $auteur = $repAuteur->findOneBy(['id' => $id]);
            if(count($auteur->getLivres())<=0){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($auteur);
                $entityManager->flush();
                return $this->redirectToRoute('auteurindex');
            }else{
                return $this->redirectToRoute('auteurindex_warning');
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($auteur);
            $em->flush();

            return $this->redirectToRoute('auteurindex');
        }
    }


}
