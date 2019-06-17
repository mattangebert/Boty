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
    private $phrase;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phrase", inversedBy="replyPhrases")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $replyPhrase;

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

    public function getReplyPhrase(): ?Phrase
    {
        return $this->replyPhrase;
    }

    public function setReplyPhrase(?Phrase $replyPhrase): self
    {
        $this->replyPhrase = $replyPhrase;

        return $this;
    }
}
