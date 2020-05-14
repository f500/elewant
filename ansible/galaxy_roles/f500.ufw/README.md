UFW
===

Install UWF (Uncomplicated Firewall) and create (or remove) rules.

Requirements
------------

Debian Wheezy/Jessie/Stretch/Buster with the package python-pycurl and python-software-properties installed.

Role Variables
--------------

Set the default policy:

    ufw_default_policy:
      - { direction: "incoming", policy: "deny" }


Add or remove rules:

    ufw_rules_to_create: []
    ufw_rules_to_delete: []

Both `ufw_rules_to_create` and `ufw_rules_to_delete` accept a list of dictionaries, like so:

    ufw_rules_to_create:
      - direction: in
        from_ip: 1.2.3.4
        from_port: 5678
        interface: eth0
        proto: tcp
        rule: allow
        to_ip: 5.6.7.8
        to_port: 1234

Example Playbook
----------------

    - hosts: servers
      roles:
         - role: f500.ufw
           ufw_rules_to_create:
             - { to_port: 22 }
             - { to_port: 80 }
             - { to_port: 443 }

License
-------

Copyright (C) 2017 Future500 B.V.

[LGPL-3.0](https://github.com/f500/ansible-ufw/blob/master/COPYING.LESSER)

Author Information
------------------

Jasper N. Brouwer, jasper@future500.nl

Ramon de la Fuente, ramon@future500.nl
