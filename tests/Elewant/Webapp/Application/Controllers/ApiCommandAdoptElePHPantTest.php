<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPantWasAdoptedByHerd;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\ShepherdId;
use PHPUnit\Framework\TestCase;

class ApiCommandAdoptElePHPantTest extends ApiCommandBase
{
    /** @var HerdId */
    private $herdId;

    public function setUp(): void
    {
        parent::setUp();
        $shepherdId = ShepherdId::generate();

        $this->formHerd($shepherdId, 'MyHerdName');
        $this->herdId = $this->recordedEvents[0]->herdId();

        $this->client = $this->adoptElePHPant($this->herdId, Breed::blackAmsterdamphpRegular());
    }

    public function test_command_adopt_elephpant_returns_http_status_202(): void
    {
        TestCase::assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_adopt_elephpant_emits_ElePHPantWasAdoptedByHerd_event(): void
    {
        TestCase::assertCount(2, $this->recordedEvents);

        /** @var ElePHPantWasAdoptedByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[1];

        TestCase::assertInstanceOf(ElePHPantWasAdoptedByHerd::class, $eventUnderTest);
        TestCase::assertSame(Breed::BLACK_AMSTERDAMPHP_REGULAR, $eventUnderTest->breed()->toString());
        TestCase::assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

    public function test_command_adopt_elephpant_created_a_correct_herd_projection(): void
    {
        /** @var ElePHPantWasAdoptedByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[1];

        $expectedElePHPantProjection = [
            'elephpant_id' => $eventUnderTest->elePHPantId()->toString(),
            'herd_id' => $eventUnderTest->herdId()->toString(),
            'breed' => $eventUnderTest->breed()->toString(),
            'adopted_on' => $eventUnderTest->createdAt()->format('Y-m-d H:i:s'),
        ];

        $this->runProjection('herd_projection');

        $projectedElePHPant = $this->retrieveElePHPantFromListing($eventUnderTest->elePHPantId()->toString());
        TestCase::assertSame($expectedElePHPantProjection, $projectedElePHPant);
    }
}
