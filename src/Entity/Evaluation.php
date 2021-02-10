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

    public function __construct()
    {
        $this->cotation = new ArrayCollection();
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
}
