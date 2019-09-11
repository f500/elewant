Nginx
=====

Install nginx, configure with sane defaults, use a default server which returns 404, and optionally configure custom servers (vhosts).

Requirements
------------

Debian Wheezy/Jessie/Stretch with the package python-pycurl and python-software-properties installed.

Role Variables
--------------

All role variables are displayed with their default value.

### nginx.conf

Some basic settings for in the `nginx.conf` itself:

    nginx_user: "www-data"
    nginx_group: "www-data"
    nginx_worker_connections: 1024
    nginx_worker_processes: "{{ ansible_processor_count }}"
    nginx_pid: "/var/run/nginx.pid"
    nginx_www_dir: "/var/www"

Add or change settings in the `http` context:

    nginx_http_params: {}

This role sets a couple of settings that are viewed as best practices.
The result is the same as if configured in the following way:

    nginx_http_params:
      server_names_hash_bucket_size: 64
      server_tokens: off

      sendfile: on
      tcp_nopush: on
      tcp_nodelay: on

      gzip: on
      gzip_disable: "msie6"
      gzip_min_length: 256
      gzip_types: application/json application/vnd.ms-fontobject application/x-font-ttf application/x-javascript application/xml application/xml+rss font/opentype image/svg+xml image/x-icon text/css text/javascript text/plain text/xml

      ssl_ciphers: "ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA:ECDHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA256:DHE-RSA-AES256-SHA:ECDHE-ECDSA-DES-CBC3-SHA:ECDHE-RSA-DES-CBC3-SHA:EDH-RSA-DES-CBC3-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:DES-CBC3-SHA:!DSS"
      ssl_dhparam: "/etc/nginx/dh{{ nginx_dhparam_bits }}.pem"
      ssl_prefer_server_ciphers: on
      ssl_protocols: TLSv1 TLSv1.1 TLSv1.2
      ssl_session_cache: shared:SSL:50m
      ssl_session_tickets: off
      ssl_session_timeout: 1d
      ssl_stapling: on
      ssl_stapling_verify: on
      resolver: "{{ ansible_dns.nameservers|join(' ') }} valid=300s"

If you want to change one of these settings, add it to `nginx_http_params`.
No need to copy the entire dictionary.

Add or change headers added in the `http` context:

    nginx_http_headers: {}

This role adds a couple of headers that are viewed as best practices.
The result is the same as if configured in the following way:

    nginx_http_headers:
      Content-Security-Policy: "default-src 'self'; form-action 'self'; frame-ancestors 'none'"
      Referrer-Policy: "no-referrer, strict-origin-when-cross-origin"
      Strict-Transport-Security: max-age=15768000
      X-Content-Type-Options: nosniff
      X-Frame-Options: DENY
      X-Xss-Protection: "1; mode=block"

If you want to change one of these headers, add it to `nginx_http_headers`.
Again, no need to copy the entire dictionary.

Note that headers added to the `http` context will be ignored when you add headers in a `server` or `location` context.
So don't forget to combine all headers:

    {% for key, value in (nginx_http_headers_default | combine(nginx_http_headers) | combine(specialized_headers)).items() %}
    add_header  {{ key }} "{{ value }}"  always;
    {% endfor %}

We generate Diffie-Hellman parameters to enable Perfect Forward Secrecy.
You can change the number of bits used for this:

    nginx_dhparam_bits: 4096

### fastcgi_params

If you want to use `$realpath_root` instead of `$document_root` (when your path contains symlinks) in `fastcgi_params`, set this to `yes`:

    nginx_use_realpath_root: no

If you want to add `REDIRECT_STATUS 200` in `fastcgi_params` (when PHP uses `cgi.force_redirect`), set this to `yes`:

    nginx_php_force_cgi_redirect: no

### Servers

If you don't want to override the systems default server, set this to `no`:

    nginx_set_default_server: yes

The overridden default server simply returns status-code 404.

We do not provide templates to configure additional servers, because such configurations depend heavily on your needs.

Instead, you can create your own template, then specify a name and the source-path:

    nginx_server_templates: []

For example (using SSL and PHP), you can create a template named `nginx-server.conf.j2` that contains:

    server {
        listen       443  ssl;
        server_name  example.com;
        root         /var/www/example_com;

        access_log  /var/log/nginx/example.access.log  main;
        error_log   /var/log/nginx/example.error.log   warn;

        ssl                  on;
        ssl_certificate      /etc/nginx/ssl/example.com.chain;
        ssl_certificate_key  /etc/nginx/ssl/example.com.key;

        location / {
            try_files $uri /index.php$is_args$args;
        }

        location /index.php {
            fastcgi_pass   unix:/run/php/php-fpm.sock;
            fastcgi_index  index.php;
            include        fastcgi_params;
        }
    }

    server {
        listen       80;
        server_name  example.com;
        return       301  https://$host$request_uri;
    }

Then have this role install it:

    nginx_server_templates:
      - { name: example, src: "{{ inventory_dir }}/templates/nginx-server.conf.j2" }

Example Playbook
----------------

    - hosts: example
      roles:
      - { role: f500.nginx }

License
-------

LGPL-3.0

Author Information
------------------

Jasper N. Brouwer, jasper@nerdsweide.nl

Ramon de la Fuente, ramon@delafuente.nl
