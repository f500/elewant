<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPantId;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPantWasAbandonedByHerd;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\ShepherdId;
use PHPUnit\Framework\TestCase;

class ApiCommandAbandonElePHPantTest extends ApiCommandBase
{
    private HerdId $herdId;

    private ElePHPantId $adoptedElePHPantId;

    public function setUp(): void
    {
        parent::setUp();

        $shepherdId = ShepherdId::generate();

        $this->formHerd($shepherdId, 'MyHerdName');
        $this->herdId = $this->recordedEvents[0]->herdId();

        $this->adoptElePHPant($this->herdId, Breed::blackAmsterdamphpRegular());
        $this->adoptedElePHPantId = $this->recordedEvents[1]->elePHPantId();

        $this->client = $this->abandonElePHPant($this->herdId, Breed::blackAmsterdamphpRegular());
    }

    public function test_command_abandon_elephpant_returns_http_status_202(): void
    {
        TestCase::assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_abandon_elephpant_emits_ElePHPantWasAdoptedByHerd_event(): void
    {
        self::assertCount(3, $this->recordedEvents);

        $eventUnderTest = $this->recordedEvents['2'];

        self::assertInstanceOf(ElePHPantWasAbandonedByHerd::class, $eventUnderTest);
        self::assertSame(Breed::BLACK_AMSTERDAMPHP_REGULAR, $eventUnderTest->breed()->toString());
        self::assertTrue($this->adoptedElePHPantId->equals($eventUnderTest->elePHPantId()));
        self::assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

    public function test_command_abandon_elephpant_created_a_correct_herd_projection(): void
    {
        /** @var ElePHPantWasAbandonedByHerd $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[2];

        $this->runProjection('herd_projection');

        $shouldBeEmpty = $this->retrieveElePHPantFromListing($eventUnderTest->elePHPantId()->toString());

        self::assertEmpty(
            $shouldBeEmpty,
            sprintf(
                'An ElePHPant (%s) is still projected after being abandonded.',
                $eventUnderTest->elePHPantId()->toString()
            )
        );
    }
}
