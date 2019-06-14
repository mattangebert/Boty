<?php

namespace App\Controller;

use App\Entity\Bot;
use App\Entity\Category;
use App\Entity\Personality;
use App\Entity\PersonalityTyp;
use App\Entity\Phrase;
use App\Entity\PhraseTyp;
use App\Entity\User;
use App\Form\BotType;
use App\Form\CategoryType;
use App\Form\PersonalityType;
use App\Form\PersonalityTypType;
use App\Form\PhraseType;
use App\Form\PhraseTypType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{

    private $entityTypes = [
        'bot' => Bot::class,
        'category' => Category::class,
        'personality' => Personality::class,
        'personalityTyp' => PersonalityTyp::class,
        'phrase' => Phrase::class,
        'phraseTyp' => PhraseTyp::class,
        'user' => User::class
    ];

    private $entityForms = [
        'bot' => BotType::class,
        'category' => CategoryType::class,
        'personality' => PersonalityType::class,
        'personalityTyp' => PersonalityTypType::class,
        'phrase' => PhraseType::class,
        'phraseTyp' => PhraseTypType::class,
        'user' => UserType::class
    ];

    protected $entityName;

    protected function showAllFromEntity($additional = null)
    {
        $entities = $this->getAllFromEntity();

        $collectionName = $this->getCollectionName();

        $folderName = $this->getFolderPath();

        return $this->render($folderName.'/viewAll.html.twig', [
            $collectionName => $entities,
            'title' => $collectionName,
            'additional' => $additional

        ]);
    }

    protected function showAllFromEntityByRelation($relationEntityName, $additional, $rId)
    {
        $entities = $this->getDoctrine()
            ->getRepository($this->entityTypes[$this->entityName])
            ->findBy([
                $relationEntityName => $rId,
            ]);

        $collectionName = $this->getCollectionName();

        $folderName = $this->getFolderPath();

        //$relationEntity = $this->getEntityById($rId, $relationEntityName);

        return $this->render($folderName.'/viewAll.html.twig', [
            $collectionName => $entities,
            'title' => $collectionName,
            'additional' => $additional
        ]);
    }


    protected function showCollection($entities, $additional)
    {

        $collectionName = $this->getCollectionName();

        $folderName = $this->getFolderPath();

        return $this->render($folderName.'/viewAll.html.twig', [
            $collectionName => $entities,
            'title' => $collectionName,
            'additional' => $additional
        ]);
    }

    protected function showEntity($id)
    {
        $entity = $this->getEntityById($id);

        $folderName = $this->getFolderPath();

        return $this->render($folderName.'/view.html.twig', [
            $this->entityName => $entity
        ]);
    }

    protected function deleteEntity($id)
    {
        $entity = $this->getEntityById($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute($this->entityName.'_show_all');
    }


    protected function handleForm($entity, Request $request)
    {
        if (!is_a($entity, 'App\Entity\\'.ucfirst($this->entityName))) {
            throw new \Exception('Wrong type: '.get_class($entity).' expected type: ' . 'App\Entity\\'.ucfirst($this->entityName));
        }

        $form = $this->createForm( $this->entityForms[$this->entityName], $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute($this->entityName.'_show_all', ['id' => $entity->getId()]);
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

    private function getFolderPath()
    {
        return preg_replace_callback('/([A-Z])/', function($m) { return '_'.strtolower($m[0]); }, $this->entityName);
    }

    private function getCollectionName()
    {
        $collectionName = $this->entityName . 's';

        if ($this->endsWith($this->entityName, 'y')) {
            $collectionName = substr($this->entityName, 0, -1) . 'ies';
        }

        return $collectionName;
    }


    protected function getAllFromEntity($entityName = '')
    {
        if (!array_key_exists($entityName, $this->entityTypes)) {
            $entityName = $this->entityName;
        }

        $entities = $this->getDoctrine()
            ->getRepository($this->entityTypes[$entityName])
            ->findAll();

        return $entities;
    }

    protected function getEntityById($id, $entityName = '')
    {
        if (!array_key_exists($entityName, $this->entityTypes)) {
            $entityName = $this->entityName;
        }

        $entity = $this->getDoctrine()
            ->getRepository($this->entityTypes[$entityName])
            ->find($id);

        if (!$entity) {
            throw $this->createNotFoundException(
                'Couldn\'t find ' . $entityName. 'for id ' .$id
            );
        }

        return $entity;
    }
}
