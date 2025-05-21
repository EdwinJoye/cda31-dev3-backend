<?php

namespace App\DataFixtures;

use App\Entity\Collaborator;
use Collator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Serializer\SerializerInterface;

class CollaboratorFixtures extends Fixture
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    public function load(ObjectManager $manager): void
    {
        $json = file_get_contents('../data/users.json');
        $users = json_decode($json, true);

        foreach($users as $user){
            $collaborator = (new Collaborator())
                ->setId((int) $user["id"])
                ->setFirstname($user["firstname"])
                ->setLastname($user["lastname"])
                ->setEmail($user["email"])
                ->setPassword($user["password"])
                ->setPhone($user["phone"] ?? null)
                ->setBirthdate(isset($user["birthdate"]) && $user["birthdate"] ? new \DateTime($user["birthdate"]) : null)
                ->setCity($user["city"] ?? null)
                ->setCountry($user["country"] ?? null)
                ->setPhoto($user["photo"] ?? null)
                ->setCategory($user["category"] ?? null)
                ->setIsAdmin($user["isAdmin"] ?? false)
            ;
            $manager->persist($collaborator);
    
        }
        $manager->flush();
    }
}
