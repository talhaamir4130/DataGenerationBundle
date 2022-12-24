<?php

namespace DoctrineFixtures\DataGenerationBundle\EventSubscriber;

use DoctrineFixtures\DataGenerationBundle\Service\MainService;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent as SymfonyConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Yaml\Yaml;

class ConsoleCommandEvent implements EventSubscriberInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private MainService $mainService
    ) {  
    }

    public static function getSubscribedEvents()
    {
        return [
            'console.command' => 'onConsoleCommand',
        ];
    }

    public function onConsoleCommand(SymfonyConsoleCommandEvent $event): void
    {
        $command = $event->getCommand();

        if ($command->getName() === 'doctrine:fixtures:load') {
            $this->dispatcher->addListener(ConsoleEvents::TERMINATE, function () {
                try {
                    $yamlValues = Yaml::parseFile(getcwd().'/fixtures-config.yml');
                } catch (\Exception $e) {
                    throw new \Exception('fixtures-config.yml file not found!');
                }

                $this->mainService->generateData($yamlValues);

                echo PHP_EOL . 'Data generated!' . PHP_EOL;
            });
        }
    }
}
