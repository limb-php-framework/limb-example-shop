<?php
lmb_require('src/model/Product.class.php');
lmb_require('src/model/Cart.class.php');

class CartController extends lmbController
{
  protected function _checkoutCart($cart)
  {
    $user = lmbToolkit::instance()->getUser();
    $this->view->set('cart', $cart);
    $this->view->set('address', $user->getAdress());
    $this->useForm('checkout_form');    
    
    if(!$this->request->hasPost())
    {
      if(!$cart->getItemsCount())
        return $this->flashAndRedirect('Your cart is empty! Nothing to checkout!', array('controller' => 'main_page'));
  
      if(!$user->getIsLoggedIn())
        return $this->flashAndRedirect('Your are not logged in yet! Please login or register to checkout!');
    }
    else
    {    	
    	
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
	
  function doDisplay()
  {
    $this->view->set('cart', $this->_getCart());
  }

  function doCheckout()
  {
    $cart = $this->_getCart();
    return $this->_checkoutCart($cart);
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
      $product = lmbActiveRecord :: findById('Product', $product_id);
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
?>
