# Elewant

### What is it?

Elewant is a project based on the PHP mascotte: the ElePHPant.

The idea is that we make it easier in the community to swap and share these lovely plush pachyderms.
For now, you can create a herd and enter all the members that you have. In the (hopefully near) future,
it should be possible to put your surplus ElePHPants up for trade, and request specific ElePHPants that
you want to add to your herd.

But this project is more. The idea is to allow people who are interested involved. Not just by open sourcing
the code so it can be contributed to, but by actually building and running it together. From idea to production
and beyond.

### What can be learned?

Well, the basics are pretty simple: we build, deploy and maintain a project together.
This means that all the aspects of building _and_ maintaining are areas of learning:

So some usual suspects:
- Behaviour driven development 
- Automated test
- Event sourcing
- The Symfony framework

But also:
- Automated Provisioning
- Deploying
- Build pipelines
- Logging and alerting
- Dependency management

And eventually: 
- How to deal with legacy code  

### Getting started

Add hosts to your hostsfile:

    192.168.77.77   develop.elewant.loc

Fetch roles from Ansible Galaxy:

    ansible-galaxy install -r ansible/galaxy_roles.yml -f

Bring your box up:

    vagrant up --provision

You should be up-and-running!

    http://develop.elewant.loc/

### Cache, logs and sessions

Normally these are kept in `var/cache`, `var/logs` and `var/sessions`.

On the `develop` vagrant machine these are moved to `/dev/shm/elewant/var/cache`, `/dev/shm/elewant/var/logs` and `/dev/shm/elewant/var/sessions`.
Moving them out of the synced folder (and into a shared memory disk) greatly improves performance.
