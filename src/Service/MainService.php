<?php

namespace DoctrineFixtures\DataGenerationBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineFixtures\DataGenerationBundle\Service\UserDataGenerationService;
use Symfony\Component\Yaml\Yaml;

class MainService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserDataGenerationService $userDataGenerationSrv
    ) {  
    }

    /**
     * @param mixed[] $yamlValues
     */
    public function generateData(array $yamlValues): void
    {
        // Insert Users
        $this->userDataGenerationSrv->generate($yamlValues['user']);

        // $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        // foreach ($metaData as $class) {
        // }

        $this->entityManager->flush();
    }
}
