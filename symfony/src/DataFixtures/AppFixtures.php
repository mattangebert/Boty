<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        // create 3 user
        for ($i = 0; $i < 3; $i++) {
            $user =  new User();
            $user->setEmail('user '.$i.'@test.de');
            $user->setPassword('changeme123');
            $user->setName('user '.$i);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
