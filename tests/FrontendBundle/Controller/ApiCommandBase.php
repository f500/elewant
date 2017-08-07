<?php
declare(strict_types=1);

namespace Tests\Elewant\FrontendBundle\Controller;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use Elewant\Herding\Projections\HerdListing;
use Prooph\Common\Event\ActionEvent;
use Prooph\EventStore\EventStore;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiCommandBase extends WebTestCase
{
    /** @var  Client */
    protected $client;

    /** @var EventStore */
    protected $store;

    /** @var array */
    protected $recordedEvents = [];

    public function setUp()
    {
    }

    protected function formHerd(ShepherdId $shepherdId, string $name)
    {
        $payload = [
            'shepherdId' => $shepherdId->toString(),
            'herdName'   => $name,
        ];

        return $this->request('POST', '/api/commands/form-herd', $payload);
    }

    protected function adoptElePHPant(HerdId $herdId, Breed $breed)
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'breed'  => $breed->toString(),
        ];

        return $this->request('POST', '/api/commands/adopt-elephpant', $payload);
    }

    protected function abandonElePHPant(HerdId $herdId, Breed $breed)
    {
        $payload = [
            'herdId' => $herdId->toString(),
            'breed'  => $breed->toString(),
        ];

        return $this->request('POST', '/api/commands/abandon-elephpant', $payload);
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
        $client      = static::createClient();
        $this->store = $client->getContainer()->get('prooph_event_store.herd_store');
        $this->store->getActionEventEmitter()->attachListener(
            'commit.post',
            function (ActionEvent $event) {
                foreach ($event->getParam('recordedEvents', new \ArrayIterator()) as $recordedEvent) {
                    $this->recordedEvents[] = $recordedEvent;
                }
            }
        );

        return $client;
    }

    protected function retrieveHerdFromListing($herdId)
    {
        /** @var HerdListing $herdListing */
        $herdListing = $this->client()->getContainer()->get('elewant.herd_projection.herd_listing');

        return $herdListing->findById($herdId);
    }

    protected function retrieveElePHPantFromListing($elePHPantId)
    {
        /** @var HerdListing $herdListing */
        $herdListing = $this->client()->getContainer()->get('elewant.herd_projection.herd_listing');

        return $herdListing->findElePHPantByElePHPantId($elePHPantId);
    }

    protected function retrieveHerdElePHPantsFromListing($herdId)
    {
        /** @var HerdListing $herdListing */
        $herdListing = $this->client()->getContainer()->get('elewant.herd_projection.herd_listing');

        return $herdListing->findElePHPantsByHerdId($herdId);
    }


}
