Before Provision
================

Make sure before you provision your ansible roles are up-to-date!

    ansible-galaxy install -r ansible/ansible-galaxy.yml -f

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

    ansible-playbook -i ansible/hosts ansible/provision/provision.yml --ask-vault-pass --limit=production

Provisioning Prodsim
====================

Edit your local `hosts` file and add:

    192.168.10.201 prodsim.elewant.loc

Startup the prodsim machine

    vagrant up elewant_prodsim

After the vagrant up, the playbook `setup_local_production_server.yml` has been executed and now you are ready to provision the prodsim server.

    ansible-playbook -i ansible/hosts ansible/provision/provision.yml --ask-vault-pass --limit=prodsim

After you did a successful provision you can deploy your application to your prodsim

    ansible-playbook -i ansible/hosts ansible/deploy/deploy.yml --extra-vars "project_version=develop" --limit=prodsim --ask-vault-pass
