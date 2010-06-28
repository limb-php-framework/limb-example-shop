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

class AdminDocumentsController extends lmbController
{
 function doCreate()
  {
    $node = new Node();
    $item = new Document();
    $this->useForm('document_form');
    $this->setViewFormDatasource($this->request);

    if($this->request->hasPost())
    {
      $node->setControllerName('document');
      $node->setObject($item);
      $item->setNode($node);
      $item->setAuthor($this->toolkit->getUser());

      $this->_import($item, $node);

      $this->_validateAndSave($item, $node);
    }
    else
    {
      $this->request->merge($item->export());
      $this->request->merge($node->export());

      $this->request->set('show_content', 1);
    }
  }

  function doEdit()
  {
    $node = lmbActiveRecord :: findById('Node', $this->request->getInteger('id'));
    $item = $node->getObject();
    $this->useForm('document_form');
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

      $this->request->set('node', $node);
    }
  }

  protected function _import($item, $node)
  {
    $item->import($this->request);
    $node->import($this->request);
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

  function doDelete()
  {
    if($this->request->hasPost() && $this->request->get('delete'))
    {
      foreach($this->request->getArray('ids') as $id)
      {
        $item = lmbActiveRecord :: findById('Node', (int)$id);
        $item->destroy();
      }
      $this->closePopup();
    }
  }
}

?>
