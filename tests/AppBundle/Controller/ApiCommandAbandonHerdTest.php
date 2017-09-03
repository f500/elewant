<?php

declare(strict_types=1);

namespace Tests\Elewant\AppBundle\Controller;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Events\HerdWasAbandoned;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use PHPUnit\Framework\TestCase;

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
        TestCase::assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_abandon_herd_emits_HerdWasAbandoned_event()
    {
        TestCase::assertCount(3, $this->recordedEvents);

        $eventUnderTest = $this->recordedEvents[2];
        TestCase::assertInstanceOf(HerdWasAbandoned::class, $eventUnderTest);
        TestCase::assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

    public function test_command_abandon_herd_created_a_correct_herd_projection()
    {
        /** @var HerdWasAbandoned $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[2];

        $shouldBeEmpty = $this->retrieveHerdFromListing($eventUnderTest->herdId()->toString());
        $shouldBeEmptyElePHPants = $this->retrieveHerdElePHPantsFromListing($eventUnderTest->herdId()->toString());

        TestCase::assertEmpty($shouldBeEmpty,
            sprintf('A Herd (%s) is still projected after being abandonded.', $eventUnderTest->herdId()->toString())
        );
        TestCase::assertEmpty($shouldBeEmptyElePHPants,
            sprintf('ElePHPants for a herd (%s) are still projected after the herd is abandonded.', $eventUnderTest->herdId()->toString())
        );
    }


}
