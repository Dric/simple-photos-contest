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

+ PHP 5.2
+ MySQL 5.5
+ Gettext enabled (for translations)
+ GD Image Library

There is nothing unusual, as GD and gettext are enabled on most of web hosting platforms. Simple Photos Contest may work with previous versions of PHP and MySQL, but it hasn't been tested.

Runs well on IE 8+, FF, Opera, Chrome (and probably Safari, but it hasn't been tested). Javascript is required.

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

Quick Start
-----------

+ Create directory in your photos dir.
+ Put your photos into this dir.
+ Activate it in admin panel.
+ The first load of a contest page is slow. No panic, it's because TimThumb needs to create thumbnails of all the gallery photos. The next load will be a lot faster !

FAQ
---

<dl>
	<dt>Where is the admin panel ?</dt>
	<dd>There is small icon in upper right corner of the main page. Just click on it, enter password and you will get the admin panel.</dd>
	<dt>How can I translate Simple Photos contest ?</dt>
	<dd>There is a pot template file in lang dir. Edit it with a po editor like [PoEdit](http://www.poedit.net) in Windows, then mimic the french translation files tree.</dd>
</dl>

TO-DO
-----

+ Display stats in frontend (with setting to disable it)

Components used
---------------

+ [TimThumb](http://www.binarymoon.co.uk/projects/timthumb/) 2.8.11
+ [jQuery](http://jquery.com) 1.8.2
+ [Freetile](https://github.com/yconst/Freetile) (customized)
+ [SlimBox 2.04](http://www.digitalia.be/software/slimbox2) (customized)
+ [Zebra DatePicker](http://stefangabos.ro/jquery/zebra-datepicker/) 1.6.2
+ [jqBarGraph](http://workshop.rs/jqbargraph/) 1.1
+ [Tiny Scrollbar](http://baijs.nl/tinyscrollbar/) 1.81

Bug tracker
-----------

Have a bug? Being shocked by a poor translation (I'm french) ? Please create an issue here on GitHub !

<https://github.com/Dric/contests-gallery/issues>


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

Copyright (c) 2012 Dric

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.