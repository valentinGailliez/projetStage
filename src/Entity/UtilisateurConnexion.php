<?php

namespace App\Entity;



class UtilisateurConnexion
{

    private $email;
    private $mot_de_passe;

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setMotDePasse($mot_de_passe)
    {
        $this->mot_de_passe = $mot_de_passe;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function getMotDePasse()
    {
        return $this->mot_de_passe;
    }
}
