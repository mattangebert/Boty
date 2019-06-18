<?php

namespace App\Controller;

use App\Entity\Bot;
use App\Entity\Category;
use App\Entity\Personality;
use App\Entity\PersonalityTyp;
use App\Entity\PhraseTyp;
use App\Form\ConversationType;
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

        $form = $this->createForm(ConversationType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation = $form->getData();
            $random_phrase = self::NO_PHRASE_FOUND;

            /** @var Bot $bot */
            $bot = $conversation['bot'];
            /** @var Personality $personality */
            $personality = $bot->getPersonality();
            /** @var Category $category */
            $category = $conversation['category'];
            /** @var PhraseTyp $type */
            $type = $conversation['type'];


            $defaultPersonality = $entityManager->getRepository(PersonalityTyp::class)
            ->findOneBy([
                'name' => 'Normal'
            ]);

            $personalityTypes = array(
                $personality->getPersonalityTypOne(),
                $personality->getPersonalityTypTwo(),
                $defaultPersonality
            );

            $query = 'SELECT * FROM phrase WHERE category_id = ' . $category->getId() . ' AND phrase_typ_id = ' . $type->getId() . ' AND (';

            foreach ($personalityTypes as $key => $personalityType) {

                if (null != $personalityType) {
                    $query .= 'personality_typ_id = ' . $personalityType->getId();

                    if($key < count($personalityTypes) - 1) {
                        $query .= ' OR ';
                    }
                }
            }

            $query.= ') ORDER BY rand() LIMIT 1';
            $statement = $entityManager->getConnection()->prepare($query);
            $statement->execute();
            $result = $statement->fetch();

            if(!empty($result)) {
                $random_phrase = $result['phrase'];
            }
        }

        $history = $user->getConversation();

        $view = $this->render('default/index.html.twig', [
            'userName' => $userName,
            'form' => $form->createView(),
            'history' => $history,
            'phrase' => $random_phrase
        ]);

        if ($random_phrase !== self::NO_PHRASE_FOUND) {
            array_push($history, $random_phrase);
        }
        $user->setConversation($history);
        $entityManager->persist($user);
        $entityManager->flush();

        return $view;
    }

    /**
     * @Route("/clearConversation", name="clearConversation")
     */
    public function clearConversation(UserInterface $user = null)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user->setConversation(array());
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}
