<?php
/**
 * require framework
 */
require_once './setup.php';

/**
 * path
 */
$tmp_arr = explode('/', $_SERVER['PHP_SELF']);
$examples_root_file = array_pop($tmp_arr);
$examples_root_dir  = implode('/', $tmp_arr);
$examples_root_dir  = 'http://'.$_SERVER['HTTP_HOST'].$examples_root_dir.'/';
unset($tmp_arr);

// -------------------------------------------------------------

/**
 * @return array file & subdir listing, without "." and ".."
 */
function getDirContents($dir)
{
  $dir = rtrim($dir, '/\\');
  $files = scandir($dir);
  if (!is_array($files)) {
      return false;
  }
  //strip . and ..
  array_shift($files);
  array_shift($files);
  return $files;
}

// -------------------------------------------------------------

/**
 * @param array file list
 * @param string path
 */
function stripFiles($dirContents, $path)
{
  $dirs = array();
  foreach ($dirContents as $file)
  {
    if (is_dir($path . DIRECTORY_SEPARATOR . $file))
      $dirs[] = $file;
  }

  $ignored = array('.svn', 'templates');

  if (file_exists($path. DIRECTORY_SEPARATOR . 'ignore.ini'))
      $ignored = array_merge($ignored, parse_ini_file($path . DIRECTORY_SEPARATOR . 'ignore.ini'));

  foreach ($dirs as $k => $dir)
  {
    if (in_array($dir, $ignored))
      unset($dirs[$k]);
  }

  return $dirs;
}

// -------------------------------------------------------------

/**
 * @param reference to array
 * @param integer number of levels to scan
 */
function createTree(& $node, $levels = 1)
{
  if ($levels < 1)
      return;

  $dirContents = getDirContents($node['path']);
  if ($dirContents == false)
      return;

  if (!in_array('desc.ini', $dirContents))
  {
    $subdirs = stripFiles($dirContents, $node['path']);
    foreach ($subdirs as $subdir)
    {
      $child = & addChild($node, $subdir);
      createTree($child, $levels - 1);
    }
  }
}

// -------------------------------------------------------------

/**
 * @param reference to array (tree node)
 * @param string name of the node
 */
function & addChild(&$node, $name)
{
  $node['children'][$name] = array('name'  => $name,
                                   'path'  => $node['path'] . $name . DIRECTORY_SEPARATOR,
                                   'group' => $node['group'] . $name . '/',
                                   'children' => array());

  return $node['children'][$name];
}

// -------------------------------------------------------------

function treeToHtml(&$node)
{
  $html = '<li>';
  if (!file_exists($node['path'] . 'desc.ini'))
  {
    $html .= '<a href="index.php?group='.$node['group'].'">'.$node['name'].'</a>';
    $html .= '<ul>';
    if (array_key_exists('children', $node) && is_array($node['children']))
    {
      foreach ($node['children'] as $child)
          $html .= treeToHtml($child);
    }
    $html .= '</ul>';
  }
  else
  {
    $html .= '<a href="show.php?group='.$node['group'].'">'.$node['name'].'</a>';
  }
  $html .= '</li>';
  return $html;
}

// -------------------------------------------------------------

function get_breadcrumbs($path)
{
  $nodes = array();
  $dirs = explode('/', $path);
  $dirs = array_filter($dirs, 'purge_empty');

  while (count($dirs))
  {
    $nodes[] = array('path' => implode('/', $dirs).'/',
                     'name' => array_pop($dirs));
  }

  //add examples root
  $nodes[] = array('path' => '',
                   'name' => 'examples');

  return array_reverse($nodes);
}

// -------------------------------------------------------------

function purge_empty($var) {
    return !empty($var);
}


function getDescriptionFileData($desc_file, $root)
{
  $data = parse_ini_file($desc_file, true);

  $newData = array();
  foreach ($data as $name => $row)
  {
    $srcfiles = array();

    if(!is_array($row) && $name == 'title')
    {
      $newData['title'] = $row;
      continue;
    }

    foreach ($row as $key => $file)
    {
      if (is_numeric($key))
        $srcfiles[] = array('path' => "./showtemplate.php?file=$root{$file}",
                            'file' => $file);
    }

    $newData['examples'][] = array('description' => $row['description'],
                                   'exec' => "./run.php?file=$root{$row['php']}",
                                   'compiled' => "./compiled.php?file=$root{$row['template']}",
                                   'php_file' => array('path' => "./showsource.php?file=$root{$row['php']}",
                                                       'file' => $row['php']),
                                   'templates' => $srcfiles);
  }

  return $newData;
}
?>