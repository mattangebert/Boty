<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhraseToReplyRepository")
 */
class PhraseToReply
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
    private $Phrase;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phrase")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $ReplyPhrase;

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

    public function getReplyPhrase(): ?Phrase
    {
        return $this->ReplyPhrase;
    }

    public function setReplyPhrase(?Phrase $ReplyPhrase): self
    {
        $this->ReplyPhrase = $ReplyPhrase;

        return $this;
    }
}
