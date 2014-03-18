require "json"

root = File.dirname(__FILE__)
parent = File.basename(root)
hostname = "fsm.dev"

Vagrant.configure("2") do |config|

    config.vm.network :private_network, ip: "172.28.128.3"

    config.vm.box = "opscode-ubuntu-12.04_chef-11.4.0"
    config.vm.box_url = "https://opscode-vm-bento.s3.amazonaws.com/vagrant/opscode_ubuntu-12.04_chef-11.4.0.box"

    config.vm.hostname = hostname

    config.vm.synced_folder ".", "/vagrant", :mount_options => ["dmode=777","fmode=777"]

    config.vm.provision :shell, :path => "vagrant.sh"

    config.vm.provider "virtualbox" do |v|
        v.name = hostname
    end
end
