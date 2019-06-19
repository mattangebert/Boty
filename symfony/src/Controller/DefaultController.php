<?php

namespace App\Controller;

use App\Entity\Bot;
use App\Entity\Category;
use App\Entity\Personality;
use App\Entity\PersonalityTyp;
use App\Entity\Phrase;
use App\Entity\PhraseTyp;
use App\Form\ConversationType;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends BaseController
{
    const NO_PHRASE_FOUND = 'No Phrase Found';

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, UserInterface $user = null)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userName = $user->getName();

        $conversation = null;
        $random_phrase = '';
        $reply = '';

        /** @var Form $form */
        $form = $this->createForm(ConversationType::class, $conversation, [
            'data' => array('isResponse'=> $request->request->get('conversation')['isResponse'])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation = $form->getData();

            /** @var PersonalityTyp $userPersonalityType */
            $userPersonalityType = $conversation['personality'];

            if (!$form->has('respond') || !$form->get('respond')->isClicked()) {

                /** @var Bot $bot */
                $bot = $conversation['bot'];
                /** @var Personality $personality */
                $personality = $bot->getPersonality();
                /** @var Category $category */
                $category = $conversation['category'];
                /** @var PhraseTyp $type */
                $type = $conversation['type'];

                // get personality typ ids
                $personalityTypIds = $this->getPersonalityTypIds($form, $userPersonalityType, $personality);

                // get random phrase
                $result = $this->getRandomPhraseByFilters($personalityTypIds, $category, $type);

                // set phrase for view to random phrase if found
                $random_phrase = empty($result) ? self::NO_PHRASE_FOUND : $result['phrase'];

                if ($random_phrase !== self::NO_PHRASE_FOUND) {
                    if ($form->get('talk_bot')->isClicked()) {
                        $random_phrase = $bot->getName() . ': ' . $random_phrase;
                    }
                    if ($form->get('talk_self')->isClicked()) {
                        $random_phrase = $user->getName() . ': ' . $random_phrase;
                    }
                }

                // get response
                if ($random_phrase !== self::NO_PHRASE_FOUND) {
                    if ($form->get('talk_self')->isClicked()) {
                        $replies = $entityManager->getRepository(
                            Phrase::class)->findAllRepliesByFilters($result['id'],
                            $category->getId(),
                            '',
                            $this->getBotPersonalityTypIds($personality)
                        );

                        if (!empty($replies)) {
                            $reply = $replies[array_rand($replies)]->getPhrase();
                        }
                    }

                    if ($form->get('talk_bot')->isClicked()) {
                        $newForm = $form = $this->createForm(ConversationType::class, $conversation, [
                            'data' => array('isResponse' => true)
                        ]);

                        $view = $this->render('default/index.html.twig', [
                            'userName' => $userName,
                            'form' => $newForm->createView(),
                            'history' => $user->getConversation(),
                            'phrase' => $random_phrase,
                            'reply' => $reply
                        ]);

                        $this->updateConversation($user, $random_phrase, $reply);

                        return $view;
                    }
                }

                if ($reply != '') {
                    if ($form->get('talk_bot')->isClicked()) {
                        $reply = $user->getName() . ': ' . $reply;
                    }
                    if ($form->get('talk_self')->isClicked()) {
                        $reply = $bot->getName() . ': ' . $reply;
                    }
                }
            }

            // user respond form
            if ($form->has('respond') && $form->get('respond')->isClicked()) {
                $history = $user->getConversation();

                $searchPhrase = substr(end($history), strpos(end($history), ':') + 2);

                /** @var Phrase $phrase */
                $phrase = $entityManager->getRepository(Phrase::class)->findOneBy([
                    'phrase' => $searchPhrase
                ]);

                $replies = $entityManager->getRepository(
                    Phrase::class)->findAllRepliesByFilters($phrase->getId(),
                    $phrase->getCategory()->getId(),
                    '',
                    $this->getUserPersonalityTypIds($userPersonalityType)
                );

                if (!empty($replies)) {
                    $reply = $replies[array_rand($replies)]->getPhrase();
                }

                if ($reply != '') {
                    $reply = $user->getName() . ': ' . $reply;
                }

                $newForm = $form = $this->createForm(ConversationType::class, $conversation, [
                    'data' => array('isResponse' => false)
                ]);

                $view = $this->render('default/index.html.twig', [
                    'userName' => $userName,
                    'form' => $newForm->createView(),
                    'history' => $user->getConversation(),
                    'phrase' => $random_phrase,
                    'reply' => $reply
                ]);

                $this->updateConversation($user, $random_phrase, $reply);

                return $view;

            }
        }

        $view = $this->render('default/index.html.twig', [
            'userName' => $userName,
            'form' => $form->createView(),
            'history' => $user->getConversation(),
            'phrase' => $random_phrase,
            'reply' => $reply
        ]);

        // safe phrase & reply in users history
        $this->updateConversation($user, $random_phrase, $reply);

        return $view;
    }

    /**
     * @Route("/clearConversation", name="clearConversation")
     *
     * @param UserInterface|null $user
     * @return RedirectResponse
     */
    public function clearConversation(UserInterface $user = null)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user->setConversation(array());
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    private function updateConversation(UserInterface $user, string $random_phrase, string $reply)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $history = $user->getConversation();

        if ($random_phrase !== self::NO_PHRASE_FOUND) {
            array_push($history, $random_phrase);
        }

        if ($reply != '') {
            array_push($history, $reply);
        }

        $user->setConversation($history);
        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function getRandomPhraseByFilters(array $personalityTypIds, Category $category, PhraseTyp $type)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $query = 'SELECT * FROM phrase WHERE category_id = ' . $category->getId() . ' AND phrase_typ_id = ' . $type->getId() . ' AND (';
        foreach ($personalityTypIds as $key => $personalityTypId) {

            if (null != $personalityTypId) {
                $query .= 'personality_typ_id = ' . $personalityTypId;

                if($key < count($personalityTypIds) - 1) {
                    $query .= ' OR ';
                }
            }
        }
        $query.= ') ORDER BY rand() LIMIT 1';
        $statement = $entityManager->getConnection()->prepare($query);
        $statement->execute();
        $result = $statement->fetch();

        return $result;
    }

    private function getPersonalityTypIds(Form $form, PersonalityTyp $userPersonality, Personality $botPersonality)
    {
        $personalityTypIds = null;

        if($form->get('talk_self')->isClicked()) {
            $personalityTypIds = $this->getUserPersonalityTypIds($userPersonality);
        }

        if($form->get('talk_bot')->isClicked()) {
            $personalityTypIds = $this->getBotPersonalityTypIds($botPersonality);
        }

        return $personalityTypIds;

    }

    private function getUserPersonalityTypIds(PersonalityTyp $personality)
    {
        $personalityTypIds = array(
            $personality->getId(),
            $this->getDefaultPersonalityTyp()->getId()
        );

        return $personalityTypIds;
    }

    private function getBotPersonalityTypIds(Personality $personality)
    {
        $personalityTypIds = array(
            $personality->getPersonalityTypOne()->getId(),
        );

        // personality typ two can be null
        if ($personality->getPersonalityTypTwo()) {
            array_push($personalityTypIds, $personality->getPersonalityTypTwo()->getId());
        }

        array_push($personalityTypIds, $this->getDefaultPersonalityTyp()->getId());

        return $personalityTypIds;
    }

    private function getDefaultPersonalityTyp()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $defaultPersonality = $entityManager->getRepository(PersonalityTyp::class)
            ->findOneBy([
                'name' => 'Normal'
        ]);

        return $defaultPersonality;
    }
}
