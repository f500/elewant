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

### Getting started

You will need some tools on your local system:

    [Virtual box](https://www.virtualbox.org/)
    [Ansible](https://www.ansible.com/)

Add the following line to your hostsfile:

    192.168.77.77   develop.elewant.loc

Fetch roles from Ansible Galaxy:

    ansible-galaxy install -r ansible/galaxy_roles.yml -f

If you want to be able to log in with twitter, you'll need to create an application at app.twitter.com, 
then place your key & secret in a file called `ansible/provision/group_vars/develop/override_locally.yml`.

Bring your box up:

    vagrant up --provision

You should be up-and-running!

    http://develop.elewant.loc/

### Running the tests

The core domain is tested with a BDD tool called [phpspec](http://www.phpspec.net/). You can find the specifications
in the `/spec` folder, they 'describe' what the tested code should be doing, and can be run to verify that that is
actually the case.

    # running the phpspec test suite:
    vendor/bin/phpspec run
    vendor/bin/phpspec run spec/Path/To/A/Folder/
    vendor/bin/phpspec run spec/Path/To/A/Specific/File.php

Then there are some tests that try to run the application through the framework, after bootstrapping. This helps to
verify that all the configuration/wiring is in order. Those tests are written in phpunit, but using the Symfony
WebTestCase as a base. The tests are located in the `/test` folder.

    # running the phpunit test suite:
    vendor/bin/phpunit
    vendor/bin/phpunit tests/Path/To/A/Folder/
    vendor/bin/phpunit tests/Path/To/A/Specific/File.php

Additionally, we use the PHPStan static analysis tool to verify that we do not have any detectable PHP errors. 

    # running phpstan
    vendor/bin/phpstan analyse --configuration phpstan.neon --level 7 src

For your convenience, there is also a file that ruins both suites back to back. That's also what Travis does.

    # running all the tests
    bin/run_tests

### Cache, logs and sessions

Normally these are kept in `var/cache`, `var/logs` and `var/sessions`.

On the `develop` vagrant machine these are moved to `/dev/shm/elewant/var/cache`, `/dev/shm/elewant/var/logs` and `/dev/shm/elewant/var/sessions`.
Moving them out of the synced folder (and into a shared memory disk) greatly improves performance.

### Moar docs

You can find more docs in the [/docs](/docs) folder.

### Provision (only for those who have access to the production server)

    # Production
    ansible-playbook -i ansible/hosts-provision ansible/provision/playbook.yml --limit=production --ask-vault-pass

### Deploy (only for those who have access to the production server)

    # Staging
    ansible-playbook -i ansible/hosts-deploy ansible/deploy/playbook.yml --limit=staging --extra="project_version=develop" --ask-vault-pass

    # Production
    ansible-playbook -i ansible/hosts-deploy ansible/deploy/playbook.yml --limit=production --extra="project_version=master" --ask-vault-pass
