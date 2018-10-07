Nodejs
======

Install Node.js and NPM

Requirements
------------

Debian Wheezy/Jessie/Stretch or Ubuntu Precise/Trusty/Xenial.

Role Variables
--------------

Specify the version of Node.js you want:

    nodejs_version: 6.x

See https://github.com/nodesource/distributions

Example Playbook
-------------------------

    - hosts: servers
      roles:
        - { role: f500.nodejs }

License
-------

LGPL-3.0

Author Information
------------------

Jasper N. Brouwer, jasper@nerdsweide.nl

Ramon de la Fuente, ramon@delafuente.nl
