<?php

namespace App\Entity;

use App\Repository\GlobalEvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GlobalEvaluationRepository::class)
 */
class GlobalEvaluation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Evaluation::class, mappedBy="globalEvaluation")
     */
    private $evaluations;

    /**
     * @ORM\Column(type="date")
     */
    private $createdDate;

    /**
     * @ORM\OneToMany(targetEntity=GlobalEvaluationSkill::class,mappedBy="globalEvaluation")
     */
    private $skillEvaluation;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $FinalCotation;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $state;

    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Evaluation[]
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setGlobalEvaluation($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getGlobalEvaluation() === $this) {
                $evaluation->setGlobalEvaluation(null);
            }
        }

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return Collection|GlobalEvaluationSkill[]
     */
    public function getGlobalEvaluationSkill(): Collection
    {
        return $this->skillEvaluation;
    }

    public function addGlobalEvaluationSkill(GlobalEvaluationSkill $evaluation): self
    {
        if (!$this->skillEvaluation->contains($evaluation)) {
            $this->skillEvaluation[] = $evaluation;
            $evaluation->setGlobalEvaluation($this);
        }

        return $this;
    }

    public function removeGlobalEvaluationSkill(GlobalEvaluationSkill $evaluation): self
    {
        if ($this->skillEvaluation->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getGlobalEvaluation() === $this) {
                $evaluation->setGlobalEvaluation(null);
            }
        }

        return $this;
    }

    public function getFinalCotation(): ?int
    {
        return $this->FinalCotation;
    }

    public function setFinalCotation(?int $FinalCotation): self
    {
        $this->FinalCotation = $FinalCotation;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

}
