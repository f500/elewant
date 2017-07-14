# Elewant

### Getting started

Add hosts to your hostsfile:

    192.168.77.77   develop.elewant.loc
    192.168.77.78   prodsim.elewant.loc

Fetch roles from Ansible Galaxy:

    ansible-galaxy install -r ansible/galaxy_roles.yml -f

Bring your box up:

    vagrant up

Re-provision your box if needed:
 
    vagrant provision

You should be up-and-running!

    http://develop.elewant.loc/

### Cache, logs and sessions

Normally these are kept in `var/cache`, `var/logs` and `var/sessions`.

On the `develop` vagrant machine these are moved to `/dev/shm/elewant/var/cache`, `/dev/shm/elewant/var/logs` and `/dev/shm/elewant/var/sessions`.
Moving them out of the synced folder (and into a shared memory disk) greatly improves performance.
