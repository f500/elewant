Notes for developers
====================

### Cache, logs and sessions

Normally these are kept in `var/cache`, `var/logs` and `var/sessions`.

On the `develop` vagrant machine these are moved to `/dev/shm/elewant/var/cache`, `/dev/shm/elewant/var/logs` and `/dev/shm/elewant/var/sessions`.
Moving them out of the synced folder (and into a shared memory disk) greatly improves performance.

### Handy console commands

    eventstore:herd:purge

This removes all the events in the eventstore. Handy if you want to remove all the herds.

    projection:herd:rebuild
 
This truncates  the herd and elephpant tables, and rebuilds them by running all the events
in the eventstore again. Handy if you are working on the projections and want to check your work

### Step debugging

The Vagrantbox is set up with XDebug, ready to connect to your favorite IDE (I'm guessing that's PHPstorm).
All you need to do is install an XDEBUG browser plugin, and set PHPStorm to listen to incoming connections.

If you want to debug a console command, or one of the tests maybe, then you can use a convenient alias:

    phpd bin/console
    phpd vendor/bin/phpspec run
