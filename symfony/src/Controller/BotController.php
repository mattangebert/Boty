<?php

namespace App\Controller;

use App\Entity\Bot;
use App\Form\BotType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BotController extends BaseController
{
    /**
     * @Route("/bots", name="bot_show_all")
     */
    public function showAllBot()
    {
        return $this->showAllFromEntity('bot');
    }

    /**
     * @Route("/bot/delete/{id}", name="bot_delete")
     */
    public function deleteBot($id)
    {
       return $this->deleteEntity('bot', $id);
    }

    /**
     * @Route("/bot/edit/{id}", name="bot_edit")
     */
    public function editBot($id, Request $request)
    {

        /** @var Bot $bot */
        $bot = $this->getBotById($id);

        return $this->handleForm('bot',$bot, $request);
    }

    /**
     * @Route("/bots/new/", name="bot_create")
     */
    public function newBot(Request $request)
    {
        $bot = new Bot();

        return $this->handleForm('bot',$bot, $request);
    }
}
