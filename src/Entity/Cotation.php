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
     * @ORM\Column(type="integer")
     */
    private $cotation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=SubSkill::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $subSkill;

    /**
     * @ORM\ManyToOne(targetEntity=Intership::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $intership;

    /**
     * @ORM\ManyToOne(targetEntity=Evaluation::class, inversedBy="cotation")
     */
    private $evaluation;



    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->subskill = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCotation(): ?int
    {
        return $this->cotation;
    }

    public function setCotation(int $cotation): self
    {
        $this->cotation = $cotation;

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

    public function getSubSkill(): ?SubSkill
    {
        return $this->subSkill;
    }

    public function setSubSkill(?SubSkill $subSkill): self
    {
        $this->subSkill = $subSkill;

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
}
