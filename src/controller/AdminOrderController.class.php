<?php
lmb_require('limb/cms/src/controller/lmbAdminObjectController.class.php');

class AdminOrderController extends lmbAdminObjectController
{
  protected $_object_class_name = 'Order';

  function doDisplay()
  {
    $this->useForm('filter_form');
    $this->setFormDatasource($this->request);

    $this->orders = Order :: findForAdmin($this->_getSearchParams())
                      ->sort(array('date' => 'DESC'));
  }

  function _getSearchParams()
  {
    $params = array();
    if($this->request->get('status'))
      $params['status'] = $this->request->getInteger('status');

    return $params;
  }

  function doDetails()
  {
    try
    {
      $this->order = new Order($this->request->getInteger('id'));
      $this->useForm('status_form');
      $this->setFormDatasource($this->order);
    }
    catch(lmbARException $e)
    {
    	$this->_endDialog();
      $this->flashError('Wrond Order ID');
      return;
    }

    if(!$this->request->hasPost())
      return;

    $status = $this->request->getInteger('status');
    $this->order->setStatus($status);
    $this->order->save();

    $this->_endDialog();

    $this->flashMessage('Order status was changed to "'.$this->order->getStatusName().'"');
  }
}