<?php

namespace DoctrineFixtures\DataGenerationBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\ClassMetadata;

class UserDataGenerationService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {  
    }

    /**
     * @param mixed[] $userConfig
     */
    public function generate(array $userConfig): void
    {
        $className = $userConfig['namespace'];

        for ($i = 0; $i < $userConfig['rows']; $i++) {
            $email = uniqid() . '@example.com';
            $password = uniqid();

            $user = new $className();
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setRoles($this->getRandomRoles($userConfig['defined_roles']));

            $this->entityManager->persist($user);
        }
    }

    /**
     * @param string[] $definedRoles
     * 
     * @return string[]
     */
    private function getRandomRoles(array $definedRoles): array
    {
        $totalRoles = count($definedRoles);
        $randomCount = rand(1, $totalRoles);
        $roles = [];
        for ($i=0; $i < $randomCount; $i++) { 
            $roles[] = $definedRoles[rand(0, $totalRoles - 1)];
        }

        return $roles;
    }
}
