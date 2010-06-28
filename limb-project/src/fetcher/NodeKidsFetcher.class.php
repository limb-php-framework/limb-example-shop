<?php
lmb_require('limb/web_app/src/fetcher/lmbFetcher.class.php');
lmb_require('limb/active_record/src/lmbActiveRecord.class.php');
lmb_require('limb/dbal/src/criteria/lmbSQLRawCriteria.class.php');
lmb_require('src/model/ClassName.class.php');

class NodeKidsFetcher extends lmbFetcher
{
  protected $controller_name;
  protected $parent_id = 0;
  protected $path;

  function setController($controller_name)
  {
    $this->controller_name = $controller_name;
  }

  function setParentId($parent_id)
  {
    if($parent_id)
      $this->parent_id = $parent_id;
  }

  function setParentPath($path)
  {
    $this->path = $path;
  }

  function _createDataSet()
  {
    $toolkit = lmbToolkit :: instance();

    if($this->path && !$this->parent_id)
    {

      if($node = Node :: findByPath('Node', $this->path))
        $this->parent_id = $node->id;
    }


    $criteria = new lmbSQLRawCriteria("parent_id = " . (int)$this->parent_id);
    if($this->controller_name)
    {
      $controller_id = ClassName :: generateIdFor($this->controller_name);
      $criteria->addAnd(new lmbSQLRawCriteria('controller_id ='. $controller_id));
    }

    return lmbActiveRecord :: find('Node', array('criteria' => $criteria));
  }
}

?>
