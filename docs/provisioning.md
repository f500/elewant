Before Provision
================

Make sure before you provision your ansible roles are up-to-date!

    ansible-galaxy install -r ansible/galaxy_roles.yml -f

First time production server setup
==================================

Login into the server and create a provision user

    sudo adduser provision

Run `visudo` to give provision user sudo rights

    provision ALL=NOPASSWD: ALL

    mkdir /home/provision/.ssh
    chmod 700 /home/provision/.ssh
    vim /home/provision/.ssh/authorized_keys (add your is_rsa.pub key)
    chmod 600 /home/provision/.ssh/authorized_keys
    chown provision:provision /home/provision/.ssh -R

Provisioning Production
=======================

    ansible-playbook -i ansible/hosts-provision ansible/provision/playbook.yml --limit=production --ask-vault-pass
