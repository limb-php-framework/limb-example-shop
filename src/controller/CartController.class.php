<?php

class CartController extends lmbController
{
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