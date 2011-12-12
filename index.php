<?php

require 'autoload.php';
require 'dirs.php';

use Symfony\Component\Finder\Finder;

$finder = new Finder;

$finder
    ->files()
    ->name('*.css')
    ->in($dirs); // coming from dirs.php
;

$rule= new CSSRule(@$_GET['property']);
$rule->setValue(@$_GET['value']);

// $params is coming from firebug's css-xfire extension
$params = array(
    'selector' => new CSSSelector(@$_GET['selector']),
    'rule'     => $rule,
    'property' => @$_GET['property'],
    'value'    => @$_GET['value'],
    'deleted'  => @$_GET['deleted'] === 'true',
    'href'     => @$_GET['href'],
    'media'    => @$_GET['media'],
);

$fd = fopen('css.log','a');
$logger = function($log) use($fd) {
    fwrite($fd, $log);
};

foreach($finder as $file) {
    $parser = new CSSParser(file_get_contents((string)$file));
    $css = $parser->parse();

    $manipulator = new CssManipulator($css, $logger);

    if($params['deleted']) {
        if($params['value']) {
            $manipulator->removeRule($params['selector'], $params['rule']);
        }
        else {
            $manipulator->removeDeclarationBlocks($params['selector']);
        }
    }
    else {
        $manipulator->addRule($params['selector'], $params['rule']);
    }

    $manipulator->save((string)$file);
}
