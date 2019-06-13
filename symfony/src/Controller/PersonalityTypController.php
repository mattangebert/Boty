<?php

namespace App\Controller;

use App\Entity\PersonalityTyp;
use App\Form\PersonalityTypType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PersonalityTypController extends AbstractController
{
    /**
     * @Route("/personalityTyps", name="personalityTyp_show_all")
     */
    public function showAllPersonalityTyp()
    {
        $personalityTyps = $this->getDoctrine()
            ->getRepository(PersonalityTyp::class)
            ->findAll();

        return $this->render('personality_typ/viewAll.html.twig', [
            'personalityTyps' => $personalityTyps
        ]);
    }

    /**
     * @Route("/personalityTyp/delete/{id}", name="personalityTyp_delete")
     */
    public function deletePersonalityTyp($id)
    {
        $personalityTyp = $this->getPersonalityTypById($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($personalityTyp);
        $entityManager->flush();

        return $this->redirectToRoute('personalityTyp_show_all');
    }

    /**
     * @Route("/personalityTyp/edit/{id}", name="personalityTyp_edit")
     */
    public function editPersonalityTyp($id, Request $request)
    {

        /** @var PersonalityTyp $personalityTyp */
        $personalityTyp = $this->getPersonalityTypById($id);

        return $this->handleForm($personalityTyp, $request);
    }

    /**
     * @Route("/personalityTyps/new/", name="personalityTyp_create")
     */
    public function newPersonalityTyp(Request $request)
    {
        $personalityTyp = new PersonalityTyp();

        return $this->handleForm($personalityTyp, $request);
    }

    private function handleForm(PersonalityTyp $personalityTyp, Request $request)
    {
        $form = $this->createForm( PersonalityTypType::class, $personalityTyp);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalityTyp = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($personalityTyp);
            $entityManager->flush();

            return $this->redirectToRoute('personalityTyp_show_all', ['id' => $personalityTyp->getId()]);
        }

        return $this->render('default/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getPersonalityTypById($id)
    {
        $personalityTyp = $this->getDoctrine()
            ->getRepository(PersonalityTyp::class)
            ->find($id);

        if (!$personalityTyp) {
            throw $this->createNotFoundException(
                'No personalityTyp found for id '.$id
            );
        }

        return $personalityTyp;
    }
}
