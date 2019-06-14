<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    protected $entityName = 'user';

    /**
     * @Route("/user/{id}", name="user_show")
     */
    public function showUser($id)
    {
       return $this->showEntity($id);
    }

    /**
     * @Route("/users", name="user_show_all")
     */
    public function showAllUser()
    {
        return $this->showAllFromEntity();
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function deleteUser($id)
    {
        return $this->deleteEntity($id);
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    public function editUser($id, Request $request)
    {
        /** @var User $user */
        $user = $this->getEntityById($id);

        return $this->handleForm($user, $request);
    }
}
