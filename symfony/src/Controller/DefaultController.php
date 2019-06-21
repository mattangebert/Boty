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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends BaseController
{
    const NO_PHRASE_FOUND = 'No Phrase Found';

    /** @var FormInterface $form */
    private $form;

    /** @var Bot $bot */
    private $bot;

    /** @var UserInterface $user*/
    private $user;

    /** @var Category $category*/
    private $category;

    private $conversation;

    /** @var array $personalityTypIds */
    private $personalityTypIds;

    /** @var array $phrases */
    private $phrases;

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, UserInterface $user)
    {
        $this->user = $user;
        $this->conversation = null;
        $this->phrases['phrase'] = '';
        $this->phrases['reply'] = '';

        $this->form = $this->createForm(ConversationType::class, $this->conversation, [
            'data' => array('isResponse'=> $request->request->get('conversation')['isResponse'])
        ]);

        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->conversation = $this->form->getData();

            // handle default form
            if (!$this->form->has('respond') || !$this->form->get('respond')->isClicked()) {
               $view = $this->handleConversationForm();
            }

            // handle user respond form
            if ($this->form->has('respond') && $this->form->get('respond')->isClicked()) {
                $view = $this->handleRespondForm();
            }
        }

        if (!isset($view)) {
            $view = $this->getViewAndUpdateConversation($this->form, $this->phrases['phrase'], $this->phrases['reply']);
        }

        return $view;
    }

    /**
     * @Route("/clearConversation", name="clearConversation")
     *
     * @param UserInterface|null $user
     * @return RedirectResponse
     */
    public function clearConversation(UserInterface $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->user = $user;
        $this->user->setConversation(array());
        $entityManager->persist($this->user);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    private function handleConversationForm()
    {
        $this->bot = $this->conversation['bot'];
        $this->category = $this->conversation['category'];

        // set personality typ ids
        $this->setPersonalityTypIds();

        // get random phrase
        /** @var Phrase $result */
        $result = $this->getRandomPhraseByFilters();

        // set phrase for view to random phrase if found
        $this->phrases['phrase'] = empty($result) ? self::NO_PHRASE_FOUND : $result->getPhrase();
        $this->phrases['phrase'] = $this->addNameToPhrase($this->phrases['phrase']);

        // get response
        if ($this->phrases['phrase'] !== self::NO_PHRASE_FOUND && !empty($result->getReplyPhrases()->getValues())) {
            if ($this->form->get('talk_self')->isClicked()) {
                $this->phrases['reply'] = $this->getReplyFromBot($result);
            }

            if ($this->form->get('talk_bot')->isClicked()) {
                $newForm = $this->createForm(ConversationType::class, $this->conversation, [
                    'data' => array('isResponse' => true)
                ]);

                $view = $this->getViewAndUpdateConversation($newForm, $this->phrases['phrase'], '');
            }
        }

        return isset($view) ? $view : null;
    }

    private function handleRespondForm()
    {
        $this->phrases['reply'] = $this->getReplyFromUser();

        $newForm = $this->createForm(ConversationType::class, $this->conversation, [
            'data' => array('isResponse' => false)
        ]);

        return $this->getViewAndUpdateConversation($newForm, '', $this->phrases['reply']);
    }

    private function getReplyFromUser()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $history = $this->user->getConversation();
        $reply = '';

        $searchPhrase = substr(end($history), strpos(end($history), ':') + 2);

        /** @var Phrase $phrase */
        $phrase = $entityManager->getRepository(Phrase::class)->findOneBy([
            'phrase' => $searchPhrase
        ]);

        if ($phrase) {
            $replies = $entityManager->getRepository(
                Phrase::class)->findAllRepliesByFilters($phrase->getId(),
                $phrase->getCategory()->getId(),
                '',
                $this->getUserPersonalityTypIds()
            );

            if (!empty($replies)) {
                $reply = $replies[array_rand($replies)]->getPhrase();
                $reply = $this->addNameToPhrase($reply);
            }
        }

        return $reply;
    }

    private function getReplyFromBot(Phrase $phrase)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reply = '';

        if ($phrase) {
            $replies = $entityManager->getRepository(
                Phrase::class)->findAllRepliesByFilters($phrase->getId(),
                $this->category->getId(),
                '',
                $this->getBotPersonalityTypIds()
            );

            if (!empty($replies)) {
                $reply = $replies[array_rand($replies)]->getPhrase();
                $reply = $this->addNameToPhrase($reply, true);
            }
        }

        return $reply;
    }

    private function addNameToPhrase(string $phrase, bool $isResponse = false)
    {
        $rtnPhrase = $phrase;
        if($phrase !== '' && $phrase !== self::NO_PHRASE_FOUND) {
            if( $this->form->has('respond') && $this->form->get('respond')->isClicked()) {
                $rtnPhrase = $this->user->getName() . ': ' . $phrase;
            }

            if ($isResponse && $this->form->has('talk_self') && $this->form->get('talk_self')->isClicked()) {
                $rtnPhrase = $this->bot->getName() . ': ' . $phrase;
            }

            if (!$isResponse && !$this->form->has('respond')) {
                if ($this->form->has('talk_bot') && $this->form->get('talk_bot')->isClicked()) {
                    $rtnPhrase = $this->bot->getName() . ': ' . $phrase;
                }

                if ($this->form->has('talk_self') && $this->form->get('talk_self')->isClicked()) {
                    $rtnPhrase =$this->user->getName() . ': ' . $phrase;
                }
            }
        }
        return $rtnPhrase;
    }

    private function getViewAndUpdateConversation(FormInterface $form, string $random_phrase, string $reply)
    {
        $view = $this->render('default/index.html.twig', [
            'userName' => $this->user->getName(),
            'form' => $form->createView(),
            'history' => $this->user->getConversation(),
            'phrase' => $random_phrase,
            'reply' => $reply
        ]);

        $this->updateConversation($random_phrase, $reply);

        return $view;
    }

    private function updateConversation(string $random_phrase, string $reply)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $history = $this->user->getConversation();

        if ($random_phrase !== self::NO_PHRASE_FOUND) {
            array_push($history, $random_phrase);
        }

        if ($reply != '') {
            array_push($history, $reply);
        }

        $this->user->setConversation($history);
        $entityManager->persist($this->user);
        $entityManager->flush();
    }

    private function getRandomPhraseByFilters()
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var PhraseTyp $type */
        $type = $this->conversation['type'];

        $query = 'SELECT * FROM phrase WHERE category_id = ' . $this->category->getId() . ' AND phrase_typ_id = ' . $type->getId() . ' AND (';
        foreach ($this->personalityTypIds as $key => $personalityTypId) {

            if (null != $personalityTypId) {
                $query .= 'personality_typ_id = ' . $personalityTypId;

                if($key < count($this->personalityTypIds) - 1) {
                    $query .= ' OR ';
                }
            }
        }
        $query.= ') ORDER BY rand() LIMIT 1';
        $statement = $entityManager->getConnection()->prepare($query);
        $statement->execute();
        $result = $statement->fetch();

        if (!empty($result)) {
           $result = $entityManager->getRepository(Phrase::class)->find($result['id']);
        }

        return $result;
    }

    private function setPersonalityTypIds()
    {
        $this->personalityTypIds = null;

        if($this->form->get('talk_self')->isClicked()) {
            $this->personalityTypIds = $this->getUserPersonalityTypIds();
        }

        if($this->form->get('talk_bot')->isClicked()) {
            $this->personalityTypIds = $this->getBotPersonalityTypIds();
        }
    }

    private function getUserPersonalityTypIds()
    {
        /** @var PersonalityTyp $personalityTyp */
        $personalityTyp = $this->conversation['personality'];

        $personalityTypIds = array(
            $personalityTyp->getId(),
            $this->getDefaultPersonalityTyp()->getId()
        );

        return $personalityTypIds;
    }

    private function getBotPersonalityTypIds()
    {
        $personality = $this->bot->getPersonality();

        $personalityTypIds = array(
            $personality->getPersonalityTypOne()->getId(),
        );

        // personality typ two can be null
        if ($personality->getPersonalityTypTwo()) {
            $personalityTypIds[] =  $personality->getPersonalityTypTwo()->getId();
        }

        $personalityTypIds[] = $this->getDefaultPersonalityTyp()->getId();

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
