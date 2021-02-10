<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=IntershipPlace::class)
     */
    private $intership;

    public function __construct()
    {
        $this->intership = new ArrayCollection();
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
}
