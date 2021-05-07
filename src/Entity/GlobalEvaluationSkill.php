<?php

namespace App\Entity;

use App\Repository\GlobalEvaluationSkillRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GlobalEvaluationSkillRepository::class)
 */
class GlobalEvaluationSkill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Skill::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $skill;

    /**
     * @ORM\ManyToOne(targetEntity=GlobalEvaluation::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $globalEvaluation;

    /**
     * @ORM\Column(type="integer")
     */
    private $cotation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): self
    {
        $this->skill = $skill;

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

    public function getCotation(): ?float
    {
        return $this->cotation;
    }

    public function setCotation(float $cotation): self
    {
        $this->cotation = $cotation;

        return $this;
    }
}
