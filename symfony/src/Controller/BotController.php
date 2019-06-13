<?php

namespace App\Controller;

use App\Entity\Bot;
use App\Form\BotType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BotController extends AbstractController
{
    /**
     * @Route("/bots", name="bot_show_all")
     */
    public function showAllBot()
    {
        $bots = $this->getDoctrine()
            ->getRepository(Bot::class)
            ->findAll();

        return $this->render('bot/viewAll.html.twig', [
            'bots' => $bots
        ]);
    }

    /**
     * @Route("/bot/delete/{id}", name="bot_delete")
     */
    public function deleteBot($id)
    {
        $bot = $this->getBotById($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($bot);
        $entityManager->flush();

        return $this->redirectToRoute('bot_show_all');
    }

    /**
     * @Route("/bot/edit/{id}", name="bot_edit")
     */
    public function editBot($id, Request $request)
    {

        /** @var Bot $bot */
        $bot = $this->getBotById($id);

        return $this->handleForm($bot, $request);
    }

    /**
     * @Route("/bots/new/", name="bot_create")
     */
    public function newBot(Request $request)
    {
        $bot = new Bot();

        return $this->handleForm($bot, $request);
    }

    private function handleForm(Bot $bot, Request $request)
    {
        $form = $this->createForm( BotType::class, $bot);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bot = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bot);
            $entityManager->flush();

            return $this->redirectToRoute('bot_show_all', ['id' => $bot->getId()]);
        }

        return $this->render('default/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getBotById($id)
    {
        $bot = $this->getDoctrine()
            ->getRepository(Bot::class)
            ->find($id);

        if (!$bot) {
            throw $this->createNotFoundException(
                'No bot found for id '.$id
            );
        }

        return $bot;
    }
}
