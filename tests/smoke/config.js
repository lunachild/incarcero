{
    /*
     * incarcero Example Configuration File
     *
     * Uncomment a specific section of the file to trigger a particular feature.
     *
     * Paths should be written using forward slashes even on Windows.
     * For ex: C:/Tools
     */

    // This allows you to use a local filestore for ISOs.
    // For all versions of Windows except Windows 10 you will need this.
    "iso_path": "/isos/",

    // Trial or registered version?
    // If using a registered product update the product_key and set trial to 'false'.
    "trial": "true",
    //"trial": "false",
    //"product_key": "9DYFR-MB64K-K9QFT-MKH79-2TVY4",
    //"product_key": "GFYY9-G6TJ7-2DK27-BY2G3-HWBK3",

    // VM settings
    "username": "incarcero",
    "password": "incarcero",
    "computername": "smoketest",
    // disk size is in megabytes
    "disk_size": "40960",

    // Windows Defender: true means enabled, false means disabled. Default is false.
    //"windows_defender": "false",

    // This example profile will attempt to load profiles/maldoc.js
    // For more information on profiles check an example profile:
    // https://github.com/0x48piraj/incarcero/blob/master/incarcero/profile-example.js
    //"profile": "maldoc",
    //"input_locale": "fr-FR",

    // Provision settings
    // Which Hypervisor for privisoning and deployment? (Options are: "virtualbox" and "vsphere") Default is "virtualbox"
    //"hypervisor": "kvm",

    // Chocolatey packages to install on the VM
    // FIXME made install go faster to prevent jenkins / packer bug when building Windows 7 machines (see #108)
    //"choco_packages": "sysinternals windbg wireshark 7zip putty",

    // Setting the IDA Path will copy the IDA remote debugging tools into the guest
    //"ida_path": "/path/to/your/ida",

    // Setting Tools Path will copy all the files under the given path into the guest.
    // Useful to copy proprietary or unpackaged tools.
    // Note: packer's file provisonning is really slow, avoid having more than
    // 100 megabytes in there.
    //"tools_path": "/path/to/your/tools",

    "_comment": "last line must finish without a comma for file to be valid json"
}
