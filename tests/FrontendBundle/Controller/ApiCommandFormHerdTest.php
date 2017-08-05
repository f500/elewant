<?php

declare(strict_types=1);

namespace Tests\Elewant\FrontendBundle\Controller;

use Elewant\Herding\Model\Events\HerdWasFormed;
use Rhumsaa\Uuid\Uuid;

class ApiCommandFormHerdTest extends ApiCommandBase
{
    public function setUp()
    {
        parent::setUp();
        $shepherdId   = Uuid::uuid4();
        $this->client = $this->formHerd($shepherdId, 'MyHerdName');
    }

    public function test_command_post_todo_returns_http_status_202()
    {
        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_post_todo_emits_HerdWasFormed_event()
    {
        $this->assertCount(1, $this->recordedEvents);
        $this->assertInstanceOf(HerdWasFormed::class, $this->recordedEvents[0]);
    }
}
