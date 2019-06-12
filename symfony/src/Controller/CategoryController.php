<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="category_show_all")
     */
    public function showAllCategory()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/viewAll.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
    public function deleteCategory($id)
    {
        $category = $this->getCategoryById($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('category_show_all');
    }

    /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function editCategory($id, Request $request) {
        $category = $this->getCategoryById($id);

        return $this->handleForm($category, $request);
    }

    /**
     * @Route("/categories/new/", name="category_create")
     */
    public function newCategory(Request $request) {
        $category = new Category();

        return $this->handleForm($category, $request);
    }

    private function handleForm(Category $category, Request $request)
    {
        $form = $this->createForm( CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_show_all', ['id' => $category->getId()]);
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getCategoryById($id) {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$id
            );
        }

        return $category;
    }
}
