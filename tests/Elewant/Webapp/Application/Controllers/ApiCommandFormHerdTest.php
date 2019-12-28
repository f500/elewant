<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

use Elewant\Herding\DomainModel\Herd\HerdWasFormed;
use Elewant\Herding\DomainModel\ShepherdId;
use PHPUnit\Framework\TestCase;

class ApiCommandFormHerdTest extends ApiCommandBase
{
    private ShepherdId $shepherdId;

    public function setUp(): void
    {
        parent::setUp();
        $this->shepherdId = ShepherdId::generate();
        $this->client = $this->formHerd($this->shepherdId, 'My herd name 😱');
    }

    public function test_command_form_herd_returns_http_status_202(): void
    {
        TestCase::assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_form_herd_emits_HerdWasFormed_event(): void
    {
        TestCase::assertCount(1, $this->recordedEvents);

        /** @var HerdWasFormed $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[0];
        TestCase::assertInstanceOf(HerdWasFormed::class, $eventUnderTest);
        TestCase::assertSame('My herd name 😱', $eventUnderTest->name());
    }

    public function test_command_form_herd_created_a_correct_herd_projection(): void
    {
        /** @var HerdWasFormed $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[0];

        $expectedHerdProjection = [
            'herd_id' => $eventUnderTest->herdId()->toString(),
            'shepherd_id' => $eventUnderTest->shepherdId()->toString(),
            'name' => $eventUnderTest->name(),
            'formed_on' => $eventUnderTest->createdAt()->format('Y-m-d H:i:s'),
        ];

        $this->runProjection('herd_projection');

        $projectedHerd = $this->retrieveHerdFromListing($eventUnderTest->herdId()->toString());
        TestCase::assertSame($expectedHerdProjection, $projectedHerd);
    }
}
