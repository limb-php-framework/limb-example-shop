<?php
class Order extends lmbActiveRecord
{
  const STATUS_NEW                 = 1;
  const STATUS_PROCESSED           = 2;
  const STATUS_FINISHED            = 3;

  protected $_has_many = array('lines' => array('field' => 'order_id',
                                                'class' => 'OrderLine'));

  protected $_many_belongs_to = array('user' => array('field' => 'user_id',
                                                      'class' => 'User'));

  function createForCart($cart)
  {
    $order = new Order();
    $order->setStatus(Order :: STATUS_NEW);
    $order->setLines($cart->getItems());
    $order->setSumm($cart->getTotalSumm());
    $order->setDate(time());
    return $order;
  }

  function setStatus($value)
  {
    $statuses = $this->getStatusOptions();
    if(isset($statuses[$value]))
      $this->_setRaw('status', $value);
  }

  function getStatusName()
  {
    $statuses = $this->getStatusOptions();
    return $statuses[$this->getStatus()];
  }

}