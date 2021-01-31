<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\UtilisateurConnexion;
use App\Form\ConnexionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     */
    public function index(Request $request): Response
    {
        $utilisateurConnecte = new UtilisateurConnexion();

        $repository = $this->getDoctrine()->getRepository(Utilisateur::class);
        $form = $this->createForm(ConnexionFormType::class, $utilisateurConnecte);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $repository->findOneBy([
                'emailUtilisateur' => $utilisateurConnecte->getEmail(),
                'motdepasseUtilisateur' => $utilisateurConnecte->getMotDePasse(),
            ]);
            if ($utilisateur != null) {

                return $this->redirectToRoute(
                    "home",
                    array(
                        'Utilisateur' => $utilisateur->getNom(),
                        'role' => $utilisateur->getRole()
                    )
                );
            }
        }
        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
            'form' => $form->createView(),
        ]);
    }
}
