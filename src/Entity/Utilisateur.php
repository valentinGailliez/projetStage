<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name = "utilisateur")
 */
class Utilisateur
{


    public static $ListeRole = array(
        0 => "ADMINISTRATEUR", 1 => "ETUDIANT", 2 => "SECRETAIRE",
        3 => "MAITRE_DE_STAGE", 4 => "DIRECTION", 5 => "REFERENT",
        6 => "ENSEIGNANT_VISITE"
    );

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(type = "integer")
     */
    private $idUtilisateur;


    /**
     * @ORM\Column(type = "string")
     */
    private $nomUtilisateur;
    /**
     * @ORM\Column(type = "string")
     */
    private $prenomUtilisateur;
    /**
     * @ORM\Column(type = "string")
     */
    private $emailUtilisateur;
    /**
     * @ORM\Column(type = "string")
     */
    private $telephoneUtilisateur;

    /**
     * @ORM\Column(type = "string")
     */
    private $roleUtilisateur;

    /**
     * @ORM\Column(type = "string", nullable=true)
     */
    private $chargeMDSUtilisateur;

    /**
     * @ORM\Column(type = "string", nullable=true)
     */
    private $matriculeUtilisateur;
    /**
     * @ORM\Column(type = "string", nullable=true)
     */
    private $motdepasseUtilisateur;



    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $telephone,
        string $role,
        $chargeMds,
        $matricule,
        $motdepasse
    ) {
        $this->nomUtilisateur = $nom;
        $this->prenomUtilisateur = $prenom;
        $this->emailUtilisateur = $email;
        $this->telephoneUtilisateur = $telephone;
        $this->roleUtilisateur = $role;
        $this->chargeMDSUtilisateur = $chargeMds;
        $this->matriculeUtilisateur = $matricule;
        $this->motdepasseUtilisateur = $motdepasse;
    }

    public function getNom()
    {
        return $this->nomUtilisateur;
    }
    public function getPrenom()
    {
        return $this->prenomUtilisateur;
    }
    public function getMatricule()
    {
        return $this->matriculeUtilisateur;
    }
    public function getRole()
    {
        return $this->roleUtilisateur;
    }
    public function getEmail()
    {
        return $this->emailUtilisateur;
    }
    public function getMotdepasse()
    {
        return $this->motdepasseUtilisateur;
    }
    public function getTelephone()
    {
        return $this->telephoneUtilisateur;
    }
    public function getChargeMds()
    {
        return $this->chargeMDSUtilisateur;
    }

    public function getId()
    {
        return $this->idUtilisateur;
    }
    public function setId($id)
    {
        $this->idUtilisateur = $id;
    }
    public function setNom($id)
    {
        $this->nomUtilisateur = $id;
    }
    public function setPrenom($id)
    {
        $this->prenomUtilisateur = $id;
    }
    public function setMotDePasse($id)
    {
        $this->motdepasseUtilisateur = $id;
    }
    public function setMatricule($id)
    {
        $this->matriculeUtilisateur = $id;
    }
    public function setCharge($id)
    {
        $this->chargeMDSUtilisateur = $id;
    }
    public function setTelephone($id)
    {
        $this->telephoneUtilisateur = $id;
    }
    public function setEmail($id)
    {
        $this->emailUtilisateur = $id;
    }
}
