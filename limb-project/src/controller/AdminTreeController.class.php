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
lmb_require('src/TreeBrowser.class.php');

class AdminTreeController extends lmbController
{
 function doCreateNode()
  {
    $this->useForm('node_form');
    $this->setViewFormDatasource($this->request);

    if($this->request->hasPost())
    {
      $class_name = $this->request->get('class_name') ? $this->request->get('class_name') : 'Node';
      $node = new $class_name();

      $this->_importAndSave($node);
    }
    else
      $this->request->set('class_name', 'Node');
  }

  function doEditNode()
  {
    $node = lmbActiveRecord :: findById('Node', $this->request->getInteger('id'));
    $this->useForm('node_form');
    $this->setViewFormDatasource($this->request);

    if($this->request->hasPost())
      $this->_importAndSave($node);
    else
    {
      $this->request->merge($node->export());
      $this->request->set('controller_name', $node->getControllerName());
    }
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
    $this->performCommand('src/command/DeleteNodeCommand');
  }

  function doSavePriority()
  {
    $this->performCommand('src/command/SaveNodePriorityCommand');
  }

  function doPublish()
  {
    $this->performCommand('src/command/PublishNodeCommand');
  }

  function doUnpublish()
  {
    $this->performCommand('src/command/UnpublishNodeCommand');
  }

  function doMove()
  {
    if($parent_id = $this->request->getInteger('id'))
    {
      $parent_node = new Node($parent_id);
      $this->request->set('parent', $parent_node);
    }

    $this->useForm('tree_form');
    $this->setViewFormDatasource($this->request);

    if($this->request->hasPost() && $this->request->get('move'))
    {
      $parent_id = $this->request->get('parent_id');
      foreach($this->request->getArray('ids') as $id)
      {
        $node = lmbActiveRecord :: findById('Node', $id);
        $node->setParentId($parent_id);
        $node->save();
      }
      $this->closePopup();
    }
  }

  function doProcessCommand()
  {
    $resource_type = $this->request->get('Type');
    $current_folder = $this->request->get('CurrentFolder');
    $command = $this->request->get('Command');

    $browser = new TreeBrowser();
    $browser->setCurrentFolderPath($current_folder);

    $this->_setXmlHeaders();

    $xml = 	'<?xml version="1.0" encoding="utf-8" ?>';
    $xml .= '<Connector command="' . $command . '" resourceType="' . $resource_type . '">' ;
    $xml .= '<CurrentFolder path="' . $current_folder . '" url="/" />' ;

    $xml .= '<Folders>' . $browser->renderFolders() . '</Folders>';
    $xml .= '<Files></Files>';

    $xml .= '</Connector>';

    return $xml;
  }

  protected function _setXmlHeaders()
  {
    $this->response->header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
    $this->response->header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
    $this->response->header('Cache-Control: no-store, no-cache, must-revalidate') ;
    $this->response->header('Cache-Control: post-check=0, pre-check=0', false) ;
    $this->response->header('Pragma: no-cache') ;
    $this->response->header( 'Content-Type:text/xml; charset=utf-8' ) ;
  }

}

?>
