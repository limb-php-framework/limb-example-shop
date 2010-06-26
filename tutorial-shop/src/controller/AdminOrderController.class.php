<?php
lmb_require('src/model/Order.class.php');

class AdminOrderController extends lmbController
{
  function doDisplay()
  {
    $this->setFormDatasource($this->request, 'filter_form');
  }

  function doDelete()
  {
    try
    {
      lmbActiveRecord :: delete('Order', 'id = ' .  $this->request->getInteger('id'));
      $this->redirect();
    }
    catch(lmbARException $e)
    {
      $this->flashError('Wrond Order ID');
      $this->redirect(array('controller' => 'admin'));
      return;
    }
  }

  function doDetails()
  {
    if(!$this->request->hasPost())
      return;

    try
    {
      $order = new Order($this->request->getInteger('id'));
    }
    catch(lmbARException $e)
    {
      return $this->flashErrorAndRedirect('Wrond Order ID', array('controller' => 'admin'));
    }

    $status = $this->request->get('status');
    $order->setStatus($status);
    $order->save();
    $this->flashMessage('Order status was changed');
  }
}
