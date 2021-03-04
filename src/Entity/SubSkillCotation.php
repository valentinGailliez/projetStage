<?php

namespace App\Entity;

use App\Entity\SubSkill;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SubSkillCotationRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=SubSkillCotationRepository::class)
 */
class SubSkillCotation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @ORM\ManyToOne(targetEntity=SubSkill::class, inversedBy="cotations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subSkill;


    /**
     * @ORM\Column(type="integer")
     */
    private $cotation;

    /**
     * @ORM\ManyToOne(targetEntity=Cotation::class, inversedBy="subskillcotation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $skillcotation;

    public function __construct()
    {
        $this->subskill = new ArrayCollection();
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

    public function getSubSkill(): ?SubSkill
    {
        return $this->subSkill;
    }

    public function setSubSkill(?SubSkill $subSkill): self
    {
        $this->subSkill = $subSkill;

        return $this;
    }

    public function getSkillcotation(): ?Cotation
    {
        return $this->skillcotation;
    }

    public function setSkillcotation(?Cotation $skillcotation): self
    {
        $this->skillcotation = $skillcotation;

        return $this;
    }
}
