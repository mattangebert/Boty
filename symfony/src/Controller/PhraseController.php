<?php

namespace App\Controller;

use App\Entity\Phrase;
use App\Form\PhraseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhraseController extends AbstractController
{
    /**
     * @Route("/phrases", name="phrase_show_all")
     */
    public function showAllPhrase()
    {
        $phrases = $this->getDoctrine()
            ->getRepository(Phrase::class)
            ->findAll();

        return $this->render('phrase/viewAll.html.twig', [
            'phrases' => $phrases
        ]);
    }

    /**
     * @Route("/phrase/delete/{id}", name="phrase_delete")
     */
    public function deletePhrase($id)
    {
        $phrase = $this->getPhraseById($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($phrase);
        $entityManager->flush();

        return $this->redirectToRoute('phrase_show_all');
    }

    /**
     * @Route("/phrase/edit/{id}", name="phrase_edit")
     */
    public function editPhrase($id, Request $request)
    {

        /** @var Phrase $phrase */
        $phrase = $this->getPhraseById($id);

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

    private function handleForm(Phrase $phrase, Request $request)
    {
        $form = $this->createForm( PhraseType::class, $phrase);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $phrase = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phrase);
            $entityManager->flush();

            return $this->redirectToRoute('phrase_show_all', ['id' => $phrase->getId()]);
        }

        return $this->render('base/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getPhraseById($id)
    {
        $phrase = $this->getDoctrine()
            ->getRepository(Phrase::class)
            ->find($id);

        if (!$phrase) {
            throw $this->createNotFoundException(
                'No phrase found for id '.$id
            );
        }

        return $phrase;
    }
}
