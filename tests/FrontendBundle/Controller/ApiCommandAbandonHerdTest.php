<?php

declare(strict_types=1);

namespace Tests\Elewant\FrontendBundle\Controller;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Events\HerdWasAbandoned;
use Elewant\Herding\Model\ShepherdId;

class ApiCommandAbandonHerdTest extends ApiCommandBase
{
    public function setUp()
    {
        parent::setUp();
        $shepherdId = ShepherdId::generate();
        $this->formHerd($shepherdId, 'MyHerdName');
        $this->herdId = $this->recordedEvents[0]->herdId();

        $this->adoptElePHPant($this->herdId, Breed::blackAmsterdamphpRegular());

        $this->client = $this->abandonHerd($this->herdid, $shepherdId);
    }

    public function test_command_post_todo_returns_http_status_202()
    {
        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_post_todo_emits_HerdWasAbandoned_event()
    {
        $this->assertCount(1, $this->recordedEvents);

        $eventUnderTest = $this->recordedEvents[0];
        $this->assertInstanceOf(HerdWasAbandoned::class, $eventUnderTest);
        $this->assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

}
