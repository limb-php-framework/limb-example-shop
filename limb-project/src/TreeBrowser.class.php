<?php
/**********************************************************************************
* Copyright 2004 BIT, Ltd. http://limb-project.com, mailto: support@limb-project.com
*
* Released under the LGPL license (http://www.gnu.org/copyleft/lesser.html)
***********************************************************************************
*
* $Id$
*
***********************************************************************************/

class TreeBrowser
{
  protected $current_folder;

  function setCurrentFolderPath($path)
  {
    $id = $this->_getLastId($path);
    $this->current_folder = $this->_getNode($id);
  }

  protected function _getNode($id)
  {
    if($id)
      return new Node($id);

    return new Node();
  }

  protected function _getLastId($path)
  {
    $path = rtrim($path, '/');
    return (int)end(explode('/', $path));
  }

  function renderFolders()
  {
    $result = '';

    if($this->current_folder->id)
       $folders = lmbActiveRecord :: find('Node', 'parent_id = '. $this->current_folder->id);
    else
      $folders = $this->current_folder->getRoots();

    foreach($folders as $folder)
    {
      $title = htmlspecialchars($folder->title, ENT_QUOTES);
      $result .= "<Folder id='{$folder->id}' name='{$title}' url='{$folder->url_path}'/>";
    }

    return $result;
  }
}

?>
