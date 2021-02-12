<?php

namespace App\Entity;

use App\Entity\SubSkill;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SkillRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SkillRepository::class)
 */
class Skill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $skillNumber;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $section;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=SubSkill::class, mappedBy="skill" , cascade={"persist", "remove"})
     */
    private $subSkills;

    public function __construct()
    {
        $this->subSkills = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkillNumber(): ?int
    {
        return $this->skillNumber;
    }

    public function setSkillNumber(int $skillNumber): self
    {
        $this->skillNumber = $skillNumber;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection|SubSkill[]
     */
    public function getSubSkills(): Collection
    {
        return $this->subSkills;
    }

    public function addSubSkill(SubSkill $subSkill): self
    {
        if (!$this->subSkills->contains($subSkill)) {
            $this->subSkills[] = $subSkill;
            $subSkill->setSkill($this);
        }

        return $this;
    }

    public function removeSubSkill(SubSkill $subSkill): self
    {
        if ($this->subSkills->removeElement($subSkill)) {
            // set the owning side to null (unless already changed)
            if ($subSkill->getSkill() === $this) {
                $subSkill->setSkill(null);
            }
        }

        return $this;
    }
}
