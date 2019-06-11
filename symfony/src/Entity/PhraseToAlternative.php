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
     * @ORM\ManyToOne(targetEntity="App\Entity\Phrase", inversedBy="alternativePhrases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Phrase;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phrase")
     * @ORM\JoinColumn(nullable=false)
     */
    private $AlternativePhrase;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhrase(): ?Phrase
    {
        return $this->Phrase;
    }

    public function setPhrase(?Phrase $Phrase): self
    {
        $this->Phrase = $Phrase;

        return $this;
    }

    public function getAlternativePhrase(): ?Phrase
    {
        return $this->AlternativePhrase;
    }

    public function setAlternativePhrase(?Phrase $AlternativePhrase): self
    {
        $this->AlternativePhrase = $AlternativePhrase;

        return $this;
    }
}
