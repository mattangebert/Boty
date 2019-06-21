<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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
     *
     * @param $id
     * @param UserInterface $self
     * @return RedirectResponse
     */
    public function deleteUser($id, UserInterface $self)
    {
        /** @var User $user */
        $user = $this->getEntityById($id);

        $this->checkUserIsSelfPermission($user, $self);

        return $this->deleteEntity($id);
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     *
     * @param $id
     * @param Request $request
     * @param UserInterface $self
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function editUser($id, Request $request, UserInterface $self)
    {
        /** @var User $user */
        $user = $this->getEntityById($id);

        $this->checkUserIsSelfPermission($user, $self);

        return $this->handleForm($user, $request);
    }

    private function checkUserIsSelfPermission(User $user, UserInterface $self)
    {
        if ($user->getId() !== $self->getId()) {
            throw $this->createAccessDeniedException(
                'Access denied or page not found. Please make sure you have the right url and permissions'
            );
        }
    }
}
