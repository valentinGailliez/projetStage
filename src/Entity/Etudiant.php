<?php

namespace App\Entity;



class Etudiant
{



    private $nom;

    private $prenom;

    private $telephone;

    private $matricule;

    private $mot_de_passe;





    public function getNom()
    {
        return $this->nom;
    }
    public function getPrenom()
    {
        return $this->prenom;
    }
    public function getMatricule()
    {
        return $this->matricule;
    }

    public function getMotdepasse()
    {
        return $this->mot_de_passe;
    }
    public function getTelephone()
    {
        return $this->telephone;
    }


    public function setNom($nom)
    {
        $this->nom = $nom;
    }
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }
    public function setMotDePasse($motdepasse)
    {
        $this->mot_de_passe = $motdepasse;
    }
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }
}
