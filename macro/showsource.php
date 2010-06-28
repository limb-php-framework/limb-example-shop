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

$filename = './examples/' . $file;

if(!file_exists($filename))
  exit;

$page = new lmbMacroTemplate('phpfile.html');
$page->set('Filename', $file);

$highlighted_string = highlight_string(file_get_contents($filename), true);

$page->set('SourceCode', $highlighted_string);
echo $page->render();
?>
