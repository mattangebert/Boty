<?php

namespace App\DataFixtures;

use App\Entity\Bot;
use App\Entity\Category;
use App\Entity\Personality;
use App\Entity\PersonalityTyp;
use App\Entity\Phrase;
use App\Entity\PhraseToAlternative;
use App\Entity\PhraseToReply;
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
        'Vegan',
        'Yoda'
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
        $this->createBotYoda($manager);
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

    private function createBotYoda(ObjectManager $manager)
    {
        $personality =  new Personality();

        /** @var PersonalityTyp $refOne */
        $refOne = $this->getReference('Yoda');
        $personality->setName('Yoda');
        $personality->setPersonalityTypOne($refOne);
        $manager->persist($personality);


        $yoda =  new Bot();

        $yoda->setName('Yoda');
        $yoda->setEnabled(true);
        $yoda->setCreatedAt(new \DateTime());
        $yoda->setPersonality($personality);

        $manager->persist($yoda);
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
            new PhraseDummy( 'Yes', 'Default', 'Answer', 'Normal'),
            new PhraseDummy('Certainly', 'Default', 'Answer', 'Formal'),
            new PhraseDummy('Ok', 'Default', 'Answer', 'Informal'),
            new PhraseDummy('Surely', 'Default', 'Answer', 'Flowery'),
            new PhraseDummy('Obviously', 'Default', 'Answer', 'Mean'),
            new PhraseDummy( 'Uh-huh', 'Default', 'Answer', 'Crazy'),
            new PhraseDummy( 'Yes I\'m a Vegan', 'Default', 'Answer', 'Vegan'),
            new PhraseDummy('Yep', 'Default', 'Answer', 'Informal'),
            new PhraseDummy('Sure', 'Default', 'Answer', 'Informal'),
            new PhraseDummy('You bet', 'Default', 'Answer', 'Informal'),
            new PhraseDummy( 'Okay', 'Default', 'Answer', 'Normal'),
            new PhraseDummy( 'Okie dokie', 'Default', 'Answer', 'Normal'),
            new PhraseDummy( 'Alright', 'Default', 'Answer', 'Normal'),
            new PhraseDummy( 'Sounds good', 'Default', 'Answer', 'Normal'),
            new PhraseDummy('Definitly', 'Default', 'Answer', 'Formal'),
            new PhraseDummy('Gladly', 'Default', 'Answer', 'Formal'),
            new PhraseDummy('Absolutely', 'Default', 'Answer', 'Formal'),
            new PhraseDummy('Indeed', 'Default', 'Answer', 'Formal'),
            new PhraseDummy('Undoubtedly', 'Default', 'Answer', 'Flowery'),
            new PhraseDummy('Indubitably', 'Default', 'Answer', 'Flowery'),
            new PhraseDummy('Fine', 'Default', 'Answer', 'Mean'),
            new PhraseDummy('Noooooooo', 'Default', 'Answer', 'Mean'),
            new PhraseDummy( 'Forsooth', 'Default', 'Answer', 'Crazy'),
            new PhraseDummy( 'Verily', 'Default', 'Answer', 'Crazy'),
            new PhraseDummy( 'Yes the answer is', 'Default', 'Answer', 'Yoda'),
            new PhraseDummy( 'Yes Yes, hmmm', 'Default', 'Answer', 'Yoda'),
            new PhraseDummy( 'Herh herh herh', 'Default', 'Answer', 'Yoda'),
            new PhraseDummy( 'Yeay', 'Default', 'Answer', 'Vegan', 'pack-end'),

            new PhraseDummy('No', 'Default', 'Answer', 'Normal'),
            new PhraseDummy( 'Unfortunately not', 'Default', 'Answer', 'Formal'),
            new PhraseDummy( 'Nope', 'Default', 'Answer', 'Informal'),
            new PhraseDummy('That doesn\'t sound right', 'Default', 'Answer', 'Flowery'),
            new PhraseDummy('Fuck Off', 'Default', 'Answer', 'Mean'),
            new PhraseDummy('I\'m slammed', 'Default', 'Answer', 'Crazy'),
            new PhraseDummy( 'It\'s not my thing', 'Default', 'Answer', 'Vegan'),
            new PhraseDummy('Look! Squirrel!', 'Default', 'Answer', 'Crazy'),
            new PhraseDummy( 'Heck No', 'Default', 'Answer', 'Informal'),
            new PhraseDummy('No Way', 'Default', 'Answer', 'Normal'),
            new PhraseDummy( 'Regrettably not', 'Default', 'Answer', 'Formal'),
            new PhraseDummy( 'It\'s a Wednesday. I have a "No on Wednesday" policy', 'Default', 'Answer', 'Vegan'),
            new PhraseDummy('Ask me in a year', 'Default', 'Answer', 'Mean'),
            new PhraseDummy('If only i could say yes', 'Default', 'Answer', 'Formal'),
            new PhraseDummy('I’m at the end of my rope right now so have to take a raincheck', 'Default', 'Answer', 'Flowery'),
            new PhraseDummy('I’m in a season of NO', 'Default', 'Answer', 'Mean'),
            new PhraseDummy('NoNoNoNoNoNo', 'Default', 'Answer', 'Crazy'),
            new PhraseDummy( 'My body says yes, but my heart says No', 'Default', 'Answer', 'Flowery'),
            new PhraseDummy( 'To answer, I no have', 'Default', 'Answer', 'Yoda'),
            new PhraseDummy( 'No Yes, hmmm', 'Default', 'Answer', 'Yoda'),
            new PhraseDummy( 'To disagree I have', 'Default', 'Answer', 'Yoda'),
            new PhraseDummy( 'N to the O.', 'Default', 'Answer', 'Flowery', 'pack-end'),

            new PhraseDummy('How are you', 'Default', 'Greeting', 'Normal'),
            new PhraseDummy('Hey Hey', 'Default', 'Greeting', 'Informal'),
            new PhraseDummy('What\'s up', 'Default', 'Greeting', 'Informal'),
            new PhraseDummy('Good to see you', 'Default', 'Greeting', 'Normal'),
            new PhraseDummy('Nice to see you', 'Default', 'Greeting', 'Formal'),
            new PhraseDummy('Long time no see', 'Default', 'Greeting', 'Normal'),
            new PhraseDummy('It\'s been a while', 'Default', 'Greeting', 'Informal'),
            new PhraseDummy('Good morning', 'Default', 'Greeting', 'Formal'),
            new PhraseDummy('Pleased to meet you', 'Default', 'Greeting', 'Formal'),
            new PhraseDummy('Yo', 'Default', 'Greeting', 'Informal'),
            new PhraseDummy('Whazzup?', 'Default', 'Greeting', 'Crazy'),
            new PhraseDummy(' G’day mate', 'Default', 'Greeting', 'Flowery'),
            new PhraseDummy('Wish you a horrible day', 'Default', 'Greeting', 'Mean'),
            new PhraseDummy('Why are you here?', 'Default', 'Greeting', 'Mean'),
            new PhraseDummy('Fucking Asshole', 'Default', 'Greeting', 'Mean'),
            new PhraseDummy('Holy Cow', 'Default', 'Greeting', 'Vegan'),
            new PhraseDummy('Happy Vegan Day', 'Default', 'Greeting', 'Vegan'),
            new PhraseDummy('A wonderfull day to you', 'Default', 'Greeting', 'Flowery'),
            new PhraseDummy('Whalalalalaala', 'Default', 'Greeting', 'Crazy'),
            new PhraseDummy('May the Force be with you', 'Default', 'Greeting', 'Yoda'),
            new PhraseDummy('To see you good.  Yeesssssss', 'Default', 'Greeting', 'Yoda'),
            new PhraseDummy('To meet you pleased. I am', 'Default', 'Greeting', 'Yoda'),
            new PhraseDummy('Hello', 'Default', 'Greeting', 'Normal', 'pack-end'),

            new PhraseDummy('How are you?', 'Default', 'Question', 'Normal', 'q1'),
            new PhraseDummy('How are you doing?', 'Default', 'Question', 'Normal', 'q1'),
            new PhraseDummy('How do you do?', 'Default', 'Question', 'Informal', 'q1'),
            new PhraseDummy('How\'s everything?', 'Default', 'Question', 'Informal'),
            new PhraseDummy('Hope you\'re feeling well today?', 'Default', 'Question', 'Flowery', 'q1'),
            new PhraseDummy('How do you do?', 'Default', 'Question', 'Formal', 'q1'),
            new PhraseDummy('Feeling well?', 'Default', 'Question', 'Informal', 'q1'),
            new PhraseDummy('Hope you\'re feeling bad', 'Default', 'Question', 'Mean', 'q1'),
            new PhraseDummy('I don\'t care how you are', 'Default', 'Question', 'Mean', 'q1'),
            new PhraseDummy('Do you feel bad?, I don\'t give a *', 'Default', 'Question', 'Mean', 'q1'),
            new PhraseDummy('Did you have a pleasant day?', 'Default', 'Question', 'Flowery', 'q1'),
            new PhraseDummy('How\'s the Duck in the oven?', 'Default', 'Question', 'Crazy', 'q1'),
            new PhraseDummy('Muhahaha *Evil laugh cough cough* mh how are you?', 'Default', 'Question', 'Crazy', 'q1'),
            new PhraseDummy('I\'m Vegan, how are you?', 'Default', 'Question', 'Vegan', 'q1'),
            new PhraseDummy('Hope, feeling well today, you are, hmm?  Hmmmmmm.', 'Default', 'Question', 'Yoda', 'q1'),
            new PhraseDummy('Pleasant day, did you have, yeees?', 'Default', 'Question', 'Yoda', 'q1'),
            new PhraseDummy('How doing are you, hmm?  Yes, hmmm.', 'Default', 'Question', 'Yoda', 'q1'),
            new PhraseDummy('How\'s your day?', 'Default', 'Question', 'Vegan', 'pack-end q1'),

            new PhraseDummy('Good', 'Default', 'Answer', 'Normal', 'a1'),
            new PhraseDummy('Acceptable', 'Default', 'Answer', 'Mean', 'a1'),
            new PhraseDummy('Excellent', 'Default', 'Answer', 'Formal', 'a1'),
            new PhraseDummy('Awesome', 'Default', 'Answer', 'Informal', 'a1'),
            new PhraseDummy('Great', 'Default', 'Answer', 'Normal', 'a1'),
            new PhraseDummy('Favorable', 'Default', 'Answer', 'Flowery', 'a1'),
            new PhraseDummy('Marvelous', 'Default', 'Answer', 'Flowery', 'a1'),
            new PhraseDummy('Wonderful', 'Default', 'Answer', 'Vegan', 'a1'),
            new PhraseDummy('Well', 'Default', 'Answer', 'Formal', 'a1'),
            new PhraseDummy('Satisfactory', 'Default', 'Answer', 'Vegan', 'a1'),
            new PhraseDummy('Super', 'Default', 'Answer', 'Informal', 'a1'),
            new PhraseDummy('Prime', 'Default', 'Answer', 'Flowery', 'a1'),
            new PhraseDummy('Superior', 'Default', 'Answer', 'Mean', 'a1'),
            new PhraseDummy('Up to snuff', 'Default', 'Answer', 'Crazy', 'a1'),
            new PhraseDummy('Worthy', 'Default', 'Answer', 'Crazy', 'a1'),
            new PhraseDummy('Wuff Wuff', 'Default', 'Answer', 'Crazy', 'a1'),
            new PhraseDummy('Feel the force!', 'Default', 'Answer', 'Yoda', 'a1'),
            new PhraseDummy('Very Good. Yes, hmmm', 'Default', 'Answer', 'Yoda', 'a1'),
            new PhraseDummy('Many of the truths that we cling to depend on our point of view', 'Default', 'Answer', 'Yoda', 'a1'),
            new PhraseDummy('Woaw', 'Default', 'Answer', 'Vegan', 'pack-end a1'),

            new PhraseDummy('Bad', 'Default', 'Answer', 'Normal', 'a1'),
            new PhraseDummy('Ok', 'Default', 'Answer', 'Formal', 'a1'),
            new PhraseDummy('Awful', 'Default', 'Answer', 'Informal', 'a1'),
            new PhraseDummy('Atrocious', 'Default', 'Answer', 'Informal', 'a1'),
            new PhraseDummy('Not Good', 'Default', 'Answer', 'Normal', 'a1'),
            new PhraseDummy('Lousy', 'Default', 'Answer', 'Informal', 'a1'),
            new PhraseDummy('Rough', 'Default', 'Answer', 'Formal', 'a1'),
            new PhraseDummy('Bottom out', 'Default', 'Answer', 'Flowery', 'a1'),
            new PhraseDummy('Inadequate', 'Default', 'Answer', 'Formal', 'a1'),
            new PhraseDummy('Abominable', 'Default', 'Answer', 'Flowery', 'a1'),
            new PhraseDummy('None of your business', 'Default', 'Answer', 'Mean', 'a1'),
            new PhraseDummy('Better then you', 'Default', 'Answer', 'Mean', 'a1'),
            new PhraseDummy('Imperfect', 'Default', 'Answer', 'Mean', 'a1'),
            new PhraseDummy('Garbage', 'Default', 'Answer', 'Vegan', 'a1'),
            new PhraseDummy('Bummer', 'Default', 'Answer', 'Vegan', 'a1'),
            new PhraseDummy('Grrr', 'Default', 'Answer', 'Crazy', 'a1'),
            new PhraseDummy('Not if anything to say about it I have', 'Default', 'Answer', 'Yoda', 'a1'),
            new PhraseDummy('Clear your mind must be, if you are to find the villains behind this plot', 'Default', 'Answer', 'Yoda', 'a1'),
            new PhraseDummy('That is why you fail', 'Default', 'Answer', 'Yoda', 'a1'),
            new PhraseDummy('Hisssss', 'Default', 'Answer', 'Crazy', 'pack-end a1'),

            new PhraseDummy('What is your favourite Movie?', 'Movie', 'Question', 'Normal', 'q2'),
            new PhraseDummy('What Movie do you like the most?', 'Movie', 'Question', 'Normal', 'q2'),
            new PhraseDummy('What is your most liked Movie?', 'Movie', 'Question', 'Normal', 'q2'),
            new PhraseDummy('Do you have a favourite Movie?', 'Movie', 'Question', 'Informal', 'q2'),
            new PhraseDummy('What Movie do you love?', 'Movie', 'Question', 'Informal', 'q2'),
            new PhraseDummy('Tell me your favourite Movie?', 'Movie', 'Question', 'Informal', 'q2'),
            new PhraseDummy('Which Movie would you call your favourite?', 'Movie', 'Question', 'Formal', 'q2'),
            new PhraseDummy('Which Movie is your personal favourite?', 'Movie', 'Question', 'Formal', 'q2'),
            new PhraseDummy('What would you say is the Movie you treasured the most?', 'Movie', 'Question', 'Flowery', 'q2'),
            new PhraseDummy('Which is the Movie you choose as your best-loved?', 'Movie', 'Question', 'Flowery', 'q2'),
            new PhraseDummy('Your favourite Movie? Let\' me Guess you still read children book only', 'Movie', 'Question', 'Mean', 'q2'),
            new PhraseDummy('Not that i care, but im forced to ask: What\'s your favourite Movie?', 'Movie', 'Question', 'Mean', 'q2'),
            new PhraseDummy('You gonna tell a Idiot like you got a good favourite Movie?', 'Movie', 'Question', 'Mean', 'q2'),
            new PhraseDummy('Moooooouvieeee?', 'Movie', 'Question', 'Crazy', 'q2'),
            new PhraseDummy('Got a favourite Movie? Tell me Tell me', 'Movie', 'Question', 'Crazy', 'q2'),
            new PhraseDummy('favourite Movie where no Animals were hurt?', 'Movie', 'Question', 'Vegan', 'q2'),
            new PhraseDummy('Your favourite movie, what is, hmm?', 'Movie', 'Question', 'Yoda', 'q2'),
            new PhraseDummy('Favourite movie, you have, hmm?  Hmmmmmm', 'Movie', 'Question', 'Yoda', 'pack-end q2'),

            new PhraseDummy('Men in Black', 'Movie', 'Answer', 'Normal', 'a2'),
            new PhraseDummy('Batman', 'Movie', 'Answer', 'Normal', 'a2'),
            new PhraseDummy('Captain Marvel', 'Movie', 'Answer', 'Normal', 'a2'),
            new PhraseDummy('English for dummies', 'Movie', 'Answer', 'Formal', 'a2'),
            new PhraseDummy('The diary of anne frank', 'Movie', 'Answer', 'Formal', 'a2'),
            new PhraseDummy('Freedom Writers', 'Movie', 'Answer', 'Formal', 'a2'),
            new PhraseDummy('Inglourious Basterds', 'Movie', 'Answer', 'Informal', 'a2'),
            new PhraseDummy('Last Summer', 'Movie', 'Answer', 'Informal', 'a2'),
            new PhraseDummy('American Pie', 'Movie', 'Answer', 'Informal', 'a2'),
            new PhraseDummy('Driving Miss Daisy', 'Movie', 'Answer', 'Flowery', 'a2'),
            new PhraseDummy('The Wizard of Oz', 'Movie', 'Answer', 'Flowery', 'a2'),
            new PhraseDummy('Alice in Wonderland', 'Movie', 'Answer', 'Flowery', 'a2'),
            new PhraseDummy('Daddy doesnt like you extended Version', 'Movie', 'Answer', 'Mean', 'a2'),
            new PhraseDummy('Your Mom knows why', 'Movie', 'Answer', 'Mean', 'a2'),
            new PhraseDummy('Fuckface Second Part your Mother', 'Movie', 'Answer', 'Mean', 'a2'),
            new PhraseDummy('The Sixth Sense', 'Movie', 'Answer', 'Crazy', 'a2'),
            new PhraseDummy('Shutter Island', 'Movie', 'Answer', 'Crazy', 'a2'),
            new PhraseDummy('Inception', 'Movie', 'Answer', 'Crazy', 'a2'),
            new PhraseDummy('Vegucated', 'Movie', 'Answer', 'Vegan', 'a2'),
            new PhraseDummy('Speciesism', 'Movie', 'Answer', 'Vegan', 'a2'),
            new PhraseDummy('Live and Let Live', 'Movie', 'Answer', 'Vegan', 'a2'),
            new PhraseDummy('Revenge on the Sith', 'Movie', 'Answer', 'Yoda', 'a2'),
            new PhraseDummy('Attack of the Clones', 'Movie', 'Answer', 'Yoda', 'a2'),
            new PhraseDummy('Return of the Jedi', 'Movie', 'Answer', 'Yoda', 'pack-end a2'),

            new PhraseDummy('Fear is the path to the dark side. Fear leads to anger. Anger leads to hate. Hate leads to suffering', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Powerful you have become, the dark side I sense in you', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('PATIENCE YOU MUST HAVE my young padawan', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Once you start down the dark path, forever will it dominate your destiny, consume you it will', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('You must unlearn what you have learned', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('When you look at the dark side, careful you must be. For the dark side looks back', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Do or do not. There is no try', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Ohhh. Great warrior.Wars not make one great', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Always two there are, no more, no less. A master and an apprentice', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Difficult to see. Always in motion is the future', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('You think Yoda stops teaching, just because his student does not want to hear? A teacher Yoda is. Yoda teaches like drunkards drink, like killers kill', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Foreplay, cuddling – a Jedi craves not these things', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Lost a planet Master Obi-Wan has. How embarrassing', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Mudhole? Slimy? My home this is!', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('When nine hundred years old you reach, look as good, you will not, hmmmm?', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Much to learn you still have…young padawan.” … “This is just the beginning!', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('If no mistake have you made, yet losing you are … a different game you should play', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('To answer power with power, the Jedi way this is not. In this war, a danger there is, of losing who we are', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Clear your mind must be, if you are to find the villains behind this plot', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('To be Jedi is to face the truth, and choose. Give off light, or darkness, Padawan. Be a candle, or the night', 'Random', 'Statement', 'Yoda'),
            new PhraseDummy('Hmm. In the end, cowards are those who follow the dark side', 'Random', 'Statement', 'Yoda', 'pack-end'),
        );

        $index = 0;
        $packs = [
            'q1' => $q1 = array(),
            'a1' => $q1 = array(),
            'q2' => $q1 = array(),
            'a2' => $q1 = array(),
        ];


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



            foreach ($packs as $key2 => &$pack) {
                if(strpos($phrase->getIndex(), $key2) !== false) {
                    $pack[] = 'phrase-'.$key;
                }

            }

            // set Phrase to Alternative
            if(strpos($phrase->getIndex(), 'pack-end') !== false) {
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


        $packKeys = array_keys($packs);

        for ($k = 0; $k < count($packKeys); $k+= 2) {
            foreach ($packs[$packKeys[$k]] as $pKey) {
                /** @var Phrase $p */
                $p =  $this->getReference($pKey);

                foreach ($packs[$packKeys[$k + 1]] as $rKey) {
                    /** @var Phrase $reply */
                    $reply = $this->getReference($rKey);

                    $rtp = new PhraseToReply();
                    $rtp->setPhrase($p);
                    $rtp->setReplyPhrase($reply);

                    $manager->persist($rtp);
                }
            }
        }

        $manager->flush();
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
