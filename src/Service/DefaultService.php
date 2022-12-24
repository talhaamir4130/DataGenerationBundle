<?php

namespace DoctrineFixtures\DataGenerationBundle\Service;

use Symfony\Component\Yaml\Yaml;

class DefaultService
{
    public function __construct(
        private MainService $mainService
    ) {  
    }

    // THIS IS FOR TESTING PURPOSE ONLY
    public function testService(): string
    {
        $yamlValues = Yaml::parseFile(getcwd().'/../fixtures-config.yml');
        $this->mainService->generateData($yamlValues);

        return 'Hello World!';
    }
}
