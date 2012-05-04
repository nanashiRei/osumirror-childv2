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


Installation
------------

1. Install git if you don't already have it
2. As osufiles user run: "git clone https://bitbucket.org/nanashiRei/osumirror-childv2.git" 
   in the home directory of that user (Should be /home/osufiles in most cases)
3. Then move everything from the created subdirectory to the home of the osufiles user
4. Make sure everything is owned by the user and the group of the user osufiles
5. ~/config needs to be writable by the webserver
6. Rename config.sample.ini to config.ini and configure it
7. Contact nanashiRei ( nanashi.rei at gmail dot com ) and ask for futher instructions