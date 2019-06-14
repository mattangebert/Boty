<?php

namespace App\Controller;

use App\Entity\Bot;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BotController extends BaseController
{
    protected $entityName = 'bot';

    /**
     * @Route("/bots", name="bot_show_all")
     */
    public function showAllBot()
    {
        return $this->showAllFromEntity();
    }

    /**
     * @Route("/bot/delete/{id}", name="bot_delete")
     */
    public function deleteBot($id)
    {
       return $this->deleteEntity($id);
    }

    /**
     * @Route("/bot/edit/{id}", name="bot_edit")
     */
    public function editBot($id, Request $request)
    {
        /** @var Bot $bot */
        $bot = $this->getEntityById($id);

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
}
