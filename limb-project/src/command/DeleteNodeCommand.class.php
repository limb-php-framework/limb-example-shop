<?php
lmb_require('limb/web_app/src/command/lmbFormCommand.class.php');

class DeleteNodeCommand extends lmbFormCommand
{
  function __construct()
  {
    parent :: __construct('', 'delete_form');

    $this->setViewFormDatasource($this->request);
  }

  function _onValid()
  {
    if($this->request->get('delete'))
    {
      foreach($this->request->getArray('ids') as $id)
      {
        $node = lmbActiveRecord :: findById('Node', (int)$id);
        $node->destroy();
      }
      $this->closePopup();
    }
  }
}

?>
