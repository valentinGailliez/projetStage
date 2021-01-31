<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class UtilisateurRepository extends ServiceEntityRepository
{
    public $repository;
    public function __construct()
    {
        $this->repository = $this->getDoctrine()->getEntityManager()->getRepository(Utilisateur::class);
    }

    public function findUtilisateurByURL($role, $nomUtilisateur)
    {
        return $this->repository->findBy([
            'roleUtilisateur' => $role,
            'nomUtilisateur' => $nomUtilisateur,
        ]);
    }

    public function findUtilisateurByConnexion($utilisateurConnecte)
    {
        return $this->repository->findBy([
            'emailUtilisateur' => $utilisateurConnecte->getEmail(),
            'motdepasseUtilisateur' => $utilisateurConnecte->getMotDePasse(),
        ]);
    }
}
