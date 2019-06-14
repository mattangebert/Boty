<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseController
{
    protected $entityName = 'category';

    /**
     * @Route("/categories", name="category_show_all")
     */
    public function showAllCategory()
    {
        return $this->showAllFromEntity();
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
    public function deleteCategory($id)
    {
        return $this->deleteEntity($id);
    }

    /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function editCategory($id, Request $request)
    {
        /** @var Category $category */
        $category = $this->getEntityById($id);

        return $this->handleForm($category, $request);
    }

    /**
     * @Route("/categories/new/", name="category_create")
     */
    public function newCategory(Request $request)
    {
        $category = new Category();

        return $this->handleForm($category, $request);
    }
}
