<?php
class OrderLine extends lmbActiveRecord
{
	protected $_many_belongs_to = array('order' => array('field' => 'order_id',
                                                       'class' => 'Order'),
                                      'product' => array('field' => 'product_id',
                                                         'class' => 'Product'));

  static function createForProduct(Product $product)
  {
    $line = new OrderLine();
    $line->setProduct($product);
    $line->setQuantity(0);
    $line->setPrice($product->getPrice());
    return $line;
  }

  function increaseQuantity($amount = 1)
  {
    $this->setQuantity($this->getQuantity() + $amount);
  }

  function getSumm()
  {
    return $this->getQuantity() * $this->getPrice();
  }
}