Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.hostname = 'sprinkler.dev'
  config.vm.network "private_network", :auto_network => true
  config.vm.provision :shell, path: "provision.sh"

  # Uncomment below on Mac to have more responsive NFS.
  # config.vm.synced_folder ".", "/vagrant",
  #   :type => :nfs,
  #   :mount_options => ['nolock,vers=3,udp,noatime,actimeo=1']
end