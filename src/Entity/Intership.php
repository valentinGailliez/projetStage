<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\IntershipRepository;

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
     * @ORM\Column(type="string")
     */
    private $ectsCode;
    /**
     * @ORM\Column(type="string")
     */
    private $ansco;
    /**
     * @ORM\Column(type="string")
     */
    private $bloc;
    /**
     * @ORM\Column(type="string")
     */
    private $section;

    /**
     * @ORM\Column(type="string")
     */
    private $department;

    /**
     * @ORM\ManyToMany(targetEntity=Skill::class)
     */
    private $skill;

    /**
     * @ORM\Column(type="date")
     */
    private $firstDay;

    /**
     * @ORM\Column(type="date")
     */
    private $lastDay;

    public function __construct()
    {
        $this->skill = new ArrayCollection();
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

    public function getBloc(): ?string
    {
        return $this->bloc;
    }

    public function setBloc(string $bloc): self
    {
        $this->bloc = $bloc;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(string $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection|Skill[]
     */
    public function getSkill(): Collection
    {
        return $this->skill;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skill->contains($skill)) {
            $this->skill[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skill->removeElement($skill)) {
            // set the owning side to null (unless already changed)

        }

        return $this;
    }

    public function getFirstDay(): ?\DateTimeInterface
    {
        return $this->firstDay;
    }

    public function setFirstDay(\DateTimeInterface $firstDay): self
    {
        $this->firstDay = $firstDay;

        return $this;
    }

    public function getLastDay(): ?\DateTimeInterface
    {
        return $this->lastDay;
    }

    public function setLastDay(\DateTimeInterface $lastDay): self
    {
        $this->lastDay = $lastDay;

        return $this;
    }
}
