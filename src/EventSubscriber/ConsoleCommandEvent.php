<?php

namespace DoctrineFixtures\DataGenerationBundle\EventSubscriber;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent as SymfonyConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ConsoleCommandEvent implements EventSubscriberInterface
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {  
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
            $this->dispatcher->addListener(ConsoleEvents::TERMINATE, function (ConsoleTerminateEvent $event) {
                echo 'WILL TRIGGER AFTER TERMINATE EVENT';
            });
        }
    }
}
