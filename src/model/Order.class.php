<?php
lmb_require('src/model/OrderLine.class.php');

class Order extends lmbActiveRecord
{
  const STATUS_NEW                 = 1;
  const STATUS_PROCESSED           = 2;
  const STATUS_FINISHED            = 3;

  protected $_has_many = array('lines' => array('field' => 'order_id',
                                                'class' => 'OrderLine'));

  protected $_many_belongs_to = array('user' => array('field' => 'user_id',
                                                      'class' => 'User'));

  static function createForCart($cart)
  {
    $order = new Order();
    $order->setStatus(Order :: STATUS_NEW);
    $order->setLines($cart->getItems());
    $order->setSumm($cart->getTotalSumm());
    $order->setDate(time());
    return $order;
  }

  static function findForAdmin()
  {
    $toolkit = lmbToolkit :: instance();
    $status = $toolkit->getRequest()->get('status');

    if(!$status)
      return lmbActiveRecord :: find('Order');
    else
      return lmbActiveRecord :: find('Order', 'status = ' . (int)$status);
  }

  function belongsToUser($user)
  {
    return ($this->getUserId() == $user->getId());
  }

  function setStatus($value)
  {
    $statuses = $this->getStatusOptions();
    if(isset($statuses[$value]))
    {
      $this->_setRaw('status', $value);
      $this->markDirty(true);
    }
  }

  function getStatusName()
  {
    $statuses = $this->getStatusOptions();
    return $statuses[$this->getStatus()];
  }

  static function getStatusOptions()
  {
    return array(self :: STATUS_NEW => 'New',
                 self :: STATUS_PROCESSED => 'Processed',
                 self :: STATUS_FINISHED => 'Delivered');
  }
}

?>
