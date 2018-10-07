Debian
========

This role installs these packages:

* curl
* python-pycurl
* python-configparser

It adds debian backports repos. 

It also runs apt-get update when it was run, last time, more than `{{ debian_cache_valid_time }}` seconds ago.

Requirements
------------

Debian Wheezy/Jessie/Stretch.

Role Variables
--------------

    debian_cache_update: yes
    debian_cache_valid_time: 14400
    debian_codename: '{{ ansible_distribution_release }}' ('wheezy', 'jessie', 'stretch')
    debian_codename: '{{ ansible_distribution_release }}'

Be aware that `debian_codename` defaults on `{{ ansible_distribution_release }}` so, most of the chances, you don't need to specify it.


Dependencies
-------------------------

None

Example Playbook
-------------------------

    - hosts: servers
      roles:
         - { role: f500.debian }

License
-------

LGPL-3.0

Author Information
------------------

Jasper N. Brouwer, jasper@nerdsweide.nl

Ramon de la Fuente, ramon@delafuente.nl
