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
     * @ORM\Column(type="date")
     */
    private $firstDay;

    /**
     * @ORM\Column(type="date")
     */
    private $lastDay;

    /**
     * @ORM\ManyToOne(targetEntity=ApplicationField::class, inversedBy="interships")
     * @ORM\JoinColumn(nullable=true)
     */
    private $applicationField;


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

    public function getApplicationField(): ?ApplicationField
    {
        return $this->applicationField;
    }

    public function setApplicationField(?ApplicationField $applicationField): self
    {
        $this->applicationField = $applicationField;

        return $this;
    }
}
