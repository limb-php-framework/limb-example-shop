<?php
require_once './src/examples_utils.inc.php';

$group = (empty($_GET['group']) ? '' : $_GET['group']);

$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'examples' . DIRECTORY_SEPARATOR . $group . 'desc.ini';

if (!is_file($file))
{
  echo '<b>Error - file desc.ini does not exists</b>';
  echo '<p><a href="./index.php">back to examples</a></p>';
  exit;
}

$page = new lmbMacroTemplate('example.html');
$page->set('crumbs', get_breadcrumbs($group));
$data = getDescriptionFileData($file, $group);
$page->set('examples', $data['examples']);
$page->set('title', $data['title']);
echo $page->render();

?>