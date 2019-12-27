# Elewant

### What is it?

Elewant is a project based on the PHP mascotte: the ElePHPant.

The idea is that we make it easier in the community to swap and share these lovely plush pachyderms.
For now, you can create a herd and enter all the members that you have. In the near future,
it will be possible to put your surplus ElePHPants up for trade, and request specific ElePHPants that
you want to add to your herd.

But this project is more. The idea is to allow people who are interested involved. Not just by open sourcing
the code so it can be contributed to, but by actually building and running it together. From idea to production
and beyond.

### What can be learned?

Well, the basics are pretty simple: we build, deploy and maintain a project together.
This means that all the aspects of building _and_ maintaining are areas of learning:

So some usual suspects:
- Behaviour driven development
- Automated testing
- Event sourcing
- The Symfony framework

But also:
- Automated Provisioning
- Deploying
- Build pipelines
- Logging and alerting
- Dependency management

And eventually:
- How to deal with legacy code ;-)

Feel free to add anything you learn, no matter how small, to <docs/lessons_learned.md>

### Getting started with Docker

You will need docker on your local system:

    [Docker](https://www.docker.com/)

Run bin/setup (only for the first time) and then Docker compose:

    docker-compose up -d
    bin/setup

You should be up-and-running!

    http://localhost.elewant.com/

For developers, there is a special button on the front page to generate users for your local environment.
Just click on "Developer login" and you should be able to create (and log in as) randomly created users.

> If you want to be able to log in with twitter, you'll need to create an application at app.twitter.com,
then place your key & secret in `.env.local`.

Have fun!


#### Running commands inside docker containers

Run `ls` inside the default container (`php-cli`):

    bin/docker ls

Run `ls` inside the default container of the testing environment:

    bin/docker-test ls

Print usage:

    bin/docker
    bin/docker-test

Running Symfony Console:

    bin/docker bin/console c:c
    bin/docker-test bin/console c:c

Running vendor binaries:

    bin/docker vendor/bin/phpunit
    bin/docker-test vendor/bin/phpunit

#### Running all tests

    bin/docker-test bin/run_tests


### Moar docs

You can find more docs in the [/docs](/docs) folder, like:

- How to run the tests [running_the_tests](/docs/running_the_tests.md)
- Adding functionality to the [domain model](/docs/adding_functionality_to_the_model.md)
- A few handy [notes for developers](/docs/notes_for_developers.md)
- A neat list of [things people learned](/docs/lessons_learned.md) working on the project
