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
lmb_require('limb/i18n/src/datetime/lmbLocaleDate.class.php');

class AdminNewsController extends lmbController
{
  function doCreate()
  {
    $item = new News();
    $this->useForm('news_form');
    $this->setViewFormDatasource($item);

    $item->import($this->request);

    if($this->request->hasPost())
    {
      $item->setAuthor($this->toolkit->getUser());

      if($item->trySave($this->error_list))
        $this->closePopup();
    }
    else
      $item->setNewsDate(time());
  }

  function doEdit()
  {
    $item = new News($this->request->getInteger('id'));
    $this->useForm('news_form');
    $this->setViewFormDatasource($item);

    if($this->request->hasPost())
    {
      $item->import($this->request);

      if($item->trySave($this->error_list))
        $this->closePopup();
    }
  }

  function doPublish()
  {
    foreach($this->request->getArray('ids') as $id)
    {
      $item = new News((int)$id);
      $item->publish();
      $item->save();
    }
    $this->closePopup();
  }

  function doUnpublish()
  {
    foreach($this->request->getArray('ids') as $id)
    {
      $item = new News((int)$id);
      $item->unpublish();
      $item->save();
    }
    $this->closePopup();
  }

  function doDelete()
  {
    if($this->request->hasPost() && $this->request->get('delete'))
    {
      foreach($this->request->getArray('ids') as $id)
      {
        $item = new News((int)$id);
        $item->destroy();
      }
      $this->closePopup();
    }
  }
}

?>
