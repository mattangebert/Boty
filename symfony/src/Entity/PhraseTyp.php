<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhraseTypRepository")
 */
class PhraseTyp
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
     * @ORM\OneToMany(targetEntity="App\Entity\Phrase", mappedBy="phraseTyp")
     */
    private $phrases;

    public function __construct()
    {
        $this->phrases = new ArrayCollection();
    }

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

    /**
     * @return Collection|Phrase[]
     */
    public function getPhrases(): Collection
    {
        return $this->phrases;
    }

    public function addPhrase(Phrase $phrase): self
    {
        if (!$this->phrases->contains($phrase)) {
            $this->phrases[] = $phrase;
            $phrase->setPhraseTyp($this);
        }

        return $this;
    }

    public function removePhrase(Phrase $phrase): self
    {
        if ($this->phrases->contains($phrase)) {
            $this->phrases->removeElement($phrase);
            // set the owning side to null (unless already changed)
            if ($phrase->getPhraseTyp() === $this) {
                $phrase->setPhraseTyp(null);
            }
        }

        return $this;
    }
}
