<?php
// Crude.
require './setup.php';
if(!defined('XML_HTMLSAX3'))
  define('XML_HTMLSAX3', 'XML/');

require_once(XML_HTMLSAX3 . '/HTMLSax3.php');
require_once('./src/lmbMacroTemplateHighlightHandler.class.php');

function contains($str, $sub) {
    return gettype(strpos($str, $sub)) == "integer";
}

if(!isset($_GET['file']))
  die('file param is not set!');

$file = $_GET['file'];

if (contains($file, "..") || contains($file, "//") || (substr($file, 0, 1) == '/')) {
    exit;
}

$filename = './examples/' . $file;

if(!file_exists($filename))
  exit;

$page = new lmbMacroTemplate('macrofile.html');
    
$parser = new XML_HTMLSax3();

$handler = new lmbMacroTemplateHighlightHandler();

$parser->set_object($handler);

$parser->set_element_handler('openHandler','closeHandler');
$parser->set_data_handler('dataHandler');
$parser->set_escape_handler('escapeHandler');
$parser->set_pi_handler('processPHPCode');

$parser->parse(file_get_contents($filename));

$html = $handler->getHtml();

$page->set('template_path', $file);
$page->set('template_content', $html);
echo $page->render();

?>
