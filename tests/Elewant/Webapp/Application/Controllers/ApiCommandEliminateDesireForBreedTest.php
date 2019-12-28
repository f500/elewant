<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Breed\BreedDesireWasEliminatedByHerd;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\ShepherdId;

class ApiCommandEliminateDesireForBreedTest extends ApiCommandBase
{
    private HerdId $herdId;

    public function setUp(): void
    {
        parent::setUp();

        $shepherdId = ShepherdId::generate();

        $this->formHerd($shepherdId, 'MyHerdName');
        $this->herdId = $this->recordedEvents[0]->herdId();

        $this->client = $this->desireBreed($this->herdId, Breed::blackAmsterdamphpRegular());
        $this->client = $this->eliminateDesireForBreed($this->herdId, Breed::blackAmsterdamphpRegular());
    }

    public function test_command_desire_breed_returns_http_status_202(): void
    {
        self::assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_eliminate_desire_for_breed_emits_BreedDesireWasEliminatedByHerd_event(): void
    {
        self::assertCount(3, $this->recordedEvents);

        /** @var BreedDesireWasEliminatedByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[2];

        self::assertInstanceOf(BreedDesireWasEliminatedByHerd::class, $eventUnderTest);
        self::assertSame(Breed::BLACK_AMSTERDAMPHP_REGULAR, $eventUnderTest->breed()->toString());
        self::assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

    public function test_command_eliminate_desire_for_breed_created_a_correct_herd_projection(): void
    {
        /** @var BreedDesireWasEliminatedByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[2];

        $expectedDesiredBreedsProjection = [];

        $this->runProjection('herd_projection');

        $desiredBreeds = $this->retrieveDesiredBreedsFromListing(
            $eventUnderTest->herdId()->toString()
        );
        self::assertSame($expectedDesiredBreedsProjection, $desiredBreeds);
    }
}
