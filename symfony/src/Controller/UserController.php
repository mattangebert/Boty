<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_show")
     */
    public function showUser($id)
    {
       $user = $this->getUserById($id);

        return $this->render('user/view.html.twig', [
            'user' => $user
        ]);

    }

    /**
     * @Route("/users", name="user_show_all")
     */
    public function showAllUser()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/viewAll.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function deleteUser($id)
    {
        $user = $this->getUserById($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_show_all');
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    public function editUser($id, Request $request) {
        $user = $this->getUserById($id);

        return $this->handleForm($user, $request);

    }

//    /**
//     * @Route("/users/new/", name="user_create")
//     */
//    public function newUser(Request $request) {
//        $user = new User();
//
//        return $this->handleForm($user, $request);
//
//    }

    private function handleForm(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getUserById($id) {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        return $user;
    }



}
