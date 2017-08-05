<?php

declare(strict_types=1);

namespace Tests\Elewant\FrontendBundle\Controller;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Events\ElePHPantWasAdoptedByHerd;
use Elewant\Herding\Model\Events\HerdWasFormed;
use Elewant\Herding\Model\ShepherdId;

class ApiCommandAdoptElePHPantTest extends ApiCommandBase
{
    public function setUp()
    {
        parent::setUp();
        $shepherdId = ShepherdId::generate();

        $this->formHerd($shepherdId, 'MyHerdName');
        $this->herdId = $this->recordedEvents[0]->herdId();

        $this->client = $this->adoptElePHPant($this->herdId, Breed::blackAmsterdamphpRegular());
    }

    public function test_command_post_todo_returns_http_status_202()
    {
        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_post_todo_emits_ElePHPantWasAdoptedByHerd_event()
    {
        $this->assertCount(2, $this->recordedEvents);

        $eventUnderTest = $this->recordedEvents[1];

        $this->assertInstanceOf(ElePHPantWasAdoptedByHerd::class, $eventUnderTest);
        $this->assertSame(Breed::BLACK_AMSTERDAMPHP_REGULAR, $eventUnderTest->breed()->toString());
        $this->assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }
}
