<?php
lmb_require('limb/core/src/lmbObject.class.php');

class Cart extends lmbObject
{
  protected $line_items = array();

  function addProduct(Product $product)
  {
    $id = $product->getId();

    if(!isset($this->line_items[$id]))
      $this->line_items[$id] = OrderLine :: createForProduct($product);
    $this->line_items[$id]->increaseQuantity(1);
  }

  function getItems()
  {
    return $this->line_items;
  }

  function reset()
  {
    parent :: reset();

    $this->line_items = array();
  }

  function getTotalSumm()
  {
    $result = 0;
    foreach($this->line_items as $item)
      $result += $item->getSumm();
    return $result;
  }

  function getItemsCount()
  {
    return sizeof($this->line_items);
  }
}
?>