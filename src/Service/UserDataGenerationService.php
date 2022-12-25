<?php

namespace DoctrineFixtures\DataGenerationBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class UserDataGenerationService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {  
    }

    /**
     * @param mixed[] $userConfig
     * 
     * @return mixed[]
     */
    public function generate(array $userConfig): array
    {
        $users = [];
        $className = $userConfig['namespace'];

        for ($i = 0; $i < $userConfig['rows']; $i++) {
            $email = uniqid() . '@example.com';
            $password = uniqid();

            $user = new $className();
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setRoles($this->getRandomRoles($userConfig['defined_roles']));

            $users[] = $user;
            $this->entityManager->persist($user);
        }

        return $users;
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
            $role = $definedRoles[rand(0, $totalRoles - 1)];
            if (!in_array($role, $roles)) {
                $roles[] = $role;
            }
        }

        return $roles;
    }
}
