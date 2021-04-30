<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvaluationRepository::class)
 */
class Evaluation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Cotation::class, mappedBy="evaluation")
     */
    private $cotation;

    /**
     * @ORM\ManyToOne(targetEntity=GlobalEvaluation::class, inversedBy="evaluations")
     */
    private $globalEvaluation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $subject;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $typeEvaluation;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="evaluations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->cotation = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Cotation[]
     */
    public function getCotation(): Collection
    {
        return $this->cotation;
    }

    public function addCotation(Cotation $cotation): self
    {
        if (!$this->cotation->contains($cotation)) {
            $this->cotation[] = $cotation;
            $cotation->setEvaluation($this);
        }

        return $this;
    }

    public function removeCotation(Cotation $cotation): self
    {
        if ($this->cotation->removeElement($cotation)) {
            // set the owning side to null (unless already changed)
            if ($cotation->getEvaluation() === $this) {
                $cotation->setEvaluation(null);
            }
        }

        return $this;
    }

    public function getGlobalEvaluation(): ?GlobalEvaluation
    {
        return $this->globalEvaluation;
    }

    public function setGlobalEvaluation(?GlobalEvaluation $globalEvaluation): self
    {
        $this->globalEvaluation = $globalEvaluation;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTypeEvaluation(): ?string
    {
        return $this->typeEvaluation;
    }

    public function setTypeEvaluation(string $typeEvaluation): self
    {
        $this->typeEvaluation = $typeEvaluation;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
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
}
