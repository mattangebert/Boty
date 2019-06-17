<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhraseToAlternativeRepository")
 */
class PhraseToAlternative
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phrase")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $phrase;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phrase", inversedBy="alternativePhrases")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $alternativePhrase;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhrase(): ?Phrase
    {
        return $this->phrase;
    }

    public function setPhrase(?Phrase $phrase): self
    {
        $this->phrase = $phrase;

        return $this;
    }

    public function getAlternativePhrase(): ?Phrase
    {
        return $this->alternativePhrase;
    }

    public function setAlternativePhrase(?Phrase $alternativePhrase): self
    {
        $this->alternativePhrase = $alternativePhrase;

        return $this;
    }
}
