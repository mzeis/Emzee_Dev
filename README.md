Emzee_Dev
=========
Provides a handy object inspection method for Magento developers. 

Facts
-----
- version: 1.0.0
- extension key: Emzee_Dev
- [extension on GitHub](https://github.com/mzeis/Emzee_Dev)

Usage
-----
To be honest, I had forgotten there is any other method besides info() in Emzee_Dev.
I guess that most of the other functionalities is covered by [FireGento_Debug](https://github.com/firegento/firegento-debug) and others now.
I'll list them here for the sake of completeness.

### info()

The most useful function is `info()`. You can call it using the model or the helper:

    # using a singleton model
    echo Mage::getSingleton('emzee_dev/debug')->info($variable);
    
    # using a helper
    echo Mage::helper('emzee_dev')->info($variable);
    
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
- Magento CE 1.7 - 1.9 (only tested here; initial version worked with 1.3 CE, so you may try it with older versions)

Installation Instructions
-------------------------
1. Install the extension using [modman](https://github.com/colinmollenhour/modman) or copy all the files into your
   document root. If you copy the files yourself copy `app/design/frontend/base/default/template/emzee_dev/` to
   `app/design/adminhtml/default/default/template/emzee_dev/` manually.

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
(c) 2014 Matthias Zeis
