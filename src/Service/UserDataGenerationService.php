<?php

namespace DoctrineFixtures\DataGenerationBundle\Service;

use Doctrine\Persistence\Mapping\ClassMetadata;

class UserDataGenerationService
{
    /**
     * @return string[]
     */
    public function generate(ClassMetadata $class, int $count = 100): array
    {
        $tableName = $class->table['name'];

        $queries = [];
        for ($i = 0; $i < $count; $i++) {
            $email = uniqid() . '@example.com';
            $password = uniqid();
            $roles = json_encode(['ROLE_USER']);

            $queries[] = $this->generateQuery($tableName, $email, $password, $roles);
        }

        return $queries;
    }

    private function generateQuery(string $tableName, string $email, string $password, string $roles): string
    {
        return "INSERT INTO $tableName (email, password, roles) VALUES ('$email', '$password', '$roles');";
    }
}
