<?php 
// Define the OS specific directory separator
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

// Define project directory
if (!defined('ROOT')) define('ROOT', realpath(__DIR__ . '/..'));

// Turn error reporting off
//error_reporting(0);

// Turn on session
session_start();

// Turn on output buffering
ob_start();

// Load settings
require_once (implode(DS, array(ROOT, 'config', 'config.php')));

// Load classes
foreach (glob(implode(DS, array(ROOT, 'controller', 'class', '*.php'))) as $filename)
{
    require_once ($filename);
}