<?php

namespace App\Controller;

use App\Entity\Personality;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PersonalityController extends BaseController
{
    protected $entityName = 'personality';

    /**
     * @Route("/personalities", name="personality_show_all")
     */
    public function showAllPersonality()
    {
        return $this->showAllFromEntity();
    }

    /**
     * @Route("/personality/delete/{id}", name="personality_delete")
     */
    public function deletePersonality($id)
    {
        return $this->deleteEntity($id);
    }

    /**
     * @Route("/personality/edit/{id}", name="personality_edit")
     */
    public function editPersonality($id, Request $request)
    {
        /** @var Personality $personality */
        $personality = $this->getEntityById($id);

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
}
