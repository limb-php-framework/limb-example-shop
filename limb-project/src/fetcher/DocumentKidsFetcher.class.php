<?php
lmb_require('limb/web_app/src/fetcher/lmbFetcher.class.php');
lmb_require('limb/active_record/src/lmbActiveRecord.class.php');
lmb_require('limb/dbal/src/criteria/lmbSQLRawCriteria.class.php');

class DocumentKidsFetcher extends lmbFetcher
{
  protected $parent_id = 0;

  function setParentId($parent_id)
  {
    if($parent_id)
      $this->parent_id = $parent_id;
  }
  function setParentPath($path)
  {
    if($node = Node :: findByPath('Node', $path))
      $this->parent_id = $node->id;
  }

  function _createDataSet()
  {
    return Document :: findKidsForParent((int)$this->parent_id);
  }
}

?>
