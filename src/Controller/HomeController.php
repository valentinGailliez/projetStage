<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function index(Request $request)
    {
        $nomUtilisateur = $request->get('Utilisateur');

        $role = $request->get('role');


        $repository = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilisateurtrouve = null;
        $utilisateur = $repository->findOneBy([
            'roleUtilisateur' => $role,
            'nomUtilisateur' => $nomUtilisateur,
        ]);
        if ($utilisateur != null) {
            $utilisateurtrouve = new Utilisateur(
                $utilisateur->getNom(),
                $utilisateur->getPrenom(),
                $utilisateur->getEmail(),
                $utilisateur->getTelephone(),
                $utilisateur->getRole(),
                $utilisateur->getChargeMds(),
                $utilisateur->getMatricule(),
                $utilisateur->getMotDePasse()
            );
        }
        $utilisateurtrouve->setId($utilisateur->getId());
        if ($utilisateurtrouve->getRole() == "ADMINISTRATEUR") {
            return $this->Administrateur($utilisateur);
        }
    }

    private function Administrateur($utilisateur)
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'utilisateur' => $utilisateur,

        ]);
    }
}
