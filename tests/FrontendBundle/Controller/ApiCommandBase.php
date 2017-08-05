<?php
declare(strict_types=1);

namespace Tests\Elewant\FrontendBundle\Controller;

use Prooph\Common\Event\ActionEvent;
use Rhumsaa\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Prooph\EventStore\EventStore;

abstract class ApiCommandBase extends WebTestCase
{
    /** @var  Client  */
    protected $client;

    /** @var EventStore */
    protected $store;

    /** @var array */
    protected $recordedEvents = [];

    public function setUp()
    {}

    protected function formHerd(Uuid $shepherdId, string $name)
    {
        $payload = array(
            'shepherdId' => $shepherdId->toString(),
            'herdName' => $name,
        );

        return $this->request('POST', '/api/commands/form-herd', $payload);
    }

    private function request(string $type, string $url, array $payload){
        $client = $this->client();
        $client->request(
            $type,
            $url,
            array(),
            array(),
            array(),
            json_encode($payload)
        );
        return $client;
    }

    private function client(){
        $client = static::createClient();
        $this->store = $client->getContainer()->get('prooph_event_store.herd_store');
        $this->store->getActionEventEmitter()->attachListener('commit.post',
            function (ActionEvent $event) {
                foreach ($event->getParam('recordedEvents', new \ArrayIterator()) as $recordedEvent) {
                    $this->recordedEvents[] = $recordedEvent;
                }
            }
        );

        return $client;
    }
}
