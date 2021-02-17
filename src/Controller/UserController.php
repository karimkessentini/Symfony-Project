<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user' =>$user,
            'titre' => 'Abonné',
            'soustitre' => 'Index',
            'abonnes' => $userRepository->findAll(),
            'lien' => $this->generateUrl('user_index'),
        ]);
    }

    /**
     * @Route("/biblio/{id}", name="user_emprunt")
     */
    public function emprunt(UserRepository $userRepository,int $id=-1): Response
    {
            $user = $this->getUser();
            $livre = $this->getDoctrine()->getRepository(Livre::class)->findOneBy(['id' => $id]);
            $user->addEmprunt($livre);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('biblio/index.html.twig', [
            'user' => $user,
            'livre' => $livre,
            'titre' => 'Abonné',
            'soustitre' => 'Index',
            'abonnes' => $userRepository->findAll(),
            'emprunts' => $user->getEmprunts(),
        ]);
    }

}

