Adding functionality to the model
=================================

Hey everyone. I'm glad you made it here. I'll try to explain how to "add functionality
to the model" by using an example: adding a command to rename a Herd.

### What are we going to do:
A Herd is formed as soon as a Shepherd registers. Their Herd is created with the 'display name' 
they enter on registration, but after that there is no way for a Shepherd to change their Herds name.
Oh nose! Let's fix that. 

*_What do I mean when I talk about "the model"? The model is the representation of 'reality' in our software._

So where does this belong? Luckily, this is a very small change. A Herd name clearly belongs
in the Herding model. Take a look at the Herding model's description in [DomainModel.md](/src/Elewant/Herding/DomainModel.md).
If the new functionality warrants a change in the model's description, change it to reflect the
new-and-improved model, and maybe log any decisions for future reference! 

### Getting started:

The first step to to adding our new functionality is thinking about all the moving parts:

Command -> CommandHandler -> Aggregate -> Event -> Projection

The model is described with a tool called [PHPspec](http://www.phpspec.net/en/stable/). It's a tool
meant for BDD, or Behavior Driven Development. That means, we start by 'describing' the new parts we need.
These 'specifications', or 'specs', are also runnable - and serve as tests that we can use to keep describing
new functionality later on! Go ahead and run the current specs to see if they pass.

    # Commands are run from inside the vagrant box
    > vendor/bin/phpspec run

If you want, take a look at the specifications. They live in the [/spec/Elewant/Herding](/spec/Elewant/Herding) folder. If 
everything is cool, we start describing our first new part:
 
    > vendor/bin/phpspec describe Elewant/Herding/Model/Commands/RenameHerd

This creates a new specification file: `/spec/Elewant/Herding/Model/Commands/RenameHerdSpec.php`
Then you run the test suite again:

    > vendor/bin/phpspec run

Then PHPspec will ask to create the new class: `/src/Elewant/Herding/Model/Commands/RenameHerd.php`

This Getting started guide is not aimed at explaining PHPspec, but it's good to know that we describe the
behaviour of the model. So we think about what is needed in our shiny new command, and start writing the
specifications for it. Take a sneak peek at the end result [RenameHerdSpec.php](/spec/Elewant/Herding/Model/Commands/RenameHerdSpec.php)
and the corresponding class [RenameHerd.php](/src/Elewant/Herding/Model/Commands/RenameHerd.php).

### Wait, how many files are we adding here??

So we are trying to change a single property of a single class. But by our latest calculations, we will need
to add 3 specifications and 3 classes (Command, CommandHandler and Event), and changing a few more 
(Aggregate and Projection). What's up with that?

Well, this is an event-sourced system. That means we need the event in order to store it. And the event comes 
from the Aggregate that was changed. But besides that, we also have a separation between code that _changes_
the Aggregate, and code that _views_ it. That's called CQRS, or Command Query Responsibility Segregation.

It goes hand-in-hand with Event sourcing, but that does mean we get more parts to deal with. Luckily,
they are really small parts, very easily reasoned about.
 
We also use [prooph/event-sourcing](https://github.com/prooph/event-sourcing). This is a library used to build
event-sourced systems, and some of the steps we are about to take are made easier by using the exisint building
blocks provided by Prooph.

### The Command

The commands extend `Prooph\Common\Messaging\Command` and use a `Prooph\Common\Messaging\PayloadTrait`.

You give a new command a static constructor with type-hinted parameters in order to create it. That method just
calls it's own constructor with an array, where every key is a piece of the data you want to send with the command.
Since we want to send commands cross boundaries, the values must be scalar only so they are easily serialized.

In order to rename a herd, we need to know two things: which herd is it, and what is the new name?
 
    public static function forShepherd(string $herdId, string $newHerdName): self
    {
        return new self(['herdId' => $herdId, 'newHerdName' => $newHerdName]);
    }

Also, each command should get methods that return the data. If needed, these methods return the data in a form
that is more suitable in the domain. For example, the herd-id is returned as a HerdId value object:

    public function herdId(): HerdId
    {
        return HerdId::fromString($this->payload['herdId']);
    }

    public function newHerdName(): string
    {
        return $this->payload['newHerdName'];
    }

The commit for these changes: <https://github.com/f500/elewant/commit/725660104757273277d7e34f4e10a094b22c9d14>

### The CommandHandler

The commandHandlers are nothing special. They take the command, load the aggregate, then ask the aggregate to
do the requested change. Make sure you inject any dependencies, and give the the commandHandler an __invoke
method that receives the command.

    public function __invoke(RenameHerd $command)
    {
        // load the herd
        $herd = $this->herdCollection->get($command->herdId());
        
        // perform the requested command
        $herd->rename($command->newHerdName());
        
        // save the herd
        $this->herdCollection->save($herd);
    }

Now looking at the commandHandler, that will fail because the aggregate (Herd) doesn't actually know how
to `->rename()` yet. But the aggregate can't do that without throwing the event, so it makes sense to continue
on the event first. Give the herd an empty `->rename()` method if you like to see a green testresult.

The commit for these changes: <https://github.com/f500/elewant/commit/cba49d3e7fd2620a3deae29c06f4abccdc8aef88>

### The Event

The events are once again made easier by the Prooph tooling. They extend `Prooph\EventSourcing\AggregateChanged`.
A similar concept applies as with the command, except the static constructor is always called `tookPlace`:

    public static function tookPlace(HerdId $herdId, string $newHerdName): self
    {
        return self::occur($herdId->toString(), ['newHerdName' => $newHerdName]);
    }

Another difference is that all events take place for a certain Aggregate. So events are always created with
an aggregate Id as a first parameter, followed by an array of parameters with data. The same rules apply, these
events need to be easily serialized so only scalar values.

As with commands, the events need methods that can return the data, in the proper object form if that applies:

    public function herdId(): HerdId
    {
        return HerdId::fromString($this->aggregateId());
    }

    public function newHerdName(): string
    {
        return $this->payload['newHerdName'];
    }

If you remember correctly, we always start with the specification. Take a look at the final result for the spec
[HerdWasRenamedSpec.php](/spec/Elewant/Herding/Model/Events/HerdWasRenamedSpec.php) as well as the event class 
[HerdWasRenamed.php](/src/Elewant/Herding/Model/Events/HerdWasRenamed.php).

The commit for these changes: <https://github.com/f500/elewant/commit/74a45da08a663c983efc25f302d38dbfbd77a5cb>

### The Herd aggregate

Finally! We get to actually _change_ the name of the herd. Oh, no, wait. We need to write the _specification_
for that behavior first. We update the [HerdSpec.php](/spec/Elewant/Herding/Model/HerdSpec.php) to let it know what we want
it to do:

    # /spec/Elewant/Herding/Model/HerdSpec.php
    function it_can_be_renamed()
    {
        $this->name()->shouldEqual($this->herdName);
        $this->rename('new name');
        $this->name()->shouldEqual('new name');
    }

Now let's open the Herd aggregate, and add that `->rename()` method:

    # /src/Elewant/Herding/Model/Herd.php
    public function rename(string $newName): void
    {
        $this->guardIsNotAbandoned();

        $this->recordThat(
            HerdWasRenamed::tookPlace(
                $this->herdId,
                $newName
            )
        );
    }

As you can see, when we add the possibility of something changing our aggregate, we always want to _guard_ against
impossible things happening. To make sure we don't change things that should not change. We can't go around
renaming Herds that where previously abandoned.

Furthermore, we haven't actually changed the name! All we did (for now) was say that we want to _record_ that
the Herd rename has taken place. This is the Event class we just wrote. In an event-sourced system, there is 
a separation between _recording_ events (done only once), and _applying_ events (done every time we need the current
state of the aggregate by replaying all the events that ever happened to it).

*_a good resource to get more familiar with those concepts is <http://buttercup-php.github.io/protects/>_

So besides writing the _recording_ part, we also need to write the _applying_ part, which is in the aptly-named
`apply()` function. Because we want to be real clear on what is needed to apply a certain event, we use the 
generic method to find out which `AggregateChanged` event we received and then call a specific method.

    # /src/Elewant/Herding/Model/Herd.php
    switch (get_class($event)) {
        ...
        case HerdWasRenamed::class:
            /** @var HerdWasRenamed $event */
            $this->applyHerdWasRenamed($event->herdId(), $event->newHerdName());
            break;
        ...
    }

And write a specific method to perform the change:

    # /src/Elewant/Herding/Model/Herd.php
    private function applyHerdWasRenamed(string $newHerdName)
    {
        $this->name = $newHerdName;
    }

And that's where the name is finally changed! So now we're done, right?

The commit for these changes: <https://github.com/f500/elewant/commit/acd2f99930690dc31c88fc6bb6ea36b9092d9e2a>

### The Projection

The model is now updated to handle a `RenameHerd` command. This means that we can change the data (the command 
side of CQRS). But we also have a read side. In an event-sourced system, that is usually done by listening to 
all the events that can happen, and updating a representation of those events optimized for reading. 

This _read model_ can be really simple, in our case the current state of all Herds is updated by the _projector_ 
into two mysql tables. So anytime we want to only look at Herds, we are looking at the data in those tables.

The projector in question is [HerdProjector.php](/src/Elewant/Herding/Projections/HerdProjector.php), and it needs a way of
handling the rename command:

    # /src/Elewant/Herding/Projections/HerdProjector.php
    public function onHerdWasRenamed(HerdWasRenamed $event)
    {
        $this->connection->update(
            self::TABLE_HERD,
            ['name' => $event->newHerdName(),],
            ['herd_id' => $event->herdId()->toString(),]
        );
    }

This is just an SQL statement saying `UPDATE 'herd table' SET 'name' = 'new value' WHERE herd_id = 'id';`. 

Surely, **now** we're done, right? Right?? _(you can probably tell where this is going)_

The commit for these changes: <https://github.com/f500/elewant/commit/0427f92d731dba9cc4ef4684da00a305dad9bf2c>

### The Prooph is in the pudding

Aha! You thought you caught me there, didn't you? We wrote specifications for all the other classes, but there
is no specification for the projection! Well, that's true. We made a decision that in order to properly test if
the system can handle a certain command, we would want to _actually_ fire that command, then check the values
in the test database to make sure everything is in order. It's more of an end-to-end test.

It also proves if we properly wired all the parts together in the configuration. Up to this part, we've only
concerned ourselves with the model, and not so much with the outside world. But this model is in fact part
of an application that uses it. And so we need to define all the bits and pieces in configuration:

    # /src/Elewant/AppBundle/Resources/config/service_bus.yml
    prooph_service_bus.command_buses.herding_command_bus.router.routes:
        ...
        'Elewant\Herding\Model\Commands\RenameHerd': 'elewant.rename_herd_handler'

    prooph_service_bus.event_buses.herd_event_bus.router.routes:
        ...
        'Elewant\Herding\Model\Events\HerdWasRenamed':
            - 'elewant.herd_projection.herd_projector'
            
    prooph_service_bus.event_buses.herd_replay_bus.router.routes:
        ...
        'Elewant\Herding\Model\Events\HerdWasRenamed':
            - 'elewant.herd_projection.herd_projector'

    # /src/Elewant/AppBundle/Resources/config/services.yml
    elewant.rename_herd_handler:
        class: Elewant\Herding\Model\Handlers\RenameHerdHandler
        arguments:
            - "@herd_collection"

The commit for these changes: <https://github.com/f500/elewant/commit/bfd3433c5711d084df950de928669cdabd956b56>

### The end-to-end test

You can find these tests in the [/tests/AppBundle/Controller](/tests/AppBundle/Controller/) folder. For each of the possible commands, there
is a separate test case. So we need to create a new one there called `ApiCommandRenameherdTest.php`. For your 
convenience, the tests extend an `ApiCommandBase` class that know how to call all the commands on the testApi.

So we need to add a method there that does that for us (you could put this code inside yur actual test, but this
makes it easy to re-use the `rename` command in different tests if needed).

    # tests/AppBundle/Controller/ApiCommandBase.php
    protected function renameHerd(HerdId $herdId, string $newHerdName)
    {
        $payload = [
            'herdId'     => $herdId->toString(),
            'newHerdName' => $newHerdName,
        ];

        return $this->request('POST', '/testapi/commands/rename-herd', $payload);
    }


And in order for this test to run, we need to configure a route for the testApi controller:

    # /src/Elewant/AppBundle/Resources/config/routing.yml
    'command::rename-herd':
        path: '/testapi/commands/rename-herd'
        defaults: { _controller: elewant.api_command_controller:postAction, prooph_command_name: 'Elewant\Herding\Model\Commands\RenameHerd' }

The commit for these changes: <https://github.com/f500/elewant/commit/3ea007674af3f875b7c895f46b74ef19d9457a0e>
    
These routes only exist in a development environment, and are used to run the end-to-end tests. They are also run
Travis every time you finish your work and make a Pull Request. 

Speaking of which... shouldn't we be about **done** by now?!

Well, let's run all the tests, to make sure everything works as intended:

    bin/run_tests

Hopefully, everything is green and you are good to go. Excellent job in adding functionality to the Model!
