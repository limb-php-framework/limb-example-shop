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

class FileRepositoryBrowser
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

    return Node :: findByPath('Node', '/files');
  }

  protected function _getLastId($path)
  {
    $path = rtrim($path, '/');
    return (int)end(explode('/', $path));
  }

  function renderFolders()
  {
    $result = '';

    $folders = lmbActiveRecord :: find('FileFolder', 'parent_id = '. $this->current_folder->id);
    foreach($folders as $folder)
      $result .= "<Folder id='{$folder->id}' name='{$folder->title}' />";

    return $result;
  }

  function renderFiles()
  {
    $result = '';

    $files = FileObject :: findForParentNode($this->current_folder);
    foreach($files as $file)
    {
      $filename = htmlspecialchars($file->getFileName(), ENT_QUOTES);
      $title = htmlspecialchars($file->getNode()->getTitle(), ENT_QUOTES);
      $result .= "<File id='$file->id' url='/file_object/show/{$file->uid}' name='{$title} ($filename)' size='{$file->size}' />";
    }
    return $result;
  }
}

?>
