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
                for ($i = $index; $i < $key; $i++) {
                    $pta = new PhraseToAlternative();
                    $pta->setPhrase($p);
                    $phraseAlt = $this->getReference('phrase-'.$i);
                    $pta->setAlternativePhrase($phraseAlt);

                    $pta2 =  new PhraseToAlternative();
                    $pta2->setAlternativePhrase($p);
                    $phraseAlt = $this->getReference('phrase-'.$i);
                    $pta2->setPhrase($phraseAlt);

                    $manager->persist($pta);
                    $manager->persist($pta2);
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
        'Insult'

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


    private function createPhraseToAlternativeFixtures(ObjectManager $manager)
    {
        $manager->flush();
    }

    private function createPhraseToReplyFixtures(ObjectManager $manager)
    {
        $manager->flush();
    }
}
