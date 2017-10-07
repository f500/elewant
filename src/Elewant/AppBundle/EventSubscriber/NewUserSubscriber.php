<?php

declare(strict_types=1);

namespace Elewant\AppBundle\EventSubscriber;

use Elewant\AppBundle\Repository\HerdRepository;
use Elewant\Herding\Model\Commands\FormHerd;
use Elewant\UserBundle\Event\UserHasRegistered;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class NewUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var HerdRepository
     */
    private $herdRepository;

    public function __construct(HerdRepository $herdRepository, CommandBus $commandBus)
    {
        $this->commandBus     = $commandBus;
        $this->herdRepository = $herdRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserHasRegistered::NAME => 'formNewHerdWhen',
        ];
    }

    public function formNewHerdWhen(UserHasRegistered $event)
    {
        $user = $event->user();

        if (!$this->herdRepository->findOneByShepherdId($user->shepherdId())) {
            $command = FormHerd::forShepherd($user->shepherdId()->toString(), $user->displayName());
            $this->commandBus->dispatch($command);
        }
    }
}
