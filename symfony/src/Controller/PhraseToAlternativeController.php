<?php

namespace App\Controller;

use App\Entity\Phrase;
use App\Entity\PhraseToAlternative;
use App\Form\PhraseToAlternativeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhraseToAlternativeController extends BaseController
{
    protected $entityName = 'phraseToAlternative';

    /**
     * @Route("phrase/all/alternatives", name="phraseToAlternative_show_all")
     */
    public function showAllAlternative()
    {
        $phrases = $this->getAllFromEntity('phrase');

        $additional = [
            'phrases' => $phrases,
            'currentPhraseId' => 'all'
        ];

        return $this->showAllFromEntity($additional);
    }

    /**
     * @Route("phrase/{id}/alternatives", name="phrase_show_alternatives")
     */
    public function showAlternativesByPhrase($id)
    {

        /** @var Phrase $phrase */
        $phrase = $this->getEntityById($id, 'phrase');
        $alternatives = array();
        $phrases = $this->getAllFromEntity('phrase');

        $additional = [
            'phrases' => $phrases,
            'currentPhraseId' => $phrase->getId()
        ];

        if (!$phrase->getAlternativePhrases()->isEmpty()) {
            $alternatives = $phrase->getAlternativePhrases()->getValues();
        }

        return $this->showCollection($alternatives, $additional);
    }


    /**
     * @Route("/phraseToAlternative/delete/{id}", name="phraseToAlternative_delete")
     */
    public function deleteAlternative($id)
    {
        return $this->deleteEntity($id);
    }

    /**
     * @Route("/phrase/{id}/addAlternative", name="phrase_add_alternative")
     */
    public function addAlternative($id, Request $request)
    {
        $alternative =  new PhraseToAlternative();

        /** @var Phrase $phrase */
        $phrase = $this->getEntityById($id, 'phrase');

        $alternative ->setPhrase($phrase);

        $form = $this->createForm( PhraseToAlternativeType::class, $alternative);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $relation = $this->getDoctrine()->getRepository(PhraseToAlternative::class)
                ->findOneByIds($id, $form->getData()->getAlternativePhrase()->getId());

            if (null !== $relation) {
                return null;
            }

            // get data from Form
            $alternative = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($alternative);

            // reverse relation
            $this->addNewAlternative($alternative->getAlternativePhrase(), $phrase);

            // get existing alternatives from new alternative
            $supplements = $alternative->getAlternativePhrase()->getAlternativePhrases();

            if (!$supplements->isEmpty()) {
                foreach ($supplements->getValues() as $supplement) {
                    /** @var PhraseToAlternative $supplement */
                    // add supplement of alternative as alternatives too
                    $this->addNewAlternative($phrase, $supplement->getAlternativePhrase());
                    $this->addNewAlternative($supplement->getAlternativePhrase(), $phrase);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute($this->entityName.'_show_all', ['pId' => $phrase->getId()]);
        }

        return $this->render('default/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    private function addNewAlternative($phrase, $alternativePhrase)
    {
        $pta = $this->getDoctrine()->getRepository(PhraseToAlternative::class)
            ->findOneByIds($phrase->getId(), $alternativePhrase->getId());

        if (null == $pta) {
            $pta = new PhraseToAlternative();
            $pta->setPhrase($phrase);
            $pta->setAlternativePhrase($alternativePhrase);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pta);
        }
    }
}
