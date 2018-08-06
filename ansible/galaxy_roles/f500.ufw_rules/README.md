Ufw
========

Install UWF (Uncomplicated Firewall)

Requirements
------------

Debian Wheezy/Jessie/Stretch with the package python-pycurl and python-software-properties installed.
Requires the ufw firewall to be installed on the guest.

Role Variables
--------------

Set the default policy:

    ufw_rules_default_policy: deny

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
-------------------------

    - hosts: servers
      roles:
         - { role: f500.ufw_rules, ufw_rules_allow_ports[22, 80, 443] }

License
-------

LGPL-3.0

Author Information
------------------

Jasper N. Brouwer, jasper@nerdsweide.nl

Ramon de la Fuente, ramon@delafuente.nl
