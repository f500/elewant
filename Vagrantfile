# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.define "elewant_develop", primary: true do |config_develop|
        config_develop.vm.box = "f500/debian-stretch64"

        config_develop.vm.hostname = "develop.elewant.loc"

        config_develop.vm.network :private_network, ip: "192.168.77.77"

        config_develop.vm.synced_folder ".", "/vagrant", nfs: true

        config_develop.vm.provider :virtualbox do |vb|
            vb.name = "elewant_develop"
            vb.cpus = "1"
            vb.memory = "1024"
            vb.gui = false

            vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
            vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        end

        config_develop.vm.provision :ansible do |ansible|
            ansible.compatibility_mode = "2.0"
            ansible.inventory_path = "ansible/hosts-provision"
            ansible.playbook = "ansible/provision/playbook.yml"
            ansible.limit = "develop"
        end
    end

    # This box is added to test provision and deploy scripts agains a simulated
    # "production" environment. It is not needed for regular development on the
    # elewant website.

    config.vm.define "elewant_prodsim", autostart: false do |config_prodsim|
        config_prodsim.vm.box = "f500/debian-stretch64"

        config_prodsim.vm.hostname = "prodsim.elewant.loc"

        config_prodsim.vm.network :private_network, ip: "192.168.77.78"

        # No synched folder, this is a simlation of production after all!
        config_prodsim.vm.synced_folder ".", "/vagrant", disabled: true

        config_prodsim.vm.provider :virtualbox do |vb|
            vb.name = "elewant_prodsim"
            vb.cpus = "1"
            vb.memory = "1024"
            vb.gui = false

            vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
            vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        end

        # Ansible is only used to create a user, the rest is done on the
        # commandline with ansible-playbook commands.
        config_prodsim.vm.provision :ansible do |ansible|
            ansible.compatibility_mode = "2.0"
            ansible.inventory_path = "ansible/hosts-provision"
            ansible.playbook = "ansible/setup_local_production_server.yml"
            ansible.limit = "prodsim"
            ansible.raw_arguments = "--user=vagrant"
            ansible.host_key_checking = "False"
        end
    end
end
