<?php

declare(strict_types=1);

namespace Tests\Elewant\AppBundle\Controller;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Events\BreedWasDesiredByHerd;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use PHPUnit\Framework\TestCase;

class ApiCommandDesireBreedTest extends ApiCommandBase
{
    /**
     * @var HerdId
     */
    private $herdId;

    public function setUp()
    {
        parent::setUp();
        $shepherdId = ShepherdId::generate();

        $this->formHerd($shepherdId, 'MyHerdName');
        $this->herdId = $this->recordedEvents[0]->herdId();

        $this->client = $this->desireBreed($this->herdId, Breed::blackAmsterdamphpRegular());
    }

    public function test_command_desire_breed_returns_http_status_202()
    {
        TestCase::assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_desire_breed_emits_BreedWasDesiredByHerd_event()
    {
        TestCase::assertCount(2, $this->recordedEvents);

        /** @var BreedWasDesiredByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[1];

        TestCase::assertInstanceOf(BreedWasDesiredByHerd::class, $eventUnderTest);
        TestCase::assertSame(Breed::BLACK_AMSTERDAMPHP_REGULAR, $eventUnderTest->breed()->toString());
        TestCase::assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

    public function test_command_desire_breed_created_a_correct_herd_projection()
    {
        /** @var BreedWasDesiredByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[1];

        $expectedDesiredBreedsProjection = [
            [
                'breed'        => $eventUnderTest->breed()->toString(),
                'herd_id'      => $eventUnderTest->herdId()->toString(),
                'desired_on'   => $eventUnderTest->createdAt()->format('Y-m-d H:i:s'),
            ],
        ];
        $desiredBreeds          = $this->retrieveDesiredBreedsFromListing($eventUnderTest->herdId()->toString());
        TestCase::assertSame($expectedDesiredBreedsProjection, $desiredBreeds);
    }

}
