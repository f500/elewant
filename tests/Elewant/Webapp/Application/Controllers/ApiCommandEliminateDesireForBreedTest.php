<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Breed\BreedDesireWasEliminatedByHerd;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\ShepherdId;
use PHPUnit\Framework\TestCase;

class ApiCommandEliminateDesireForBreedTest extends ApiCommandBase
{
    /** @var HerdId */
    private $herdId;

    public function setUp()
    {
        parent::setUp();

        $shepherdId = ShepherdId::generate();

        $this->formHerd($shepherdId, 'MyHerdName');
        $this->herdId = $this->recordedEvents[0]->herdId();

        $this->client = $this->desireBreed($this->herdId, Breed::blackAmsterdamphpRegular());
        $this->client = $this->eliminateDesireForBreed($this->herdId, Breed::blackAmsterdamphpRegular());
    }

    public function test_command_desire_breed_returns_http_status_202()
    {
        TestCase::assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_eliminate_desire_for_breed_emits_BreedDesireWasEliminatedByHerd_event()
    {
        TestCase::assertCount(3, $this->recordedEvents);

        /** @var BreedDesireWasEliminatedByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[2];

        TestCase::assertInstanceOf(BreedDesireWasEliminatedByHerd::class, $eventUnderTest);
        TestCase::assertSame(Breed::BLACK_AMSTERDAMPHP_REGULAR, $eventUnderTest->breed()->toString());
        TestCase::assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

    public function test_command_eliminate_desire_for_breed_created_a_correct_herd_projection()
    {
        /** @var BreedDesireWasEliminatedByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[2];

        $expectedDesiredBreedsProjection = [];

        $this->runProjection('herd_projection');

        $desiredBreeds = $this->retrieveDesiredBreedsFromListing(
            $eventUnderTest->herdId()->toString()
        );
        TestCase::assertSame($expectedDesiredBreedsProjection, $desiredBreeds);
    }

}
