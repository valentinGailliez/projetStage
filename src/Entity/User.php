<?php

namespace App\Entity;

use App\Entity\Intership;
use App\Entity\IntershipPlace;
use App\Entity\ApplicationField;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * 
     * @ORM\Column(type="string")
     */
    private $lastname;
    /**
     * @ORM\Column(type="string")
     */
    private $firstname;
    /**
     * @ORM\Column(type="string")
     */
    private $email;
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
    */
     private $type;
    /**
     * @ORM\ManyToMany(targetEntity=IntershipPlace::class)
     */
    private $intership;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @ORM\ManyToOne(targetEntity=ApplicationField::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $applicationField;

    /**
     * @ORM\ManyToMany(targetEntity=Intership::class, mappedBy="referents")
     */
    private $interships;


    public function __construct()
    {
        $this->intership = new ArrayCollection();
        $this->interships = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function setLastName(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function setFirstName(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->email;
    }

    public function setMail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|IntershipPlace[]
     */
    public function getIntership(): Collection
    {
        return $this->intership;
    }

    public function addIntership(IntershipPlace $intership): self
    {
        if (!$this->intership->contains($intership)) {
            $this->intership[] = $intership;
        }

        return $this;
    }

    public function removeIntership(IntershipPlace $intership): self
    {
        $this->intership->removeElement($intership);

        return $this;
    }

    public function getApplicationField(): ?ApplicationField
    {
        return $this->applicationField;
    }

    public function setApplicationField(?ApplicationField $applicationField): self
    {
        $this->applicationField = $applicationField;

        return $this;
    }

    /**
     * @return Collection|Intership[]
     */
    public function getInterships(): Collection
    {
        return $this->interships;
    }

   

     /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        switch($this->getType()){
            case 'Etudiant':
                $roles[]='ROLE_STUDENT';
                break;
            case 'Référent':
                $roles[]='ROLE_REFERENT';
                break;
            case 'Enseignant':
                $roles[]='ROLE_TEACHER';
                break;
            case 'Administrateur':
                $roles[]='ROLE_ADMIN';
                break;
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
}
