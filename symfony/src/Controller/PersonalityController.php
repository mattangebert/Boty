<?php

namespace App\Controller;

use App\Entity\Personality;
use App\Form\PersonalityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PersonalityController extends AbstractController
{
    /**
     * @Route("/personalities", name="personality_show_all")
     */
    public function showAllPersonality()
    {
        $personalities = $this->getDoctrine()
            ->getRepository(Personality::class)
            ->findAll();

        return $this->render('personality/viewAll.html.twig', [
            'personalities' => $personalities
        ]);
    }

    /**
     * @Route("/personality/delete/{id}", name="personality_delete")
     */
    public function deletePersonality($id)
    {
        $personality = $this->getPersonalityById($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($personality);
        $entityManager->flush();

        return $this->redirectToRoute('personality_show_all');
    }

    /**
     * @Route("/personality/edit/{id}", name="personality_edit")
     */
    public function editPersonality($id, Request $request)
    {
        /** @var Personality $personality */
        $personality = $this->getPersonalityById($id);

        return $this->handleForm($personality, $request);
    }

    /**
     * @Route("/personalities/new/", name="personality_create")
     */
    public function newPersonality(Request $request)
    {
        $personality = new Personality();

        return $this->handleForm($personality, $request);
    }

    private function handleForm(Personality $personality, Request $request)
    {
        $form = $this->createForm( PersonalityType::class, $personality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personality = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($personality);
            $entityManager->flush();

            return $this->redirectToRoute('personality_show_all', ['id' => $personality->getId()]);
        }

        return $this->render('default/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getPersonalityById($id)
    {
        $personality = $this->getDoctrine()
            ->getRepository(Personality::class)
            ->find($id);

        if (!$personality) {
            throw $this->createNotFoundException(
                'No personality found for id '.$id
            );
        }

        return $personality;
    }
}
