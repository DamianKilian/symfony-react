<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }
    

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'test'
        );
        $user->setPassword($hashedPassword);
        $user->setEmail('test@test.test');
        $user->setAuthToken('340625658077554617180480684567');
        $manager->persist($user);

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName('product '.$i);
            $product->setNum(mt_rand(10, 100));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
