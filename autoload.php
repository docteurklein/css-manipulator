<?php

require 'vendor/css-parser/CSSParser.php';
require 'CssManipulator.php';
require 'vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony\\Component'          => __DIR__.'/vendor',
));
$loader->register();
