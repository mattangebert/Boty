<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhraseRepository")
 */
class Phrase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $phrase;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhraseTyp", inversedBy="phrases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phraseTyp;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PersonalityType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $personalityType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="phrases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhraseToAlternative", mappedBy="Phrase")
     */
    private $alternativePhrases;

    public function __construct()
    {
        $this->alternativePhrases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhrase(): ?string
    {
        return $this->phrase;
    }

    public function setPhrase(string $phrase): self
    {
        $this->phrase = $phrase;

        return $this;
    }

    public function getPhraseTyp(): ?PhraseTyp
    {
        return $this->phraseTyp;
    }

    public function setPhraseTyp(?PhraseTyp $phraseTyp): self
    {
        $this->phraseTyp = $phraseTyp;

        return $this;
    }

    public function getPersonalityType(): ?PersonalityType
    {
        return $this->personalityType;
    }

    public function setPersonalityType(?PersonalityType $personalityType): self
    {
        $this->personalityType = $personalityType;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|PhraseToAlternative[]
     */
    public function getAlternativePhrases(): Collection
    {
        return $this->alternativePhrases;
    }

    public function addAlternativePhrase(PhraseToAlternative $alternativePhrase): self
    {
        if (!$this->alternativePhrases->contains($alternativePhrase)) {
            $this->alternativePhrases[] = $alternativePhrase;
            $alternativePhrase->setPhrase($this);
        }

        return $this;
    }

    public function removeAlternativePhrase(PhraseToAlternative $alternativePhrase): self
    {
        if ($this->alternativePhrases->contains($alternativePhrase)) {
            $this->alternativePhrases->removeElement($alternativePhrase);
            // set the owning side to null (unless already changed)
            if ($alternativePhrase->getPhrase() === $this) {
                $alternativePhrase->setPhrase(null);
            }
        }

        return $this;
    }
}
