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
