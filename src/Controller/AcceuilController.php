<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Editeur;
use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    /**
     * @Route("/acceuil", name="acceuil")
     */
    public function index(): Response
    {
        $nbAuteurs = count($this->getDoctrine()->getRepository(Auteur::class)->findAll());
        $nbLivres = count($this->getDoctrine()->getRepository(Livre::class)->findAll());
        $nbEditeurs = count($this->getDoctrine()->getRepository(Editeur::class)->findAll());
        $nbCategories = count($this->getDoctrine()->getRepository(Categorie::class)->findAll());

        return $this->render('acceuil/index.html.twig', [
            'titre' =>'Acceuil',
            'soustitre' => '',
            'lien' => $this->generateUrl('acceuil'),
            'nbAuteurs' => $nbAuteurs,
            'nbLivres' => $nbLivres,
            'nbEditeurs' => $nbEditeurs,
            'nbCategories' => $nbCategories,
        ]);
    }
}
