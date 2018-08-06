Locale
======

- Generate localisation files from templates (`locale-gen`).
- Configure locale environment variables.

Requirements
------------

None.

Role Variables
--------------

These variables are available with the following default values:

    locale_generate:
      - "en_US.UTF-8 UTF-8"
      - "en_US ISO-8859-1"
      - "en_US.ISO-8859-15 ISO-8859-15"

    locale_env:
      lang: "en_US.UTF-8"
      language: "en_US:en"
      lc_all: "en_US.UTF-8"
      lc_address: ~
      lc_collate: "C"
      lc_ctype: ~
      lc_identification: ~
      lc_measurement: ~
      lc_messages: ~
      lc_monetary: ~
      lc_name: ~
      lc_numeric: ~
      lc_paper: ~
      lc_telephone: ~
      lc_time: ~
      locpath: ~

Locales listed in `locale_generate` will be placed in `/etc/locale.gen`, which is used by `locale-gen` to generate them.

The variables under `locale_env` represent environment variables and are placed in `/etc/default/locale`.

A locale _must_ be listed in `locale_generate` in order to be used in `locale_env`.

Example Playbook
----------------

    - hosts: example
      roles:
      - { role: f500.locale }

License
-------

LGPL-3.0

Author Information
------------------

Jasper N. Brouwer, jasper@nerdsweide.nl

Ramon de la Fuente, ramon@delafuente.nl
