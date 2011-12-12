# PHP css-xfire handler

## Handle live css manipulation from firebug to modify css source files.

### how-to

* clone the project

    git clone http://github.com/docteurklein/css-manipulator.git css-manipulator
    cd css-manipulator
    git submodule update --init

    echo "<?php $dirs = array(__DIR__.'/css');" > dirs.php

* make the index.php file accessible at something like `http://css.local:8080`

* configure css-xfire extension
  * launch firefox
  * install firebug
  * [install css-xfire](http://code.google.com/p/css-x-fire/wiki/Installation#Details)
  * open ``about:config``
  * configure ``extensions.cssxfire@cssxfire.*`` keys to fit your config

* relaunch firefox
* edit your css live!
* observe the diff of your css files located in  ``__DIR__.'/css'`` (depending on what you put in ``dirs.php``)
