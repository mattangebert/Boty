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
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $phraseTyp;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PersonalityTyp")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $personalityTyp;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="phrases")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhraseToAlternative", mappedBy="phrase")
     */
    private $alternativePhrases;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhraseToReply", mappedBy="phrase")
     */
    private $replyPhrases;

    public function __construct()
    {
        $this->alternativePhrases = new ArrayCollection();
        $this->replyPhrases = new ArrayCollection();
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

    public function getPersonalityTyp(): ?PersonalityTyp
    {
        return $this->personalityTyp;
    }

    public function setPersonalityTyp(?PersonalityTyp $personalityTyp): self
    {
        $this->personalityTyp = $personalityTyp;

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

    /**
     * @return Collection|PhraseToReply[]
     */
    public function getReplyPhrases(): Collection
    {
        return $this->replyPhrases;
    }

    public function addReplyPhrase(PhraseToAlternative $replyPhrase): self
    {
        if (!$this->replyPhrases->contains($replyPhrase)) {
            $this->replyPhrases[] = $replyPhrase;
            $replyPhrase->setPhrase($this);
        }

        return $this;
    }

    public function removeReplyPhrase(PhraseToAlternative $replyPhrase): self
    {
        if ($this->replyPhrases->contains($replyPhrase)) {
            $this->replyPhrases->removeElement($replyPhrase);
            // set the owning side to null (unless already changed)
            if ($replyPhrase->getPhrase() === $this) {
                $replyPhrase->setPhrase(null);
            }
        }

        return $this;
    }
}
