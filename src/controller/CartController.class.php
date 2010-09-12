<?php

class CartController extends lmbController
{
  protected function _checkoutCart($cart)
  {
    $user = lmbToolkit::instance()->getUser();
    $this->cart = $cart;
    $this->useForm('checkout_form');

    if(!$this->request->hasPost())
    {
    	$this->setFormDatasource($user);

      if(!$cart->getItemsCount())
        return $this->flashAndRedirect('Your cart is empty! Nothing to checkout!', array('controller' => 'main_page'));

      if(!$user->getIsLoggedIn())
        return $this->flashAndRedirect('Your are not logged in yet! Please login or register to checkout!');
    }
    else
    {
    	$this->setFormDatasource($this->request);

      $order = Order :: createForCart($cart);
      $order->setAddress($this->request->get('address'));
      $order->setUser($user);

      if($order->trySave($this->error_list))
      {
        $cart->reset();
        return $this->flashAndRedirect('Your order has been sent. Your cart is now empty.', array('controller' => 'main_page'));
      }
    }
  }

  function doCheckout()
  {
    $cart = $this->_getCart();
    return $this->_checkoutCart($cart);
  }

  function doDisplay()
  {
    $this->cart = $this->_getCart();
  }

  function doEmpty()
  {
    $cart = $this->_getCart();
    $cart->reset();
    $this->redirect();
  }

  function doAdd()
  {
    $product_id = $this->request->getInteger('id');
    try
    {
      $product = Product :: findById($product_id);
      $cart = $this->_getCart();
      $cart->addProduct($product);
      $this->flashMessage('Product "' . $product->getTitle() . '" added to your cart!');
    }
    catch(lmbARException $e)
    {
      $this->flashError('Wrong product!');
    }

    if(isset($_SERVER['HTTP_REFERER']))
      $this->redirect($_SERVER['HTTP_REFERER']);
    else
      $this->redirect();
  }

  protected function _getCart()
  {
    $session = $this->toolkit->getSession();
    if(!$cart = $session->get('cart'))
    {
      $cart = new Cart();
      $session->set('cart', $cart);
    }

    return $cart;
  }
}