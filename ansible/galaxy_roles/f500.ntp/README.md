Ntp
========

Install ntp service and set default ntp servers for the netherlands.

Requirements
------------

Debian Wheezy/Jessie/Stretch/Buster with the package python-pycurl and python-software-properties installed.

Role Variables
--------------

The default settings add ntp servers in the netherlands.

    ntp_servers:
        - "0.nl.pool.ntp.org"
        - "1.nl.pool.ntp.org"
        - "2.nl.pool.ntp.org"
        - "3.nl.pool.ntp.org"

    ntp_restrict_policies:
        - "default ignore"
        - "127.0.0.1"
        - "::1"

Example Playbook
-------------------------

    - hosts: servers
      roles:
         - { role: f500.ntp }

License
-------

LGPL-3.0

Author Information
------------------

Jasper N. Brouwer, jasper@nerdsweide.nl

Ramon de la Fuente, ramon@delafuente.nl
