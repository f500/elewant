<?php

declare(strict_types=1);

namespace Tests\Elewant\FrontendBundle\Controller;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Events\ElePHPantWasAbandonedByHerd;
use Elewant\Herding\Model\ShepherdId;

class ApiCommandAbandonElePHPantTest extends ApiCommandBase
{
    public function setUp()
    {
        parent::setUp();
        $shepherdId = ShepherdId::generate();

        $this->formHerd($shepherdId, 'MyHerdName');
        $this->herdId = $this->recordedEvents[0]->herdId();

        $this->adoptElePHPant($this->herdId, Breed::blackAmsterdamphpRegular());
        $this->adoptedElePHPantId = $this->recordedEvents[1]->elePHPantId();

        $this->client = $this->abandonElePHPant($this->herdId, Breed::blackAmsterdamphpRegular());
    }

    public function test_command_post_todo_returns_http_status_202()
    {
        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_post_todo_emits_ElePHPantWasAdoptedByHerd_event()
    {
        $this->assertCount(3, $this->recordedEvents);

        $eventUnderTest = $this->recordedEvents['2'];

        $this->assertInstanceOf(ElePHPantWasAbandonedByHerd::class, $eventUnderTest);
        $this->assertSame(Breed::BLACK_AMSTERDAMPHP_REGULAR, $eventUnderTest->breed()->toString());
        $this->assertTrue($this->adoptedElePHPantId->equals($eventUnderTest->elePHPantId()));
        $this->assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }
}
