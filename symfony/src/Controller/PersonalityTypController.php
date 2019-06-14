<?php

namespace App\Controller;

use App\Entity\PersonalityTyp;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PersonalityTypController extends BaseController
{
    protected $entityName = 'personalityTyp';

    /**
     * @Route("/personalityTyps", name="personalityTyp_show_all")
     */
    public function showAllPersonalityTyp()
    {
        return $this->showAllFromEntity();
    }

    /**
     * @Route("/personalityTyp/delete/{id}", name="personalityTyp_delete")
     */
    public function deletePersonalityTyp($id)
    {
        return $this->deleteEntity($id);
    }

    /**
     * @Route("/personalityTyp/edit/{id}", name="personalityTyp_edit")
     */
    public function editPersonalityTyp($id, Request $request)
    {
        /** @var PersonalityTyp $personalityTyp */
        $personalityTyp = $this->getEntityById($id);

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
}
