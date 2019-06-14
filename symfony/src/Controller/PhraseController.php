<?php

namespace App\Controller;

use App\Entity\Phrase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhraseController extends BaseController
{
    protected $entityName = 'phrase';

    /**
     * @Route("/phrases", name="phrase_show_all")
     */
    public function showAllPhrase()
    {
        return $this->showAllFromEntity();
    }

    /**
     * @Route("/phrase/delete/{id}", name="phrase_delete")
     */
    public function deletePhrase($id)
    {
        return $this->deleteEntity($id);
    }

    /**
     * @Route("/phrase/edit/{id}", name="phrase_edit")
     */
    public function editPhrase($id, Request $request)
    {
        /** @var Phrase $phrase */
        $phrase = $this->getEntityById($id);

        return $this->handleForm($phrase, $request);
    }

    /**
     * @Route("/phrases/new/", name="phrase_create")
     */
    public function newPhrase(Request $request)
    {
        $phrase = new Phrase();

        return $this->handleForm($phrase, $request);
    }
}
