<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\SubSkill;
use App\Entity\Intership;
use App\Entity\Evaluation;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CotationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CotationRepository::class)
 */
class Cotation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=IntershipSkill::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $intershipskill;

    /**
     * @ORM\ManyToOne(targetEntity=Evaluation::class, inversedBy="cotation")
     */
    private $evaluation;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=SubSkillCotation::class, mappedBy="skillcotation", orphanRemoval=true)
     */
    private $subskillcotation;



    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->subskillcotation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }



    public function getIntership(): ?Intership
    {
        return $this->intership;
    }

    public function setIntership(?Intership $intership): self
    {
        $this->intership = $intership;

        return $this;
    }

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }



    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return Collection|SubSkillCotation[]
     */
    public function getSubskillcotation(): Collection
    {
        return $this->subskillcotation;
    }

    public function addSubskillcotation(SubSkillCotation $subskillcotation): self
    {
        if (!$this->subskillcotation->contains($subskillcotation)) {
            $this->subskillcotation[] = $subskillcotation;
            $subskillcotation->setSkillcotation($this);
        }

        return $this;
    }

    public function removeSubskillcotation(SubSkillCotation $subskillcotation): self
    {
        if ($this->subskillcotation->removeElement($subskillcotation)) {
            // set the owning side to null (unless already changed)
            if ($subskillcotation->getSkillcotation() === $this) {
                $subskillcotation->setSkillcotation(null);
            }
        }

        return $this;
    }
}
