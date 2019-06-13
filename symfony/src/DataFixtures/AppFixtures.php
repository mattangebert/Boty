<?php

namespace App\DataFixtures;

use App\Entity\Bot;
use App\Entity\Category;
use App\Entity\Personality;
use App\Entity\PersonalityTyp;
use App\Entity\Phrase;
use App\Entity\PhraseTyp;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $categories = array(
        'Default',
        'Random',
        'Family & Friendship',
        'Movie',
        'Music',
        'Sports',
        'Food',
        'Travel & Nature',
        'Technology',
        'Fashion',
        'Hobbies',
        'Weird'
    );

    private $phraseTyps = array(
        'DefaultType',
        'Question',
        'Answer',
        'Statement',
        'Opinion',
        'Insult'

    );

    private $personalityTyps = array(
        'Normal',
        'Formal',
        'Informal',
        'Flowery',
        'Mean',
        'Crazy',
        'Vegan'
    );

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    public function load(ObjectManager $manager)
    {
        $this->createUserFixtures($manager);
        $this->createCategoryFixtures($manager);
        $this->createPhraseTypFixtures($manager);
        $this->createPersonalityTypFixtures($manager);
        $this->createPersonalityFixtures($manager);
        $this->createBotFixtures($manager);
        $this->createPhraseFixtures($manager);
        $this->createPhraseToAlternativeFixtures($manager);
        $this->createPhraseToReplyFixtures($manager);

        $this->createBotMelanie($manager);
    }

    private function createUserFixtures(ObjectManager $manager)
    {
        /**
         * create test users
         */
        for ($i = 0; $i < 3; $i++) {
            $user =  new User();
            $user->setEmail('user_'.$i.'@test.de');
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'changeme123'
            ));
            $user->setName('user '.$i);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createCategoryFixtures(ObjectManager $manager)
    {
        /**
         * create test categories
         */
        foreach ($this->categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
            $this->addReference($categoryName, $category);
        }

        $manager->flush();
    }

    private function createPhraseTypFixtures(ObjectManager $manager)
    {
        /**
         * create test phraseTyps
         */
        foreach ($this->phraseTyps as $typ) {
            $phraseTyp =  new PhraseTyp();
            $phraseTyp->setName($typ);

            $manager->persist($phraseTyp);
            $this->addReference($typ, $phraseTyp);

        }

        $manager->flush();
    }

    private function createPersonalityTypFixtures(ObjectManager $manager)
    {
        /**
         * create test personalityTyps
         */
        foreach ($this->personalityTyps as $type) {
            $personalityTyp =  new PersonalityTyp();
            $personalityTyp->setName($type);

            $manager->persist($personalityTyp);
            $this->addReference($type, $personalityTyp);
        }

        $manager->flush();
    }

    private function createPersonalityFixtures(ObjectManager $manager)
    {
        /**
         * create test personalities
         */
        for($i = 0; $i < 3; $i++) {
            $personality =  new Personality();

            // copy of type array
            $types = $this->personalityTyps;

            // get 2 different types
            $keyOne = array_rand($types);
            $typeOne = $types[$keyOne];
            unset($types[$keyOne]);
            $keyTwo = array_rand($types);
            $typeTwo = $types[$keyTwo];

            /** @var PersonalityTyp $refOne */
            $refOne = $this->getReference($typeOne);
            /** @var PersonalityTyp $refTwo */
            $refTwo = $this->getReference($typeTwo);

            $personality->setName($typeOne . '-' . $typeTwo);
            $personality->setPersonalityTypOne($refOne);
            $personality->setPersonalityTypTwo($refTwo);

            $manager->persist($personality);
            $this->addReference('personality'.$i, $personality);
        }

        $manager->flush();
    }

    private function createBotFixtures(ObjectManager $manager)
    {
        for($i = 0; $i < 2; $i++) {
            $bot =  new Bot();

            $bot->setName('bot'.$i);
            $bot->setEnabled(true);

            date_default_timezone_set('Europe/Berlin');
            $bot->setCreatedAt(new \DateTime());

            /** @var Personality $personality */
            $personality =  $this->getReference('personality'.$i);
            $bot->setPersonality($personality);

            $manager->persist($bot);
            //$this->addReference('bot'.$i, $bot);
        }

        $manager->flush();
    }

    private function createBotMelanie(ObjectManager $manager)
    {
        $personality =  new Personality();

        /** @var PersonalityTyp $refOne */
        $refOne = $this->getReference('Vegan');
        $personality->setName('Veganer');
        $personality->setPersonalityTypOne($refOne);
        $manager->persist($personality);


        $melanie =  new Bot();

        $melanie->setName('Melanie');
        $melanie->setEnabled(true);
        $melanie->setCreatedAt(new \DateTime());
        $melanie->setPersonality($personality);

        $manager->persist($melanie);
        $manager->flush();
    }

    private function createPhraseFixtures(ObjectManager $manager)
    {
        foreach ($this->personalityTyps as $personalityTypName) {
            /** @var PersonalityTyp $personalityTyp */
            $personalityTyp = $this->getReference($personalityTypName);

            foreach ($this->categories as $categoryName) {
                /** @var Category $category */
                $category = $this->getReference($categoryName);

                foreach ($this->phraseTyps as $phraseTypName) {
                    /** @var PhraseTyp $phraseTyp */
                    $phraseTyp = $this->getReference($phraseTypName);

                    for($i = 0; $i < rand(2, 10); $i++) {
                        $phrase = new Phrase();
                        $phrase->setPersonalityTyp($personalityTyp);
                        $phrase->setCategory($category);
                        $phrase->setPhraseTyp($phraseTyp);
                        $phrase->setPhrase('This is the ' . $i . 'th Test Phrase of type: ' . $phraseTypName . ' of the category: ' . $categoryName . ' with the personalityTyp: ' . $personalityTypName);

                        $manager->persist($phrase);
                        //$this->addReference('phrase-'.$phraseTypName.'-'.$categoryName.'-'.$personalityTyp, $phrase);
                    }
                }
            }
        }


        $manager->flush();
    }


    private function createPhraseToAlternativeFixtures(ObjectManager $manager)
    {
        $manager->flush();
    }

    private function createPhraseToReplyFixtures(ObjectManager $manager)
    {
        $manager->flush();
    }
}
