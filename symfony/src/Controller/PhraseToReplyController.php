<?php

namespace App\Controller;

use App\Entity\Phrase;
use App\Entity\PhraseToReply;
use App\Form\PhraseToReplyType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhraseToReplyController extends BaseController
{
    protected $entityName = 'phraseToReply';

    /**
     * @Route("phrase/all/replies", name="phraseToReply_show_all")
     */
    public function showAllReply()
    {
        $phrases = $this->getDoctrine()->getRepository(Phrase::class)->findAllWithReplies();

        $additional = [
            'phrases' => $phrases,
            'currentPhraseId' => 'all'
        ];

        return $this->showAllFromEntity($additional);
    }

    /**
     * @Route("phrase/{id}/replies", name="phrase_show_replies")
     */
    public function showRepliesByPhrase($id)
    {

        /** @var Phrase $phrase */
        $phrase = $this->getEntityById($id, 'phrase');
        $replies = array();
        $phrases = $this->getDoctrine()->getRepository(Phrase::class)->findAllWithReplies();

        $additional = [
            'phrases' => $phrases,
            'currentPhraseId' => $phrase->getId()
        ];

        if (!$phrase->getReplyPhrases()->isEmpty()) {
            $replies = $phrase->getReplyPhrases()->getValues();
        }

        return $this->showCollection($replies, $additional);
    }

    /**
     * @Route("/phraseToReply/delete/{id}", name="phraseToReply_delete")
     */
    public function deleteReply($id)
    {
        return $this->deleteEntity($id);
    }


    /**
     * @Route("/phrase/{id}/addReply", name="phrase_add_reply")
     */
    public function addReply($id, Request $request)
    {
        $reply = new PhraseToReply();

        /** @var Phrase $phrase */
        $phrase = $this->getEntityById($id, 'phrase');

        $reply->setPhrase($phrase);

        $form = $this->createForm(PhraseToReplyType::class, $reply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $relation = $this->getDoctrine()->getRepository(PhraseToReply::class)
                ->findOneByIds($id, $form->getData()->getReplyPhrase()->getId());

            if (null !== $relation) {
                $form->addError(new FormError('Reply already Exist'));
                return $this->render('default/form.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            //get data from Form
            $reply = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reply);

            $entityManager->flush();

            return $this->redirectToRoute($this->entityName.'_show_all', ['pId' => $phrase->getId()]);
        }

        return $this->render('default/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
