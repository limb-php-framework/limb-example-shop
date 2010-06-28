<?php
require_once './src/examples_utils.inc.php';

/**
 * Tree structure implemented with an array
 * for best performances.
 */
$tree = array('name'  => 'examples',
              'path'  => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'examples' . DIRECTORY_SEPARATOR,
              'group' => '');

$group = (empty($_GET['group']) ? '' : $_GET['group']);

if (!empty($group))
{
  $groups = explode('/', $_GET['group']);
  array_pop($groups);
  $tree['name']  = array_pop($groups);
  $tree['group'] = $_GET['group'];
  $tree['path']  = $tree['path'].$_GET['group'];
}

createTree($tree, 3); // one level deep tree. Change the 2nd parameter ad libitum.

$page = new lmbMacroTemplate('index.html');
$page->set('crumbs', get_breadcrumbs($group));
$page->set('tree', treeToHtml($tree));
echo $page->render();
?>