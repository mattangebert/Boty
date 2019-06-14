<?php

namespace App\Controller;

use App\Entity\PhraseTyp;
use App\Form\PhraseTypType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhraseTypController extends BaseController
{
    protected $entityName = 'phraseTyp';

    /**
     * @Route("/phraseTyps", name="phraseTyp_show_all")
     */
    public function showAllPhraseTyp()
    {
       return $this->showAllFromEntity();
    }

    /**
     * @Route("/phraseTyp/delete/{id}", name="phraseTyp_delete")
     */
    public function deletePhraseTyp($id)
    {
        return $this->deleteEntity($id);
    }

    /**
     * @Route("/phraseTyp/edit/{id}", name="phraseTyp_edit")
     */
    public function editPhraseTyp($id, Request $request)
    {
        /** @var PhraseTyp $phraseTyp */
        $phraseTyp = $this->getEntityById($id);

        return $this->handleForm($phraseTyp, $request);
    }

    /**
     * @Route("/phraseTyps/new/", name="phraseTyp_create")
     */
    public function newPhraseTyp(Request $request)
    {
        $phraseTyp = new PhraseTyp();

        return $this->handleForm($phraseTyp, $request);
    }
}
