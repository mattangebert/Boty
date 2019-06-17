<?php

namespace App\DataFixtures;

class PhraseDummy {
    private $index;
    private $phrase;
    private $category;
    private $phraseTyp;
    private $personalityTyp;
    private $alternative;

    public function __construct($phrase, $category, $phraseTyp, $personalityTyp, $index = 0)
    {
        $this->phrase = $phrase;
        $this->category = $category;
        $this->phraseTyp = $phraseTyp;
        $this->personalityTyp = $personalityTyp;
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return mixed
     */
    public function getPhrase()
    {
        return $this->phrase;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getPhraseTyp()
    {
        return $this->phraseTyp;
    }

    /**
     * @return mixed
     */
    public function getPersonalityTyp()
    {
        return $this->personalityTyp;
    }

    /**
    * @return mixed
    */
    public function getAlternative()
    {
        return $this->alternative;
    }

    /**
     * @param mixed $alternative
     */
    public function setAlternative($alternative): void
    {
        $this->alternative = $alternative;
    }


}