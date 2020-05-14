Vim
========

Install Vim, set vimrc and use update-alternatives to add vim

Requirements
------------

Debian Wheezy/Jessie/Stretch/Buster with the package python-pycurl and python-software-properties installed.

Example Playbook
-------------------------

    - hosts: servers
      roles:
         - { role: f500.vim }

License
-------

LGPL-3.0

This role includes a module called **update_alternatives** written by Philipp Grau, released
under the GPL license.

Author Information
------------------

Jasper N. Brouwer, jasper@nerdsweide.nl

Ramon de la Fuente, ramon@delafuente.nl
