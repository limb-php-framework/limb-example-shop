<?php
// Crude.
require './setup.php';

function contains($str, $sub) {
    return gettype(strpos($str, $sub)) == "integer";
}

if(!isset($_GET['file']))
  die('file param is not set!');

$file = $_GET['file'];

if (contains($file, "..") || contains($file, "//") || (substr($file, 0, 1) == '/'))
  exit;

$filename = dirname(__FILE__) . '/examples/' . $file;

if(!file_exists($filename))
  exit;

set_include_path(dirname($filename) . '/' . PATH_SEPARATOR .
                 get_include_path());

include($filename);
?>
