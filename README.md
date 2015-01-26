[Simple Photos Contest](https://github.com/Dric/simple-photos-contest)
==========

A Simple photos contest gallery. Vote for your favorites photos.

Screenshots
-----------
![Frontend 1](https://raw.github.com/Dric/simple-photos-contest/master/install/img/front1.jpg "Frontend 1")
Look at the small cog icon in upper right corner : this is the settings button.

![Frontend 2](https://raw.github.com/Dric/simple-photos-contest/master/install/img/front2.jpg "Frontend 2")
Photos are automatically tiled. This can be a bit messy.

![Admin 1](https://raw.github.com/Dric/simple-photos-contest/master/install/img/admin1.jpg "Admin 1")
Admin panel - Contests list.

![Admin 2](https://raw.github.com/Dric/simple-photos-contest/master/install/img/admin2.jpg "Admin 2")
Admin panel - SPC settings bar.

Requirements
------------

+ PHP 5.3
+ MySQL 5.5
+ Gettext enabled (for translations)
+ GD Image Library

There is nothing unusual, as GD and gettext are enabled on most of web hosting platforms. Simple Photos Contest may work with previous versions of MySQL, but it hasn't been tested.

Runs well on a decent Internet Browser that support HTML5. Javascript is required.

Installation
------------
* Automatic install (recommended)
	1. Make the cache dir writable (777).
	2. Open the SPC url in your favorite browser.
	3. You will be redirected to the SPC installer.
	4. Follow the installer steps.
* Manual install : 
	1. Make the cache dir writable (777).
	2. import install/install.sql in your mysql db.
	3. Rename config-sample.php into config.php.
	4. Edit config.php to change db connect values and admin password.
	5. Configure SPC in admin panel.


Update from 1.x versions
------------------------
Run SQL commands :

    ALTER TABLE `contests` ADD `voting_type` VARCHAR( 10 ) NOT NULL DEFAULT "open" ;
    ALTER TABLE `image_ip` CHANGE `ip_add` `ip_add` INT NULL DEFAULT NULL;

Changelog
---------
* 2.0
	- Major rewrite : frontend now use HTML5 and CSS3
	- Used of LESS files to build CSS file
	- Use fingerprinting method instead of IP address to detect if a visitor has already voted.
	- ZebraDatePicker replaced by glDatePicker
* 1.3
	- `mysql_*` obsolete php functions replaced by `mysqli_*` functions.
	- When `config.php` file is not found, user will be redirected to install script.
	- Link to admin panel in breadcrumb
	- When contests are saved in Db but no default contest is set, SPC will display the first contest saved in Db
	- Better SQL error check
	- A few bugs fixed
	- Updated jQuery to v1.11.2
	- Updated freeTile to v0.3.1
	- Custom Slimbox2 replaced by LightBox
* 1.2
  - Use of SimpleImage class instead of TimThumb
  - Add a setting to allow voting once in a contest or unlimited votes (you are still not allowed to vote more than once for a photo)

Quick Start
-----------

+ Create directory in your photos dir.
+ Put your photos into this dir.
+ Activate it in admin panel.
+ The first load of a contest page is slow. No panic, it's because SPC needs to create thumbnails of all the gallery photos. The next load will be a lot faster !

FAQ
---

<dl>
	<dt>Where is the admin panel ?</dt>
	<dd>There is small icon in upper right corner of the main page. Just click on it, enter password and you will get the admin panel.</dd>
	<dt>How can I translate Simple Photos contest ?</dt>
	<dd>There is a pot template file in lang dir. Edit it with a po editor like [PoEdit](http://www.poedit.net) in Windows, then mimic the french translation files tree.</dd>
	<dt>How can I change SPC CSS ?</dt>
  <dd>You should modify SPC LESS files (check `variables.less` first, as most of colors and sizes are defined here) and compile it to a CSS file.</dd>
</dl>

TO-DO
-----

+ Use of responsive template
+ Use of HTML5 + CSS3 to replace jQuery animations

Components used
---------------

+ [SimpleImage](https://github.com/claviska/SimpleImage) 2.4
+ [jQuery](http://jquery.com) 1.11.2
+ [Freetile](https://github.com/yconst/Freetile) 0.3.1
+ [Lightbox](http://lokeshdhakar.com/projects/lightbox2) 2.7.1
+ [glDatePicker](http://glad.github.io/glDatePicker) 2.0
+ [jqBarGraph](http://workshop.rs/jqbargraph/) 1.1
+ [Tiny Scrollbar](http://baijs.nl/tinyscrollbar/) 1.81

Bug tracker
-----------

Have a bug? Being shocked by a poor translation (I'm french) ? Please create an issue here on GitHub !

<https://github.com/Dric/simple-photos-contest/issues>


Author
-------

**Dric**

+ Blog : <http://www.driczone.net>
+ Twitter : <http://twitter.com/Dric>
+ Github : <http://github.com/Dric>


Copyright and license
---------------------

Simple Photos Contest is released under MIT licence.

The MIT License (MIT)

Copyright (c) 2012-2015 Dric

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.