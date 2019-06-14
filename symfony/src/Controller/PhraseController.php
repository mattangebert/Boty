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
        $additional = [
            'categories' => $this->getAllFromEntity('category'),
            'categoryId' => 'all',
            'phraseTyps' => $this->getAllFromEntity('phraseTyp'),
            'phraseTypId' => 'all',
            'personalityTyps' => $this->getAllFromEntity('personalityTyp'),
            'personalityTypId' => 'all'
        ];
        return $this->showAllFromEntity($additional);
    }


    /**
     * @Route("/phrases/category/{cId}/phraseTyp/{pId}/personalityTyp/{ptId}", name="phrase_show_by_filters")
     */
    public function showPhrasesByFilters($cId, $pId, $ptId)
    {
       $phrases = $this->getDoctrine()
           ->getRepository(Phrase::class)
           ->findByFilters($cId, $pId, $ptId);

        $additional = [
            'categories' => $this->getAllFromEntity('category'),
            'categoryId' => $cId,
            'phraseTyps' => $this->getAllFromEntity('phraseTyp'),
            'phraseTypId' => $pId,
            'personalityTyps' => $this->getAllFromEntity('personalityTyp'),
            'personalityTypId' => $ptId
        ];

        return $this->showCollection($phrases, $additional);
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
