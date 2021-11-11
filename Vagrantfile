# -*- mode: ruby -*-
# vi: set ft=ruby :
VAGRANTFILE_API_VERSION = "2"
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
	config.vm.box = "bento/ubuntu-20.04"
	config.vm.hostname = "poeuniqueladder"
	config.ssh.insert_key = false
	config.vm.provision :shell, :path => "setup.sh"
	config.vm.network :forwarded_port, guest: 443, host: 443
	config.vm.network :forwarded_port, guest: 80, host: 80
	config.vm.provider :virtualbox do |vb|
		vb.customize ["modifyvm", :id, "--memory", "1024"]
		vb.customize [
			"setextradata",
			:id,
			"VBoxInternal2/SharedFoldersEnableSymlinksCreate/v-root",
			"1"
		]
	end
	config.vm.synced_folder ".", "/var/www/poeuniqueladder", owner: "www-data", group: "www-data"
end
