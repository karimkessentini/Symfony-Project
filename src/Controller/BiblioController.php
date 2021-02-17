<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Editeur;
use App\Entity\Livre;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BiblioController extends AbstractController
{
    /**
     * @Route("/", name="biblio")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $livres = $this->getDoctrine()->getRepository(Livre::class)->findAll();
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $auteurs = $this->getDoctrine()->getRepository(Auteur::class)->findAll();
        $editeurs = $this->getDoctrine()->getRepository(Editeur::class)->findAll();
        return $this->render('biblio/index.html.twig', [
            'emprunts' => $user->getEmprunts(),
            'nbemps' => count($user->getEmprunts()),
            'auteurs' => $auteurs,
            'editeurs' => $editeurs,
            'categories' => $categories,
            'livres'=> $livres,
            'user' =>$user,
            'titre' => 'biblio acceuil',
            'soustitre' => 'sous biblio acceuil',
            'lien' => $this->generateUrl('biblio'),
        ]);
    }

    /**
     * @Route("/biblio/{id}", name="biblio_emprunt")
     */
    public function emprunt(UserRepository $userRepository,int $id=-1): Response
    {
        $user = $this->getUser();
        $livre = $this->getDoctrine()->getRepository(Livre::class)->findOneBy(['id' => $id]);
        $user->addEmprunt($livre);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('biblio', [
            'user' => $user,
            'livre' => $livre,
            'titre' => 'Abonné',
            'soustitre' => 'Index',
            'abonnes' => $userRepository->findAll(),
            //'emprunts' => $user->getEmprunts(),
        ]);
    }
    /**
     * @Route("/biblio/delete/{id}", name="biblio_delete")
     */
    public function delete(UserRepository $userRepository,int $id=-1): Response
    {
        $user = $this->getUser();
        $livre = $this->getDoctrine()->getRepository(Livre::class)->findOneBy(['id' => $id]);
        $user->removeEmprunt($livre);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('biblio', [
            'user' => $user,
            'livre' => $livre,
            'titre' => 'Abonné',
            'soustitre' => 'Index',
            'abonnes' => $userRepository->findAll(),
            //'emprunts' => $user->getEmprunts(),
        ]);
    }


}
