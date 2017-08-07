<?php

declare(strict_types=1);

namespace Tests\Elewant\FrontendBundle\Controller;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Events\HerdWasAbandoned;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;

class ApiCommandAbandonHerdTest extends ApiCommandBase
{
    /** @var  HerdId */
    private $herdId;

    public function setUp()
    {
        parent::setUp();
        $shepherdId = ShepherdId::generate();
        $this->formHerd($shepherdId, 'MyHerdName');
        $this->herdId = $this->recordedEvents[0]->herdId();

        $this->adoptElePHPant($this->herdId, Breed::blackAmsterdamphpRegular());

        $this->client = $this->abandonHerd($this->herdId, $shepherdId);
    }

    public function test_command_abandon_herd_returns_http_status_202()
    {
        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_abandon_herd_emits_HerdWasAbandoned_event()
    {
        $this->assertCount(3, $this->recordedEvents);

        $eventUnderTest = $this->recordedEvents[2];
        $this->assertInstanceOf(HerdWasAbandoned::class, $eventUnderTest);
        $this->assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

    public function test_command_abandon_herd_created_a_correct_herd_projection()
    {
        /** @var HerdWasAbandoned $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[2];

        $shouldBeEmpty = $this->retrieveHerdFromListing($eventUnderTest->herdId()->toString());
        $shouldBeEmptyElePHPants = $this->retrieveHerdElePHPantsFromListing($eventUnderTest->herdId()->toString());

        $this->assertEmpty($shouldBeEmpty,
            sprintf('A Herd (%s) is still projected after being abandonded.', $eventUnderTest->herdId()->toString())
        );
        $this->assertEmpty($shouldBeEmptyElePHPants,
            sprintf('ElePHPants for a herd (%s) are still projected after the herd is abandonded.', $eventUnderTest->herdId()->toString())
        );
    }


}
