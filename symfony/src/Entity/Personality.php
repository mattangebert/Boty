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
     * @ORM\ManyToOne(targetEntity="App\Entity\PersonalityTyp")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $personalityTypOne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PersonalityTyp")
     */
    private $personalityTypTwo;

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

    public function getPersonalityTypOne(): ?PersonalityTyp
    {
        return $this->personalityTypOne;
    }

    public function setPersonalityTypOne(?PersonalityTyp $personalityTypOne): self
    {
        $this->personalityTypOne = $personalityTypOne;

        return $this;
    }

    public function getPersonalityTypTwo(): ?PersonalityTyp
    {
        return $this->personalityTypTwo;
    }

    public function setPersonalityTypTwo(?PersonalityTyp $personalityTypTwo): self
    {
        $this->personalityTypTwo = $personalityTypTwo;

        return $this;
    }
}
