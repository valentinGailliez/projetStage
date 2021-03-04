<?php

namespace App\Entity;

use App\Entity\Skill;
use App\Entity\Cotation;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SubSkillRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SubSkillRepository::class)
 */
class SubSkill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $number;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Skill::class, inversedBy="subSkills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $skill;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=SubSkillCotation::class, mappedBy="subSkill")
     * @ORM\Column(nullable=true)
     */
    private $cotations;



    public function __construct()
    {
        $this->cotations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): self
    {
        $this->skill = $skill;

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
     * @return Collection|Cotation[]
     */
    public function getCotation(): Collection
    {
        return $this->subSkills;
    }

    public function addCotation(Cotation $cotation): self
    {
        if (!$this->cotations->contains($cotation)) {
            $this->cotations[] = $cotation;
            $cotation->setSubSkill($this);
        }

        return $this;
    }

    public function removeCotation(Cotation $cotation): self
    {
        if ($this->cotations->removeElement($cotation)) {
            // set the owning side to null (unless already changed)
            if ($cotation->getSubSkill() === $this) {
                $cotation->setSubSkill(null);
            }
        }

        return $this;
    }
}
