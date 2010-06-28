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

abstract class FolderController extends lmbController
{
  protected $_form_id = 'file_folder_form';
  protected $_controller_name = 'file_folder';

  protected function _initNode($id = null)
  {
    return new Node($id);
  }

  function doCreate()
  {
    $node = $this->_initNode();
    $this->useForm($this->_form_id);
    $this->setViewFormDatasource($this->request);

    if($this->request->hasPost())
    {
      $node->setControllerName($this->_controller_name);
      $this->_importAndSave($node);
    }
  }

  function doEdit()
  {
    $node = $this->_initNode($this->request->getInteger('id'));
    $this->useForm($this->_form_id);
    $this->setViewFormDatasource($this->request);

    if($this->request->hasPost())
      $this->_importAndSave($node);
    else
      $this->request->merge($node->export());
  }

  protected function _importAndSave($node)
  {
    $node->import($this->request);

    $node->validate($this->error_list);

    if($this->error_list->isValid())
    {
      $node->saveSkipValidation();
      $this->closePopup();
    }
  }

  function doDelete()
  {
    if($this->request->hasPost() && $this->request->get('delete'))
    {
      foreach($this->request->getArray('ids') as $id)
      {
        $node = $this->_initNode((int)$id);
        $node->destroy();
      }
      $this->closePopup();
    }
  }
}

?>
