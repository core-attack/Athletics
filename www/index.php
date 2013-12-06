<?php
ini_set('default_charset', 'UTF-8');
error_reporting(1);
define('ROOT', dirname(dirname(__FILE__)));

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

set_include_path(
    ROOT . DS .'library'. PATH_SEPARATOR
    . ROOT . DS .'application'. PATH_SEPARATOR
    . get_include_path()
);

require_once('controllers/MainController.php');

$main = new Main();
