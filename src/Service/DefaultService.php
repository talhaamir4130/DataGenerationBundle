<?php

namespace DoctrineFixtures\DataGenerationBundle\Service;

class DefaultService
{
    public function __construct(
        private MainService $mainService
    ) {  
    }

    // THIS IS FOR TESTING PURPOSE ONLY
    public function testService(): string
    {
        $this->mainService->generateData();

        return 'Hello World!';
    }
}
