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
lmb_require('limb/web_app/src/controller/lmbController.class.php');

class AdminImageController extends lmbController
{
 function doCreate()
  {
    $node = new Node();
    $item = new Image();
    $this->useForm('image_form');
    $this->setViewFormDatasource($this->request);

    if($this->request->hasPost())
    {
      $node->setControllerName('image');
      $node->setObject($item);
      $item->setNode($node);

      $this->_import($item, $node);
      $node->setIdentifier(Node :: generateIdentifier($this->request->get('parent')));

      $this->_validateAndSave($item, $node);
    }
  }

  function doEdit()
  {
    $node = lmbActiveRecord :: findById('Node', $this->request->getInteger('id'));
    $item = $node->getObject();
    $this->useForm('image_form');
    $this->setViewFormDatasource($this->request);

    if($this->request->hasPost())
    {
      $this->_import($item, $node);
      $this->_validateAndSave($item, $node);
    }
    else
    {
      $this->request->merge($item->export());
      $this->request->merge($node->export());
      $this->request->set('item', $item);
    }
  }

  protected function _import($item, $node)
  {
    $item->import($this->request);
    $node->import($this->request);

    if($original = $this->_uploadFile('original_image'))
    {
      if($original_size = $this->request->get('original_size'))
        $original->resize($original_size);

      $item->setOriginal($original);
    }

    if($thumbnail = $this->_uploadFile('thumbnail_image'))
    {
      if($thumbnail_size = $this->request->get('thumbnail_size'))
        $thumbnail->resize($thumbnail_size);
      $item->setThumbnail($thumbnail);
    }
    else
    {
      $thumbnail = clone($original);

      if($thumbnail_size = $this->request->get('thumbnail_size'))
        $thumbnail->resize($thumbnail_size);
      else
        $thumbnail->resize(150);

      $item->setThumbnail($thumbnail);
    }

    if($icon = $this->_uploadFile('icon_image'))
    {
      if($icon_size = $this->request->get('icon_size'))
        $icon->resize($icon_size);

      $item->setIcon($icon);
    }
    else
    {
      $icon = clone($original);

      if($icon_size = $this->request->get('icon_size'))
        $icon->resize($icon_size);
      else
        $icon->resize(50);

      $item->setIcon($icon);
    }
  }

  protected function _validateAndSave($item, $node)
  {
    $node->validate($this->error_list);
    $item->validate($this->error_list);

    if($this->error_list->isValid())
    {
      $item->saveSkipValidation();
      $node->saveSkipValidation();
      $this->closePopup();
    }
  }

  function _uploadFile($field)
  {
    if (isset($_FILES[$field]) &&
        !is_null($_FILES[$field]['tmp_name']) &&
        is_uploaded_file($_FILES[$field]['tmp_name']))
    {
      $file = $_FILES[$field];
      $file_name = $file['name'];

      $image = new ImageFileObject();
      $image->setFileName($file_name);
      $image->setMimeType($file['type']);

      try
      {
        $image->loadFile($file['tmp_name']);
      }
      catch(lmbIOException $e)
      {
        $this->toolkit->flashError('Ошибка при загрузке файла!');
      }

      return $image;
    }
  }

  function doDelete()
  {
    $this->performCommand('src/command/DeleteNodeCommand');
  }

  function doShow()
  {
    if($image = FileObject :: findById('Image', $this->request->get('id')))
    {
      header('Content-type: ' . $image->getThumbnail()->getMimeType());
      header('Content-Disposition: filename=' . $image->getThumbnail()->getName());
      print(file_get_contents($image->getThumbnail()->getFilePath()));
      exit();
    }
  }
}

?>
