<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonalityRepository")
 */
class Personality
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PersonalityType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $personalityTypeOne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PersonalityType")
     */
    private $personalityTypeTwo;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPersonalityTypeOne(): ?PersonalityType
    {
        return $this->personalityTypeOne;
    }

    public function setPersonalityTypeOne(?PersonalityType $personalityTypeOne): self
    {
        $this->personalityTypeOne = $personalityTypeOne;

        return $this;
    }

    public function getPersonalityTypeTwo(): ?PersonalityType
    {
        return $this->personalityTypeTwo;
    }

    public function setPersonalityTypeTwo(?PersonalityType $personalityTypeTwo): self
    {
        $this->personalityTypeTwo = $personalityTypeTwo;

        return $this;
    }
}
