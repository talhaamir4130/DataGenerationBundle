<?php

namespace DoctrineFixtures\DataGenerationBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineFixtures\DataGenerationBundle\Service\UserDataGenerationService;
use ReflectionClass;

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
        $users = $this->userDataGenerationSrv->generate($yamlValues['user']);

        // Insert other entities
        $entities = [
            $yamlValues['user']['namespace'] => $users
        ];
        foreach ($yamlValues['entities'] as $entityConfig) {
            $namespace = $entityConfig['namespace'];
            $construct = [];
            if (isset($entityConfig['construct'])) {
                $construct =$entityConfig['construct'];
            }

            for ($i=0; $i < $entityConfig['rows']; $i++) {
                $class = $this->createClass($namespace, $construct, $entities);
                $entities[$namespace][] = $class;
                $this->entityManager->persist($class);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param mixed[] $construct
     * @param mixed[] $entities
     * 
     * @return mixed
     */
    private function createClass(string $namespace, array $construct, array $entities): mixed
    {
        if (empty($construct)) {
            $class = new $namespace();

            return $class;
        }

        $reflection = new ReflectionClass($namespace);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();
        $args = [];

        foreach ($parameters as $parameter) {
            $parameterType = $parameter->getType()->getName();
            $name = $parameter->getName();
            if (class_exists($parameterType)) {
                $args[$name] = $entities[$parameterType][rand(0, count($entities[$parameterType]) - 1)];

                continue;
            }

            // TODO: CHECK FOR OTHER DATA TYPES
            // $defaultValue = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
            // $args[$name] = $defaultValue;
        }

        $class = $reflection->newInstanceArgs($args);

        return $class;
    }
}
