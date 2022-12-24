<?php

namespace DoctrineFixtures\DataGenerationBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineFixtures\DataGenerationBundle\Service\UserDataGenerationService;

class MainService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserDataGenerationService $userDataGenerationSrv
    ) {  
    }

    public function generateData(): void
    {
        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $queries = [];
        foreach ($metaData as $class) {
            if ($class->getName() == 'App\Entity\User') {
                $queries = array_merge($queries, $this->userDataGenerationSrv->generate($class));
            }
        }

        $this->insertData($queries);
    }

    private function insertData(array $queries): void
    {
        $connection = $this->entityManager->getConnection();
        foreach ($queries as $query) {
            $statement = $connection->prepare($query);
            $statement->executeQuery();
        }
    }
}
