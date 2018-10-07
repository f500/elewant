<?php

declare(strict_types=1);

namespace Tests\Elewant\AppBundle\Controller;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Events\ElePHPantWasAbandonedByHerd;
use Elewant\Herding\Model\ShepherdId;
use PHPUnit\Framework\TestCase;

class ApiCommandAbandonElePHPantTest extends ApiCommandBase
{
    private $herdId;
    private $adoptedElePHPantId;

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

    public function test_command_abandon_elephpant_returns_http_status_202()
    {
        TestCase::assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_abandon_elephpant_emits_ElePHPantWasAdoptedByHerd_event()
    {
        TestCase::assertCount(3, $this->recordedEvents);

        $eventUnderTest = $this->recordedEvents['2'];

        TestCase::assertInstanceOf(ElePHPantWasAbandonedByHerd::class, $eventUnderTest);
        TestCase::assertSame(Breed::BLACK_AMSTERDAMPHP_REGULAR, $eventUnderTest->breed()->toString());
        TestCase::assertTrue($this->adoptedElePHPantId->equals($eventUnderTest->elePHPantId()));
        TestCase::assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

    public function test_command_abandon_elephpant_created_a_correct_herd_projection()
    {
        /** @var ElePHPantWasAbandonedByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[2];

        $this->runProjection('herd_projection');

        $shouldBeEmpty = $this->retrieveElePHPantFromListing($eventUnderTest->elePHPantId()->toString());

        TestCase::assertEmpty(
            $shouldBeEmpty,
            sprintf(
                'An ElePHPant (%s) is still projected after being abandonded.',
                $eventUnderTest->elePHPantId()->toString()
            )
        );
    }

}
