<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $anonymous = new User();
        $anonymous->setUsername('Anonymous');
        $anonymous->setEmail('anonymous@todo.com');
        $anonymous->setPassword($this->encoder->encodePassword($anonymous, 'anonymous'));
        $anonymous->setRole('ROLE_USER');
        $manager->persist($anonymous);
        $manager->flush();
    }
}
