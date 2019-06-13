<?php

namespace App\Controller;

use App\Entity\PhraseTyp;
use App\Form\PhraseTypType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhraseTypController extends AbstractController
{
    /**
     * @Route("/phraseTyps", name="phraseTyp_show_all")
     */
    public function showAllPhraseTyp()
    {
        $phraseTyps = $this->getDoctrine()
            ->getRepository(PhraseTyp::class)
            ->findAll();

        return $this->render('phrase_type/viewAll.html.twig', [
            'phraseTyps' => $phraseTyps
        ]);
    }

    /**
     * @Route("/phraseTyp/delete/{id}", name="phraseTyp_delete")
     */
    public function deletePhraseTyp($id)
    {
        $phraseTyp = $this->getPhraseTypById($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($phraseTyp);
        $entityManager->flush();

        return $this->redirectToRoute('phraseTyp_show_all');
    }

    /**
     * @Route("/phraseTyp/edit/{id}", name="phraseTyp_edit")
     */
    public function editPhraseTyp($id, Request $request)
    {

        /** @var PhraseTyp $phraseTyp */
        $phraseTyp = $this->getPhraseTypById($id);

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

    private function handleForm(PhraseTyp $phraseTyp, Request $request)
    {
        $form = $this->createForm( PhraseTypType::class, $phraseTyp);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $phraseTyp = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phraseTyp);
            $entityManager->flush();

            return $this->redirectToRoute('phraseTyp_show_all', ['id' => $phraseTyp->getId()]);
        }

        return $this->render('base/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getPhraseTypById($id)
    {
        $phraseTyp = $this->getDoctrine()
            ->getRepository(PhraseTyp::class)
            ->find($id);

        if (!$phraseTyp) {
            throw $this->createNotFoundException(
                'No phraseTyp found for id '.$id
            );
        }

        return $phraseTyp;
    }
}
