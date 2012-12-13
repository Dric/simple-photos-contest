[Simple Photos Contest](https://github.com/Dric/contests-gallery)
==========

A Simple photos contest gallery. Vote for your favorites photos.

Requirements
------------

+ PHP 5.3
+ MySQL 5.5
+ Gettext enabled (for translations)
+ GD Image Library

There is nothing unusual, as GD and gettext are enabled on most of web hosting platforms. Simple Photos Contest could work with previous versions of PHP and MySQL, but it hasn't been tested.

Installation
------------

+ Make the cache dir writable (777).
+ import install.sql in your mysql db.
+ Rename config-sample.php into config.php.
+ Edit config.php to change db connect values.

Quick Start
-----------

+ Create directory in your photos dir.
+ Put your photos into this dir.
+ Activate it in admin panel.
+ Enjoy !

FAQ
---

<dl>
	<dt>Where are the contests settings ?</dt>
	<dd>There is small icon in upper right corner of the main page. Just click on it, enter password and you will get the admin panel</dd>
	<dt>How can I translate Simple Photos contest ?</dt>
	<dd>There is a pot template file in lang dir, then mimic the french translation files tree.</dd>
</dl>


Bug tracker
-----------

Have a bug? Please create an issue here on GitHub!

https://github.com/Dric/contests-gallery/issues


Author
-------

**Dric**

+ http://www.driczone.net
+ http://twitter.com/Dric
+ http://github.com/Dric


Copyright and license
---------------------

The MIT License (MIT)

Copyright (c) 2012 Dric

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.