<?php
class OrderLine extends lmbActiveRecord
{
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