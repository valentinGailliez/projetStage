<?php

namespace App\Service;

use App\Entity\Utilisateur;

class RoleUtilisateurService
{
    public $role;
    public function setRole(Utilisateur $utilisateur)
    {
        $this->role = $utilisateur->getRole();
    }

    public function getRole()
    {
        return $this->role;
    }
}
