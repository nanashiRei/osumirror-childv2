osu!Mirror Child V2
===================

Goals
-----

* OOP based source
* Easy to extend and install
* No dependencies to other libraries (minimal installation requirements)
* Work with apache2 (x-sendfile), nginx (x-accell-redirect) and everything else (php file proxy)
* More stats and system tests implemented (for the owner to play with)
* many more...

Requirements
------------

* Apache2 or nginx (other httpds might work, too)
* PHP 5.3+ with at least mcrypt and sqlite3 support
* 100 mbit connection with at least 75 mbit bandwidth available
* A working version of git needs to be installed for easy updating 

Installation
------------

Lines starting with $ are command that you should run as the user that'll be running the script.

1.  Install git if you don't already have it
2.  Create a new user (preferably: osufiles)
3.  Switch to the new user
4.  Run these commands

		$ cd ~
	   	$ git clone https://bitbucket.org/nanashiRei/osumirror-childv2.git 
		$ cp -rf ./osumirror-childv2/* ./
		$ chmod 777 ./config
		$ cp ./config/config.sample.ini ./config.ini

5.  Configure the config.ini file
6.  Generate a privat/public keypair

		$ ssh-keygen -t dsa -b 1024
		
7.  Contact nanashiRei ( nanashi.rei at gmail dot com ) and ask for futher instructions.
    Don't forget to attach the id_dsa.pub file to the email. It is required for automatic
   	syncronization of files between the master server and the child server. 

