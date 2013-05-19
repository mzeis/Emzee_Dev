Emzee_Dev
=========
Provides a handy object inspection method for Magento developers. 

Facts
-----
- version: 0.0.1
- extension key: Emzee_Dev
- [extension on GitHub](https://github.com/mzeis/Emzee_Dev)

Prolog
------
In the mid of 2010, in my very first days of Magento development, I needed a
tool to inspect those monstrous Magento objects. All the nice extensions,
PhpStorm integrations and the like were quite a few moons away, so I sat down
for a few hours and wrote a small class called Emzee_Dev.

Fast-forward 3 years. It's May 2013 and I'm still using my precious little
helper. Although many great tools exist by now, it seems there still is a place
for Emzee_Dev. When people see me inspecting the output of my `info()` method
they ask where they can get that extension. "I can give it to you, but before
I can publish it, I have to clean it up. The code is messy." is my usual answer.

Well, you can't always sit on your code. Especially when you want to get it out
there but don't manage to rewrite that few lines of code in nearly three years.
That's why I invested two minutes to turn my temporary static Emzee_Debug class
into a Magento extension called Emzee_Debug.

It was meant as (and was) a class you can throw in anywhere and use it without
making a big fuss about it. So don't be surprised if you find some static methods
or code that is also covered by Magento methods. I may clean that up.

Usage
-----
To be honest, I had forgotten there is any other method besides info() in Emzee_Dev.
I guess that most of the other functionalities is covered by [FireGento_Debug](https://github.com/firegento/firegento-debug) and others now.
I'll list them here for the sake of completeness.

### info()

The mose useful function is `info()`. It can be called in different ways:

    # using a singleton model
    echo Mage::getSingleton('emzee_dev/debug')->info($this);
    
    # using a helper
    echo Mage::helper('emzee_dev')->info($this);
    
    # using the class directly (bad practice - boo!)
    echo Emzee_Dev_Model_Debug::info($this);
   
`info()` is intended to be used with objects. Providing info() with other types will
get you a simple `print_r()` output.

For objects, you get

* A **graphical presentation** of the public methods that exits in the class and its parent
  classes.
  - You can see which class defines the method.
  - Hover over the method name to see the defining class.
  - Click on the method name to see the method declaration (variables, default values).
* The names of the parent and child blocks if the object is a **block class**.
* A nice **backtrace** showing the content of the files next to the called line.

Here is a screenshot so you get a better picture:

![Emzee_Dev info() call](https://github.com/mzeis/Emzee_Dev/raw/master/app/code/community/Emzee/Dev/documentation/screenshot-info.png)

### configInfo()

* Prints the absolute pathes of various Magento folders.
* Prints a list of all modules with status, code pool, dependencies and version.

### memoryUsage()

Returns current memory usage in human readable format.

### modelInfo()

Returns information on models:

* which model groups are defined in config XML files (node global > models)
* what does that model group get translated to (e.g. "admin" => "Mage_Admin_Model")
* if it is a "normal" model: what is the resource model for this model
* if it is a resource model: which entities do exist for this model

Compatibility
-------------
- Magento 1.7 CE (only tested here; initial version worked with 1.3 CE, so you may try it with older versions)

Installation Instructions
-------------------------
1. Install the extension using modman or copy all the files into your document root.

Uninstallation
--------------
1. Remove the extension like all other extensions you install using modman.

Changelog
---------

See [Changelog](https://github.com/mzeis/Emzee_Dev/blob/master/CHANGELOG.md).

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/mzeis/Emzee_Dev/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Matthias Zeis
[http://www.matthias-zeis.com](http://www.matthias-zeis.com)  
[@mzeis](https://twitter.com/mzeis)

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2013 Matthias Zeis