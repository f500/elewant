<?php

declare(strict_types=1);

namespace Tests\Elewant\AppBundle\Controller;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Events\ElePHPantWasAdoptedByHerd;
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

    public function test_command_adopt_elephpant_returns_http_status_202()
    {
        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_adopt_elephpant_emits_ElePHPantWasAdoptedByHerd_event()
    {
        $this->assertCount(2, $this->recordedEvents);

        /** @var ElePHPantWasAdoptedByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[1];

        $this->assertInstanceOf(ElePHPantWasAdoptedByHerd::class, $eventUnderTest);
        $this->assertSame(Breed::BLACK_AMSTERDAMPHP_REGULAR, $eventUnderTest->breed()->toString());
        $this->assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

    public function test_command_adopt_elephpant_created_a_correct_herd_projection()
    {
        /** @var ElePHPantWasAdoptedByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[1];

        $expectedElePHPantProjection = [
            'elephpant_id' => $eventUnderTest->elePHPantId()->toString(),
            'herd_id'      => $eventUnderTest->herdId()->toString(),
            'breed'        => $eventUnderTest->breed()->toString(),
            'adopted_on'   => $eventUnderTest->createdAt()->format('Y-m-d H:i:s'),
        ];
        $projectedElePHPant          = $this->retrieveElePHPantFromListing($eventUnderTest->elePHPantId()->toString());
        $this->assertSame($expectedElePHPantProjection, $projectedElePHPant);
    }

}
