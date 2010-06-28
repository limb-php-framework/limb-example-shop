<?php
lmb_require('limb/web_app/src/fetcher/lmbFetcher.class.php');
lmb_require('limb/active_record/src/lmbActiveRecord.class.php');
lmb_require('limb/dbal/src/criteria/lmbSQLRawCriteria.class.php');
lmb_require('src/model/ClassName.class.php');

class NodeFetcher extends lmbFetcher
{
  protected $id;
  protected $append_locale = false;
  protected $path;

  function setNodeId($id)
  {
    if(!$id)
      return;

    $this->id = $id;
  }

  function setAppendLocale($flag)
  {
    $this->append_locale = (bool)$flag;
  }

  function setPath($path)
  {
    $this->path = $path;
  }

  function _createDataSet()
  {
    $toolkit = lmbToolkit :: instance();

    if($this->path && !$this->id)
    {
      $path = $this->path;

      if($node = Node :: findByPath('Node', $path))
        $this->id = $node->id;
    }

    if(!$this->id && $id = $toolkit->getRequest()->getInteger('id'))
      $this->id = $id;

    if($this->id)
      $result = lmbActiveRecord :: find('Node', 'id = ' . $this->id);
    else
    {
      $criteria = new lmbSQLRawCriteria("parent_id = 0");
      $kids = lmbActiveRecord :: find('Node', array('criteria' => $criteria));
      $result = new lmbArrayDataset(array(array('parent_id' => 0,
                                                'kids' => $kids)));
    }

    return $result;
  }
}

?>
