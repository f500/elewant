PHP7
====

Install PHP version 7.X from [deb.sury.org](https://deb.sury.org/).

Requirements
------------

This role is tailored towards Debian Jessie.

The packages `python-apt` (or `python3-apt`) must be installed.

Role Variables
--------------

All role variables are displayed with their default value.

Specify the version of PHP you want to use (`7.0`, `7.1`, `7.2` or `7.3`):

    php7_version: 7.3

CLI is always installed. You can also install the Apache2 module, CGI binary and/or FPM binary:

    php7_enable_apache: no
    php7_enable_cgi:    no
    php7_enable_fpm:    no

Files for module development can be installed too:

    php7_enable_dev: no

The original `php.ini` files are _never_ touched by this role.
Instead a `99-customization.ini` file is placed in the appropriate `conf.d` directories.
These customization files will be filled with the [INI directives](https://secure.php.net/manual/en/ini.list.php) you specify with the following role variables.

### INI

Global INI directives, which configure all of CLI, Apache2, CGI and FPM:

    php7_ini_directives_global: {}

Specific INI directives:

    php7_ini_directives_cli:    {}
    php7_ini_directives_apache: {}
    php7_ini_directives_cgi:    {}
    php7_ini_directives_fpm:    {}

This role sets a couple of INI directives that are viewed as best practices.
The result is the same as if configured in the following way:

    php7_ini_directives_global:
      allow_url_fopen: no
      disable_functions: "exec, passthru, shell_exec, system, proc_open, popen, curl_exec, curl_multi_exec"
      expose_php: no
      session.cookie_httponly: yes
      session.cookie_secure: yes
      session.hash_bits_per_character: 4  # 7.0 only
      session.hash_function: sha256       # 7.0 only
      session.sid_bits_per_character: 4   # 7.1 and up
      session.sid_length: 64              # 7.1 and up
      session.use_strict_mode: yes

### Extensions

Install additional PHP extensions:

    php7_extensions: []
    php7_versioned_extensions: []

For example, to install the packages `php-apcu`, `php7.X-curl` and `php7.X-mysql`:

    php7_extensions:
      - apcu

    php7_versioned_extensions:
      - curl
      - mysql

Extension specific INI directives can be specified the same way any other, see the **INI** section above.

### FPM

The following variables can be used to configure PHP FPM:

    php7_fpm_pid: "/run/php/php{{ php7_version }}-fpm.pid"
    php7_fpm_error_log: "/var/log/php{{ php7_version }}-fpm.log"
    php7_fpm_log_level: warning
    php7_fpm_syslog_facility: ~
    php7_fpm_syslog_ident: ~
    php7_fpm_emergency_restart_threshold: 0
    php7_fpm_emergency_restart_interval: 0
    php7_fpm_process_control_timeout: 0
    php7_fpm_process_max: 0
    php7_fpm_process_priority: ~
    php7_fpm_daemonize: yes
    php7_fpm_rlimit_files: ~
    php7_fpm_rlimit_core: ~
    php7_fpm_events_mechanism: epoll
    php7_fpm_systemd_interval: 10

By default a standard pool is configured as well:

    php7_fpm_pool_enabled: yes

The pool name will determine the filename (with `.conf` appended):

    php7_fpm_pool_name: www

The following variables configure that standard pool (when enabled)

    php7_fpm_pool_user: www-data
    php7_fpm_pool_group: www-data
    php7_fpm_pool_listen: "/run/php/php{{ php7_version }}-fpm.sock"
    php7_fpm_pool_listen_backlog: 512
    php7_fpm_pool_listen_owner: "{{ php7_fpm_pool_user }}"
    php7_fpm_pool_listen_group: "{{ php7_fpm_pool_group }}"
    php7_fpm_pool_listen_mode: "0660"
    php7_fpm_pool_listen_acl_users: ~
    php7_fpm_pool_listen_acl_groups: ~
    php7_fpm_pool_listen_allowed_clients: ~
    php7_fpm_pool_pm: dynamic
    php7_fpm_pool_pm_max_children: 5
    php7_fpm_pool_pm_start_servers: 2
    php7_fpm_pool_pm_min_spare_servers: 1
    php7_fpm_pool_pm_max_spare_servers: 3
    php7_fpm_pool_pm_process_idle_timeout: 10s
    php7_fpm_pool_pm_max_requests: 512
    php7_fpm_pool_status_path: /status
    php7_fpm_pool_ping_path: /ping
    php7_fpm_pool_ping_response: pong
    php7_fpm_pool_access_log: ~
    php7_fpm_pool_access_format: ~
    php7_fpm_pool_slowlog: ~
    php7_fpm_pool_request_slowlog_timeout: 0
    php7_fpm_pool_request_terminate_timeout: 0
    php7_fpm_pool_rlimit_files: ~
    php7_fpm_pool_rlimit_core: ~
    php7_fpm_pool_chroot: ~
    php7_fpm_pool_chdir: /var/www
    php7_fpm_pool_catch_workers_output: yes
    php7_fpm_pool_clear_env: yes
    php7_fpm_pool_security_limit_extensions: .php
    php7_fpm_pool_env: {}
    php7_fpm_pool_php_admin_value: {}
    php7_fpm_pool_php_value: {}

Dependencies
------------

None.

Example Playbook
----------------

    - hosts: servers
      roles:
        - { role: f500.php7, php7_version: 7.3, php7_enable_fpm: yes }

License
-------

Copyright (C) 2017 Future500 B.V.

[LGPL-3.0](https://github.com/f500/ansible-php7/blob/master/COPYING.LESSER)

Author Information
------------------

Jasper N. Brouwer, jasper@future500.nl

Ramon de la Fuente, ramon@future500.nl
