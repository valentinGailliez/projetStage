<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\IntershipRepository;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=IntershipRepository::class)
 * @ORM\Table(name="intership")
 */
class Intership
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * 
     * @ORM\Column(type="string", nullable = true)
     */
    private $ectsCode;
    /**
     * @ORM\Column(type="string")
     */
    private $ansco;


    /**
     * @ORM\Column(type="date")
     */
    private $firstDay;

    /**
     * @ORM\Column(type="date")
     */
    private $lastDay;

    /**
     * @ORM\ManyToOne(targetEntity=ApplicationField::class, inversedBy="interships")
     */
    private $applicationField;

    /**
     * @ORM\ManyToMany(targetEntity=Skill::class)
     */
    private $skills;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEctsCode(): ?string
    {
        return $this->ectsCode;
    }

    public function setEctsCode(string $ectsCode): self
    {
        $this->ectsCode = $ectsCode;

        return $this;
    }

    public function getAnsco(): ?string
    {
        return $this->ansco;
    }

    public function setAnsco(string $ansco): self
    {
        $this->ansco = $ansco;

        return $this;
    }




    public function getFirstDay(): ?DateTime
    {
        return $this->firstDay;
    }

    public function setFirstDay(DateTime $firstDay): self
    {
        $this->firstDay = $firstDay;

        return $this;
    }

    public function getLastDay(): ?DateTime
    {
        return $this->lastDay;
    }

    public function setLastDay(DateTime $lastDay): self
    {
        $this->lastDay = $lastDay;

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
     * @return Collection|Skill[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skills->removeElement($skill);

        return $this;
    }
}
