<?php

namespace App\Controller;

use App\Entity\Bot;
use App\Entity\Category;
use App\Form\BotType;
use App\Form\CategoryType;
use function PHPSTORM_META\type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{

    private $entityTypes = [
        'bot' => Bot::class,
        'category' => Category::class
    ];

    private $entityForms = [
        'bot' => BotType::class,
        'category' => CategoryType::class
    ];

    protected function showAllFromEntity($entityName)
    {
        $entity = $this->getDoctrine()
            ->getRepository($this->entityTypes[$entityName])
            ->findAll();

        $collectionName = $entityName . 's';

        if ($this->endsWith($entityName, 'y')) {
            $collectionName = substr($entityName, 0, -1) . 'ies';
        }

        $folderName = $this->getFolderPath($entityName);

        return $this->render($folderName.'/viewAll.html.twig', [
            $collectionName => $entity
        ]);
    }

    // todo get mulible

    //todo get single

    protected function deleteEntity($entityName, $id)
    {
        $entity = $this->getEntityById($entityName, $id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute($entityName.'_show_all');
    }


    protected function handleForm($entityName, $entity, Request $request)
    {
        if (!is_a($entity, 'App\Entity\\'.ucfirst($entityName))) {
            throw new \Exception('Wrong type: '.get_class($entity).' expected type: ' . 'App\Entity\\'.ucfirst($entityName));
        }

        $form = $this->createForm( $this->entityForms[$entityName], $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute($entityName.'_show_all', ['id' => $entity->getId()]);
        }

        return $this->render('default/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }






    private function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    private function getFolderPath($entityName)
    {
        return preg_replace_callback('/([A-Z])/', function($m) { return '_'.strtolower($m[0]); }, $entityName);
    }

    private function getEntityById($entityName, $id)
    {
        $entity = $this->getDoctrine()
            ->getRepository($this->entityTypes[$entityName])
            ->find($id);

        if (!$entity) {
            throw $this->createNotFoundException(
                'Couldnt find ' . $entityName . 'for id ' .$id
            );
        }

        return $entity;
    }
}
