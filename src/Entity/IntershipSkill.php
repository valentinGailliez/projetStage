<?php

namespace App\Entity;

use App\Repository\IntershipSkillRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IntershipSkillRepository::class)
 */
class IntershipSkill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Skill::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $skill;

    /**
     * @ORM\OneToOne(targetEntity=Intership::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $intership;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getIntership(): ?Intership
    {
        return $this->intership;
    }

    public function setIntership(Intership $intership): self
    {
        $this->intership = $intership;

        return $this;
    }
}
