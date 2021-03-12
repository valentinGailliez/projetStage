<?php

namespace App\Entity;

use App\Repository\ApplicationFieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationFieldRepository")
 */
class ApplicationField
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @OneToMany(targetEntity="App\Entity\ApplicationField", mappedBy="parent")
     */
    private $children;

    /**
     * @ManyToOne(targetEntity="App\Entity\ApplicationField", inversedBy="children" ,cascade={"persist", "remove"})
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ManyToOne(targetEntity="App\Entity\ApplicationField", inversedBy="children")
     * @JoinColumn(name="original_id", referencedColumnName="id")
     */
    private $original;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $shortName;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $allIn;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $aliasTeam;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $webPage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @ORM\OneToMany(targetEntity=Intership::class, mappedBy="applicationField", orphanRemoval=true)
     */
    private $interships;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="applicationField")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Skill::class, mappedBy="domain", orphanRemoval=true)
     */
    private $skills;



    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->interships = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->skills = new ArrayCollection();
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param mixed $shortName
     */
    public function setShortName($shortName): void
    {
        $this->shortName = $shortName;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAllIn()
    {
        return $this->allIn;
    }

    /**
     * @param mixed $allIn
     */
    public function setAllIn($allIn): void
    {
        $this->allIn = $allIn;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAliasTeam()
    {
        return $this->aliasTeam;
    }

    /**
     * @param mixed $aliasTeam
     */
    public function setAliasTeam($aliasTeam): void
    {
        $this->aliasTeam = $aliasTeam;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getWebPage()
    {
        return $this->webPage;
    }

    /**
     * @param mixed $webPage
     */
    public function setWebPage($webPage): void
    {
        $this->webPage = $webPage;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param mixed $createdDate
     */
    public function setCreatedDate($createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }


    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * @param mixed $original
     */
    public function setOriginal($original): void
    {
        $this->original = $original;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function getInstanceOf()
    {
        $path = explode('\\', get_class($this));
        return array_pop($path);
    }

    /**
     * @return Collection|Intership[]
     */
    public function getInterships(): Collection
    {
        return $this->interships;
    }

    public function addIntership(Intership $intership): self
    {
        if (!$this->interships->contains($intership)) {
            $this->interships[] = $intership;
            $intership->setApplicationField($this);
        }

        return $this;
    }

    public function removeIntership(Intership $intership): self
    {
        if ($this->interships->removeElement($intership)) {
            // set the owning side to null (unless already changed)
            if ($intership->getApplicationField() === $this) {
                $intership->setApplicationField(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setApplicationField($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getApplicationField() === $this) {
                $user->setApplicationField(null);
            }
        }

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
            $skill->setDomain($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getDomain() === $this) {
                $skill->setDomain(null);
            }
        }

        return $this;
    }
}
