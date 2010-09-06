<?php
lmb_require('limb/toolkit/src/lmbAbstractTools.class.php');
lmb_require('src/model/User.class.php');

class ShopTools extends lmbAbstractTools
{
  protected $user;

  function getUser()
  {
    if(is_object($this->user))
      return $this->user;

    $session = lmbToolkit :: instance()->getSession();
    if($user_id = $session->get('shop_user_id'))
    {
      try
      {
        $this->user = new User($user_id);
        $this->user->setIsLoggedIn(true);
      }
      catch(lmbARException $e)
      {
        $this->user = new User();
        $session->remove('shop_user_id');
      }
    }
    else
      $this->user = new User();

    return $this->user;
  }
}
?>
