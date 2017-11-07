<?php

declare(strict_types=1);

namespace Tests\Elewant\AppBundle\Controller;

use Elewant\Herding\Model\Events\HerdWasRenamed;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use PHPUnit\Framework\TestCase;

class ApiCommandRenameHerdTest extends ApiCommandBase
{
    /** @var  HerdId */
    private $herdId;

    public function setUp()
    {
        parent::setUp();
        $shepherdId = ShepherdId::generate();
        $this->formHerd($shepherdId, 'MyHerdName');
        $this->herdId = $this->recordedEvents[0]->herdId();
        $this->client = $this->renameHerd($this->herdId, 'new herd name');
    }

    public function test_command_rename_herd_returns_http_status_202()
    {
        TestCase::assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_rename_herd_emits_HerdWasRenamed_event()
    {
        TestCase::assertCount(2, $this->recordedEvents);

        $eventUnderTest = end($this->recordedEvents);
        TestCase::assertInstanceOf(HerdWasRenamed::class, $eventUnderTest);
        TestCase::assertTrue($this->herdId->equals($eventUnderTest->herdId()));
    }

    public function test_command_rename_herd_created_a_correct_herd_projection()
    {
        /** @var HerdWasRenamed $eventUnderTest */
        $eventUnderTest = end($this->recordedEvents);

        $projectedHerd = $this->retrieveHerdFromListing($eventUnderTest->herdId()->toString());

        TestCase::assertSame(
            'new herd name',
            $projectedHerd['name'],
            sprintf(
                'The Herd (%s) was found, but with the wrong name "%s" instead of "%s"',
                $eventUnderTest->herdId()->toString(),
                $projectedHerd['name'],
                'new herd name'
            )
        );
    }

}
