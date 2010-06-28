<?php
lmb_require('limb/web_app/src/fetcher/lmbFetcher.class.php');
lmb_require('limb/active_record/src/lmbActiveRecord.class.php');

class NodeBreadcrumbsFetcher extends lmbFetcher
{
  function _createDataSet()
  {
    $path = lmbToolkit :: instance()->getRequest()->getUri()->getPath();

    if(!$node = Node :: findByPath('Node', $path))
      return new lmbEmptyIterator();

    $parents = $node->getParents();

    $result = array();
    $path = '';

    foreach($parents as $parent)
    {
      $path .= '/' . $parent->get('identifier');
      $parent->url_path = $path;
      $result[] = $parent;
    }

    $node->url_path = $path . '/' . $node->identifier;
    $node->is_last = true;
    $result[] = $node;

    return new lmbArrayDataset($result);
  }

}

?>
