<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

use ArrayIterator;
use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\ShepherdId;
use Elewant\Webapp\Infrastructure\ProophProjections\HerdListing;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventStore\ActionEventEmitterEventStore;
use Prooph\EventStore\EventStore;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiCommandBase extends WebTestCase
{
    protected EventStore $store;

    /**
     * @var DomainEvent[]
     */
    protected array $recordedEvents = [];

    protected KernelBrowser $client;

    protected function formHerd(ShepherdId $shepherdId, string $name): KernelBrowser
    {
        $payload = [
            'shepherdId' => $shepherdId->toString(),
            'herdName' => $name,
        ];

        return $this->request('POST', '/testapi/commands/form-herd', $payload);
    }

    protected function adoptElePHPant(HerdId $herdId, Breed $breed): KernelBrowser
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'breed' => $breed->toString(),
        ];

        return $this->request('POST', '/testapi/commands/adopt-elephpant', $payload);
    }

    protected function abandonElePHPant(HerdId $herdId, Breed $breed): KernelBrowser
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'breed' => $breed->toString(),
        ];

        return $this->request('POST', '/testapi/commands/abandon-elephpant', $payload);
    }

    protected function renameHerd(HerdId $herdId, string $newHerdName): KernelBrowser
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'newHerdName' => $newHerdName,
        ];

        return $this->request('POST', '/testapi/commands/rename-herd', $payload);
    }

    protected function desireBreed(HerdId $herdId, Breed $breed): KernelBrowser
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'breed' => $breed->toString(),
        ];

        return $this->request('POST', '/testapi/commands/desire-breed', $payload);
    }

    protected function eliminateDesireForBreed(HerdId $herdId, Breed $breed): KernelBrowser
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'breed' => $breed->toString(),
        ];

        return $this->request('POST', '/testapi/commands/eliminate-desire-for-breed', $payload);
    }

    protected function abandonHerd(HerdId $herdId, ShepherdId $shepherdId): KernelBrowser
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'shepherdId' => $shepherdId->toString(),
        ];

        return $this->request('POST', '/testapi/commands/abandon-herd', $payload);
    }

    /**
     * @param string $herdId
     * @return mixed[]|null
     */
    protected function retrieveHerdFromListing(string $herdId): ?array
    {
        $herdListing = $this->getHerdListing($this->client()->getContainer());

        return $herdListing->findById($herdId);
    }

    /**
     * @param string $elePHPantId
     * @return mixed[]|null
     */
    protected function retrieveElePHPantFromListing(string $elePHPantId): ?array
    {
        $herdListing = $this->getHerdListing($this->client()->getContainer());

        return $herdListing->findElePHPantByElePHPantId($elePHPantId);
    }

    /**
     * @param string $herdId
     * @return mixed[]
     */
    protected function retrieveHerdElePHPantsFromListing(string $herdId): array
    {
        $herdListing = $this->getHerdListing($this->client()->getContainer());

        return $herdListing->findElePHPantsByHerdId($herdId);
    }

    /**
     * @param string $herdId
     * @return mixed[]
     */
    protected function retrieveDesiredBreedsFromListing(string $herdId): array
    {
        $herdListing = $this->getHerdListing($this->client()->getContainer());

        return $herdListing->findDesiredBreedsByHerdId($herdId);
    }

    private function getStore(ContainerInterface $container): ActionEventEmitterEventStore
    {
        return $container->get('prooph_event_store.herd_store');
    }

    private function getHerdListing(ContainerInterface $container): HerdListing
    {
        return $container->get(HerdListing::class);
    }

    /**
     * This method can run a projection once.
     *
     * @param string $projectionName
     */
    protected function runProjection(string $projectionName): void
    {
        self::bootKernel();
        $container = self::$container;

        $projectionManager = $container->get(
            'prooph_event_store.projection_manager.elewant_projection_manager'
        );
        $projectionsLocator = $container->get(
            'prooph_event_store.projections_locator'
        );
        $projectionReadModelLocator = $container->get(
            'prooph_event_store.projection_read_models_locator'
        );

        $projection = $projectionsLocator->get($projectionName);

        if ($projection instanceof ReadModelProjection) {
            if (!$projectionReadModelLocator->has($projectionName)) {
                throw new RuntimeException(sprintf('ReadModel for "%s" not found', $projectionName));
            }

            $readModel = $projectionReadModelLocator->get($projectionName);
            $projector = $projectionManager->createReadModelProjection($projectionName, $readModel);
        } else {
            $projector = $projectionManager->createProjection($projectionName);
        }

        $projector = $projection->project($projector);
        $projector->run(false);
    }

    /**
     * @param string  $type
     * @param string  $url
     * @param mixed[] $payload
     * @return KernelBrowser
     */
    private function request(string $type, string $url, array $payload): KernelBrowser
    {
        $client = $this->client();
        $client->request(
            $type,
            $url,
            [],
            [],
            [],
            json_encode($payload)
        );

        return $client;
    }

    private function client(): KernelBrowser
    {
        $client = static::createClient();

        if ($client->getContainer() === null) {
            throw new RuntimeException('Kernel has been shutdown or not started yet.');
        }

        $this->getStore($client->getContainer())->attach(
            'appendTo',
            function (ActionEvent $event): void {
                foreach ($event->getParam('streamEvents', new ArrayIterator()) as $recordedEvent) {
                    $this->recordedEvents[] = $recordedEvent;
                }
            }
        );

        return $client;
    }
}
