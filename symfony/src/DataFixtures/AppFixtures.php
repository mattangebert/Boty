<?php

namespace App\DataFixtures;

use App\Entity\Bot;
use App\Entity\Category;
use App\Entity\Personality;
use App\Entity\PersonalityTyp;
use App\Entity\Phrase;
use App\Entity\PhraseToAlternative;
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
        'Insult',
        'Greeting'

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
        //$this->createPhraseFixtures($manager);
        $this->createPhrases($manager);
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

//    private function createPhraseFixtures(ObjectManager $manager)
//    {
//        foreach ($this->personalityTyps as $personalityTypName) {
//            /** @var PersonalityTyp $personalityTyp */
//            $personalityTyp = $this->getReference($personalityTypName);
//
//            foreach ($this->categories as $categoryName) {
//                /** @var Category $category */
//                $category = $this->getReference($categoryName);
//
//                foreach ($this->phraseTyps as $phraseTypName) {
//                    /** @var PhraseTyp $phraseTyp */
//                    $phraseTyp = $this->getReference($phraseTypName);
//
//                    for($i = 0; $i < rand(2, 10); $i++) {
//                        $phrase = new Phrase();
//                        $phrase->setPersonalityTyp($personalityTyp);
//                        $phrase->setCategory($category);
//                        $phrase->setPhraseTyp($phraseTyp);
//                        $phrase->setPhrase('This is the ' . $i . 'th Test Phrase of type: ' . $phraseTypName . ' of the category: ' . $categoryName . ' with the personalityTyp: ' . $personalityTypName);
//
//                        $manager->persist($phrase);
//                        //$this->addReference('phrase-'.$phraseTypName.'-'.$categoryName.'-'.$personalityTyp, $phrase);
//                    }
//                }
//            }
//        }
//
//
//        $manager->flush();
//    }

    private function createPhrases(ObjectManager $manager)
    {
        $phrases = array(
            $p001 = new PhraseDummy( 'Yes', 'Default', 'Answer', 'Normal'),
            $p001a = new PhraseDummy('Certainly', 'Default', 'Answer', 'Formal'),
            $p001b = new PhraseDummy('Ok', 'Default', 'Answer', 'Informal'),
            $p001c = new PhraseDummy('Surely', 'Default', 'Answer', 'Flowery'),
            $p001d = new PhraseDummy('Obviously', 'Default', 'Answer', 'Mean'),
            $p001e = new PhraseDummy( 'Uh-huh', 'Default', 'Answer', 'Crazy'),
            $p001f = new PhraseDummy( 'Yes I\'m a Vegan', 'Default', 'Answer', 'Vegan'),
            $p001g = new PhraseDummy('Yep', 'Default', 'Answer', 'Informal'),
            $p001h = new PhraseDummy('Sure', 'Default', 'Answer', 'Informal'),
            $p001i = new PhraseDummy('You bet', 'Default', 'Answer', 'Informal'),
            $p001j = new PhraseDummy( 'Okay', 'Default', 'Answer', 'Normal'),
            $p001k = new PhraseDummy( 'Okie dokie', 'Default', 'Answer', 'Normal'),
            $p001l = new PhraseDummy( 'Alright', 'Default', 'Answer', 'Normal'),
            $p001m = new PhraseDummy( 'Sounds good', 'Default', 'Answer', 'Normal'),
            $p001n = new PhraseDummy('Definitly', 'Default', 'Answer', 'Formal'),
            $p001o = new PhraseDummy('Gladly', 'Default', 'Answer', 'Formal'),
            $p001p = new PhraseDummy('Absolutely', 'Default', 'Answer', 'Formal'),
            $p001q = new PhraseDummy('Indeed', 'Default', 'Answer', 'Formal'),
            $p001r = new PhraseDummy('Undoubtedly', 'Default', 'Answer', 'Flowery'),
            $p001s = new PhraseDummy('Indubitably', 'Default', 'Answer', 'Flowery'),
            $p001t = new PhraseDummy('Fine', 'Default', 'Answer', 'Mean'),
            $p001u = new PhraseDummy('Noooooooo', 'Default', 'Answer', 'Mean'),
            $p001v = new PhraseDummy( 'Forsooth', 'Default', 'Answer', 'Crazy'),
            $p001w = new PhraseDummy( 'Verily', 'Default', 'Answer', 'Crazy'),
            $p001x = new PhraseDummy( 'Yeay', 'Default', 'Answer', 'Vegan', 1),

            $p002 = new PhraseDummy('No', 'Default', 'Answer', 'Normal'),
            $p002a = new PhraseDummy( 'Unfortunately not', 'Default', 'Answer', 'Formal'),
            $p002b = new PhraseDummy( 'Nope', 'Default', 'Answer', 'Informal'),
            $p002c = new PhraseDummy('That doesn\'t sound right', 'Default', 'Answer', 'Flowery'),
            $p002d = new PhraseDummy('Fuck Off', 'Default', 'Answer', 'Mean'),
            $p002e = new PhraseDummy('I\'m slammed', 'Default', 'Answer', 'Crazy'),
            $p002f = new PhraseDummy( 'It\'s not my thing', 'Default', 'Answer', 'Vegan'),
            $p002g = new PhraseDummy('Look! Squirrel!', 'Default', 'Answer', 'Crazy'),
            $p002h = new PhraseDummy( 'Heck No', 'Default', 'Answer', 'Informal'),
            $p002i = new PhraseDummy('No Way', 'Default', 'Answer', 'Normal'),
            $p002j = new PhraseDummy( 'Regrettably not', 'Default', 'Answer', 'Formal'),
            $p002k = new PhraseDummy( 'It\'s a Wednesday. I have a "No on Wednesday" policy', 'Default', 'Answer', 'Vegan'),
            $p002l = new PhraseDummy('Ask me in a year', 'Default', 'Answer', 'Mean'),
            $p002m = new PhraseDummy('If only i could say yes', 'Default', 'Answer', 'Formal'),
            $p002n = new PhraseDummy('I’m at the end of my rope right now so have to take a raincheck', 'Default', 'Answer', 'Flowery'),
            $p002o = new PhraseDummy('I’m in a season of NO', 'Default', 'Answer', 'Mean'),
            $p002p = new PhraseDummy('NoNoNoNoNoNo', 'Default', 'Answer', 'Crazy'),
            $p002q = new PhraseDummy( 'My body says yes, but my heart says No', 'Default', 'Answer', 'Flowery'),
            $p002r = new PhraseDummy( 'N to the O.', 'Default', 'Answer', 'Flowery', 1),

            $p003 =  new PhraseDummy('How are you', 'Default', 'Greeting', 'Normal'),
            $p003a =  new PhraseDummy('Hey Hey', 'Default', 'Greeting', 'Informal'),
            $p003b =  new PhraseDummy('What\'s up', 'Default', 'Greeting', 'Informal'),
            $p003c =  new PhraseDummy('Good to see you', 'Default', 'Greeting', 'Normal'),
            $p003d =  new PhraseDummy('Nice to see you', 'Default', 'Greeting', 'Formal'),
            $p003e =  new PhraseDummy('Long time no see', 'Default', 'Greeting', 'Normal'),
            $p003f =  new PhraseDummy('It\'s been a while', 'Default', 'Greeting', 'Informal'),
            $p003g =  new PhraseDummy('Good morning', 'Default', 'Greeting', 'Formal'),
            $p003h =  new PhraseDummy('Pleased to meet you', 'Default', 'Greeting', 'Formal'),
            $p003i =  new PhraseDummy('Yo', 'Default', 'Greeting', 'Informal'),
            $p003j =  new PhraseDummy('Whazzup?', 'Default', 'Greeting', 'Crazy'),
            $p003k =  new PhraseDummy(' G’day mate', 'Default', 'Greeting', 'Flowery'),
            $p003l =  new PhraseDummy('Wish you a horrible day', 'Default', 'Greeting', 'Mean'),
            $p003m =  new PhraseDummy('Why are you here?', 'Default', 'Greeting', 'Mean'),
            $p003n =  new PhraseDummy('Fucking Asshole', 'Default', 'Greeting', 'Mean'),
            $p003o =  new PhraseDummy('Holy Cow', 'Default', 'Greeting', 'Vegan'),
            $p003p =  new PhraseDummy('Happy Vegan Day', 'Default', 'Greeting', 'Vegan'),
            $p003q =  new PhraseDummy('A wonderfull day to you', 'Default', 'Greeting', 'Flowery'),
            $p003r =  new PhraseDummy('Whalalalalaala', 'Default', 'Greeting', 'Crazy'),
            $p003s =  new PhraseDummy('Hello', 'Default', 'Greeting', 'Normal', 1),

            $p004 =  new PhraseDummy('How are you?', 'Default', 'Question', 'Normal'),
            $p004a =  new PhraseDummy('How are you doing?', 'Default', 'Question', 'Normal'),
            $p004b =  new PhraseDummy('How do you do?', 'Default', 'Question', 'Informal'),
            $p004c =  new PhraseDummy('How\'s everything?', 'Default', 'Question', 'Informal'),
            $p004d =  new PhraseDummy('Hope you\'re feeling well today?', 'Default', 'Question', 'Flowery'),
            $p004e =  new PhraseDummy('How do you do?', 'Default', 'Question', 'Formal'),
            $p004f =  new PhraseDummy('Feeling well?', 'Default', 'Question', 'Informal'),
            $p004g =  new PhraseDummy('Hope you\'re feeling bad', 'Default', 'Question', 'Mean'),
            $p004h =  new PhraseDummy('I don\'t care how you are', 'Default', 'Question', 'Mean'),
            $p004i =  new PhraseDummy('Do you feel bad?, I don\'t give a *', 'Default', 'Question', 'Mean'),
            $p004j =  new PhraseDummy('Did you have a pleasant day?', 'Default', 'Question', 'Flowery'),
            $p004k =  new PhraseDummy('How\'s the Duck in the oven?', 'Default', 'Question', 'Crazy'),
            $p004l =  new PhraseDummy('Muhahaha *Evil laugh cough cough* mh how are you?', 'Default', 'Question', 'Crazy'),
            $p004m =  new PhraseDummy('I\'m Vegan, how are you?', 'Default', 'Question', 'Vegan'),
            $p004n =  new PhraseDummy('How\'s your day?', 'Default', 'Question', 'Vegan', 1),

            $p005 =  new PhraseDummy('Good', 'Default', 'Answer', 'Normal'),
            $p005a =  new PhraseDummy('Acceptable', 'Default', 'Answer', 'Mean'),
            $p005b =  new PhraseDummy('Excellent', 'Default', 'Answer', 'Formal'),
            $p005c =  new PhraseDummy('Awesome', 'Default', 'Answer', 'Informal'),
            $p005d =  new PhraseDummy('Great', 'Default', 'Answer', 'Normal'),
            $p005e =  new PhraseDummy('Favorable', 'Default', 'Answer', 'Flowery'),
            $p005f =  new PhraseDummy('Marvelous', 'Default', 'Answer', 'Flowery'),
            $p005g =  new PhraseDummy('Wonderful', 'Default', 'Answer', 'Vegan'),
            $p005h =  new PhraseDummy('Well', 'Default', 'Answer', 'Formal'),
            $p005i =  new PhraseDummy('Satisfactory', 'Default', 'Answer', 'Vegan'),
            $p005j =  new PhraseDummy('Super', 'Default', 'Answer', 'Informal'),
            $p005k =  new PhraseDummy('Prime', 'Default', 'Answer', 'Flowery'),
            $p005l =  new PhraseDummy('Superior', 'Default', 'Answer', 'Mean'),
            $p005m =  new PhraseDummy('Up to snuff', 'Default', 'Answer', 'Crazy'),
            $p005n =  new PhraseDummy('Worthy', 'Default', 'Answer', 'Crazy'),
            $p005o =  new PhraseDummy('Wuff Wuff', 'Default', 'Answer', 'Crazy'),
            $p005p =  new PhraseDummy('Woaw', 'Default', 'Answer', 'Vegan', 1),

            $p006 =  new PhraseDummy('Bad', 'Default', 'Answer', 'Normal'),
            $p006a =  new PhraseDummy('Ok', 'Default', 'Answer', 'Formal'),
            $p006b =  new PhraseDummy('Awful', 'Default', 'Answer', 'Informal'),
            $p006c =  new PhraseDummy('Atrocious', 'Default', 'Answer', 'Informal'),
            $p006d =  new PhraseDummy('Not Good', 'Default', 'Answer', 'Normal'),
            $p006e =  new PhraseDummy('Lousy', 'Default', 'Answer', 'Informal'),
            $p006f =  new PhraseDummy('Rough', 'Default', 'Answer', 'Formal'),
            $p006g =  new PhraseDummy('Bottom out', 'Default', 'Answer', 'Flowery'),
            $p006h =  new PhraseDummy('Inadequate', 'Default', 'Answer', 'Formal'),
            $p006i =  new PhraseDummy('Abominable', 'Default', 'Answer', 'Flowery'),
            $p006j =  new PhraseDummy('None of your business', 'Default', 'Answer', 'Mean'),
            $p006k =  new PhraseDummy('Better then you', 'Default', 'Answer', 'Mean'),
            $p006l =  new PhraseDummy('Imperfect', 'Default', 'Answer', 'Mean'),
            $p006m =  new PhraseDummy('Garbage', 'Default', 'Answer', 'Vegan'),
            $p006n =  new PhraseDummy('Bummer', 'Default', 'Answer', 'Vegan'),
            $p006o =  new PhraseDummy('Grrr', 'Default', 'Answer', 'Crazy'),
            $p006p =  new PhraseDummy('Hisssss', 'Default', 'Answer', 'Crazy', 1),

        );

        $index = 0;
        foreach ($phrases as $key => $phrase) {
            /** @var PhraseDummy $phrase */
            $p = new Phrase();
            $p->setPhrase($phrase->getPhrase());
            /** @var Category $category */
            $category = $this->getReference($phrase->getCategory());
            $p->setCategory($category);
            /** @var PhraseTyp $phraseTyp */
            $phraseTyp = $this->getReference($phrase->getPhraseTyp());
            $p->setPhraseTyp($phraseTyp);
            /** @var PersonalityTyp $personalityTyp */
            $personalityTyp = $this->getReference($phrase->getPersonalityTyp());
            $p->setPersonalityTyp($personalityTyp);

            $manager->persist($p);
            $this->addReference('phrase-'.$key, $p);

            if($phrase->getIndex() === 1) {
                for ($i = $index; $i <= $key; $i++) {
                    $p1 = $this->getReference('phrase-'.$i);

                    for($j = $i +  1; $j <= $key; $j++) {
                        $p2 = $this->getReference('phrase-'.$j);
                        $pta = $this->addNewAlternativeFixture($p1, $p2, $manager);
                        $pta2 = $this->addNewAlternativeFixture($p2, $p1, $manager);
                    }
                }

                $index = $key + 1;
            }
        }

        $manager->flush();

        $categories = array(
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

        $phraseTyps = array(
        'DefaultType',
        'Question',
        'Answer',
        'Statement',
        'Opinion',
        'Insult',
        'Greeting'
        );

        $personalityTyps = array(
        'Normal',
        'Formal',
        'Informal',
        'Flowery',
        'Mean',
        'Crazy',
        'Vegan'
        );
    }

    private function addNewAlternativeFixture($phrase, $alternativePhrase, ObjectManager $manager)
    {
            $pta = new PhraseToAlternative();
            $pta->setPhrase($phrase);
            $pta->setAlternativePhrase($alternativePhrase);

            $manager->persist($pta);
            return $pta;
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
