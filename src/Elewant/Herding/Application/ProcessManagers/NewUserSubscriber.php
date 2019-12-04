<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\ProcessManagers;

use Bundles\UserBundle\Event\UserHasRegistered;
use Doctrine\ORM\NonUniqueResultException;
use Elewant\Herding\Application\Commands\FormHerd;
use Elewant\Webapp\DomainModel\Herding\HerdRepository;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * This is a process-manager that listens to the UserHasRegistered event,
 * originating from Bundles\UserBundle, to ensure this new shepherd forms
 * its (first) herd.
 */
final class NewUserSubscriber implements EventSubscriberInterface
{
    /**
     * @todo In the context of CQRS, we are using a query-side repository here in a command-side process-manager.
     * @todo The command-side mustn't rely on the query-side, so we should fix this!
     * @todo Because we don't have a Shepherd aggregate, just a ShepherdId, we cannot look it up right now.
     * @todo I suggest we create a Shepherd and ShepherdCollection, so we can use the latter here.
     * @todo We can then do: UserHasRegistered -> GiveBirthToShepherd -> ShepherdWasBorn -> FormHerd.
     * @var HerdRepository
     */
    private $herdRepository;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(HerdRepository $herdRepository, CommandBus $commandBus)
    {
        $this->herdRepository = $herdRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @return mixed[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserHasRegistered::CLASS => 'formNewHerdWhen',
        ];
    }

    /**
     * @param UserHasRegistered $event
     * @throws NonUniqueResultException
     */
    public function formNewHerdWhen(UserHasRegistered $event): void
    {
        $user = $event->user();

        if ($this->herdRepository->findOneByShepherdId($user->shepherdId())) {
            return;
        }

        $command = FormHerd::forShepherd($user->shepherdId()->toString(), $user->displayName());
        $this->commandBus->dispatch($command);
    }
}
