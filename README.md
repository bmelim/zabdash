# ZabDash
> Dashboard for Zabbix

![](https://repository-images.githubusercontent.com/70854481/3cc6d100-884e-11e9-82f0-821e44ba5d40)


1 - Copy zabdash folder to Zabbix folder (/usr/share/zabbix);

2 - Copy config.php.sample to config.php;

3 - Edit config.php with your server settings;

4 - Set Automatic Hosts inventory in Zabbix;

5 - Set permissions of zabdash folder to the same os Zabbix folder;

6 - Access URL http://your_zabbix_server/zabbix/zabdash;


To add a menu item for ZabDash see README.txt file in menu folder.



## Zabbix API Needs php-posix.

In debian/ubuntu is in php-common package

yum install php-process - redhat/centos

zypper install php-posix - OpenSuse

