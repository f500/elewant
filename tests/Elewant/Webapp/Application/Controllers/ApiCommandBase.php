<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\ShepherdId;
use Elewant\Webapp\Infrastructure\ProophProjections\HerdListing;
use Prooph\Bundle\EventStore\Projection\Projection;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventStore\ActionEventEmitterEventStore;
use Prooph\EventStore\EventStore;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiCommandBase extends WebTestCase
{
    /** @var Client */
    protected $client;

    /** @var EventStore */
    protected $store;

    /** @var DomainEvent[] */
    protected $recordedEvents = [];

    protected function formHerd(ShepherdId $shepherdId, string $name)
    {
        $payload = [
            'shepherdId' => $shepherdId->toString(),
            'herdName'   => $name,
        ];

        return $this->request('POST', '/testapi/commands/form-herd', $payload);
    }

    protected function adoptElePHPant(HerdId $herdId, Breed $breed)
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'breed'  => $breed->toString(),
        ];

        return $this->request('POST', '/testapi/commands/adopt-elephpant', $payload);
    }

    protected function abandonElePHPant(HerdId $herdId, Breed $breed)
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'breed'  => $breed->toString(),
        ];

        return $this->request('POST', '/testapi/commands/abandon-elephpant', $payload);
    }

    protected function renameHerd(HerdId $herdId, string $newHerdName)
    {
        $payload = [
            'herdId'      => $herdId->toString(),
            'newHerdName' => $newHerdName,
        ];

        return $this->request('POST', '/testapi/commands/rename-herd', $payload);
    }

    protected function desireBreed(HerdId $herdId, Breed $breed)
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'breed'  => $breed->toString(),
        ];

        return $this->request('POST', '/testapi/commands/desire-breed', $payload);
    }

    protected function eliminateDesireForBreed(HerdId $herdId, Breed $breed)
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'breed'  => $breed->toString(),
        ];

        return $this->request('POST', '/testapi/commands/eliminate-desire-for-breed', $payload);
    }

    protected function abandonHerd(HerdId $herdId, ShepherdId $shepherdId)
    {
        $payload = [
            'herdId'     => $herdId->toString(),
            'shepherdId' => $shepherdId->toString(),
        ];

        return $this->request('POST', '/testapi/commands/abandon-herd', $payload);
    }

    private function request(string $type, string $url, array $payload)
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

    private function client()
    {
        $client = static::createClient();

        if ($client->getContainer() === null) {
            throw new \RuntimeException('Kernel has been shutdown or not started yet.');
        }

        $this->getStore($client->getContainer())->attach(
            'appendTo',
            function (ActionEvent $event): void {
                foreach ($event->getParam('streamEvents', new \ArrayIterator()) as $recordedEvent) {
                    $this->recordedEvents[] = $recordedEvent;
                }
            }
        );

        return $client;
    }

    protected function retrieveHerdFromListing($herdId)
    {
        $herdListing = $this->getHerdListing($this->client()->getContainer());

        return $herdListing->findById($herdId);
    }

    protected function retrieveElePHPantFromListing($elePHPantId)
    {
        $herdListing = $this->getHerdListing($this->client()->getContainer());

        return $herdListing->findElePHPantByElePHPantId($elePHPantId);
    }

    protected function retrieveHerdElePHPantsFromListing($herdId)
    {
        $herdListing = $this->getHerdListing($this->client()->getContainer());

        return $herdListing->findElePHPantsByHerdId($herdId);
    }

    protected function retrieveDesiredBreedsFromListing($herdId)
    {

        $herdListing = $this->getHerdListing($this->client()->getContainer());

        return $herdListing->findDesiredBreedsByHerdId($herdId);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return ActionEventEmitterEventStore
     */
    private function getStore(ContainerInterface $container)
    {
        return $container->get('prooph_event_store.herd_store');
    }

    /**
     * @param ContainerInterface $container
     *
     * @return HerdListing
     */
    private function getHerdListing(ContainerInterface $container)
    {
        return $container->get('Elewant\Webapp\Infrastructure\ProophProjections\HerdListing');
    }

    /**
     * This method can run a projection once
     *
     * @param string $projectionName
     */
    protected function runProjection($projectionName)
    {
        self::bootKernel();
        $container = self::$container;

        $projectionManager          = $container->get(
            'prooph_event_store.projection_manager.elewant_projection_manager'
        );
        $projectionsLocator         = $container->get(
            'prooph_event_store.projections_locator'
        );
        $projectionReadModelLocator = $container->get(
            'prooph_event_store.projection_read_models_locator'
        );

        $projection = $projectionsLocator->get($projectionName);

        if ($projection instanceof ReadModelProjection) {
            if (!$projectionReadModelLocator->has($projectionName)) {
                throw new \RuntimeException(sprintf('ReadModel for "%s" not found', $projectionName));
            }
            $readModel = $projectionReadModelLocator->get($projectionName);

            $projector = $projectionManager->createReadModelProjection($projectionName, $readModel);
        }

        if ($projection instanceof Projection) {
            $projector = $projectionManager->createProjection($projectionName);
        }

        $projector = $projection->project($projector);
        $projector->run(false);
    }
}
