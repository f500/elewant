# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

    config.vm.define "dev", primary: true do |config_dev|
        config_dev.vm.box = "f500/debian-wheezy64"

        config_dev.vm.network :private_network, ip: "192.168.77.77"
        config_dev.vm.synced_folder ".", "/vagrant", type: "nfs"

        config_dev.vm.provider :virtualbox do |vb|
            vb.name = "elewant_dev"
        end

        config_dev.vm.provision :ansible do |ansible|
            ansible.inventory_path = "ansible/hosts-vagrant"
            ansible.playbook       = "ansible/provision.yml"
            ansible.limit          = "vagrant"
        end
    end

    # This box is added to test provision and deploy scripts agains a simulated "production" environment.
    # It is not needed for regular development on the elewant website.
    #
    config.vm.define "prodsim", autostart: false do |config_prodsim|
        config_prodsim.vm.box = "f500/debian-jessie64"
        config_prodsim.vm.network :private_network, ip: "192.168.77.78"

        # No synched folder, this is a simlation of production after all!
        config.vm.synced_folder ".", "/vagrant", disabled: true

        config_prodsim.vm.provider :virtualbox do |vb|
            vb.name = "elewant_prodsim"
        end

        # Ansible is only used to create a user, the rest is done on the commandline
        # with ansible-playbook commands.
        config_prodsim.vm.provision :ansible do |ansible|
            ansible.inventory_path = "ansible/hosts-prodsim"
            ansible.playbook       = "ansible/setup_local_production_server.yml"
            ansible.limit          = "production"
            ansible.raw_arguments  = "--user=vagrant"
            ansible.raw_arguments  = "--ask-vault-pass"
            ansible.host_key_checking  = "False"
        end
    end

end
