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

class ImageRepositoryBrowser
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

    return Node :: findByPath('Node', '/images');
  }

  protected function _getLastId($path)
  {
    $path = rtrim($path, '/');
    return (int)end(explode('/', $path));
  }

  function renderFolders()
  {
    $result = '';

    $folders = lmbActiveRecord :: find('ImageFolder', 'parent_id = '. $this->current_folder->getId());
    foreach($folders as $folder)
      $result .= "<Folder id='{$folder->id}' name='{$folder->title}' />";

    return $result;
  }

  function renderFiles()
  {
    $result = '';

    $files = Image :: findForParentNode($this->current_folder);
    foreach($files as $file)
    {
      $title = htmlspecialchars($file->getNode()->getTitle(), ENT_QUOTES);
      $result .= "<File id='$file->id' thumbnail_url='/file_object/show/{$file->getThumbnail()->getUid()}' ";
      $result .= "icon_url='/file_object/show/{$file->getIcon()->getUid()}' ";
      $result .= "original_url='/file_object/show/{$file->getOriginal()->getUid()}' ";
      $result .= "name='{$title}' size='{$file->getThumbnail()->getSize()}'/>";
    }
    return $result;
  }
}

?>
