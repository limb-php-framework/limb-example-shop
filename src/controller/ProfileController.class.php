<?php
lmb_require('src/model/User.class.php');

class ProfileController extends lmbController
{
  function doDisplay()
  {
    $this->useForm('profile_form');
    $this->setFormDatasource($this->toolkit->getUser());

    if($this->request->hasAttribute('change_password'))
      $this->_changeUserPassword();
    if($this->request->hasAttribute('edit'))
      $this->_updateUserProfile();
  }

  function doShowOrder()
  {
    try
    {
      $order = new Order($this->request->getInteger('id'));

      if(!$order->belongsToUser($this->toolkit->getUser()))
      {
        $this->flashError('You can see only your orders!');
        $this->redirect(array('controller' => 'main_page'));
      }

      $this->view->set('order', $order);
    }
    catch(lmbARException $e)
    {
      $this->flashError('Can\'t load order!');
      $this->redirect(array('controller' => 'main_page'));
    }
  }

  protected function _changeUserPassword()
  {
    $this->useForm('change_password_form');

    $this->_validateChangePasswordForm();

    if($this->error_list->isValid())
    {
      $user = $this->toolkit->getUser();
      $user->setPassword($this->request->get('password'));
      $user->save();

      $this->flashMessage('Your password was changed');
      $this->toolkit->redirect();
    }
  }

  protected function _updateUserProfile()
  {
    $this->useForm('profile_form');
    $this->setFormDatasource($this->toolkit->getUser());

    $user = $this->toolkit->getUser();
    $user->setEmail($this->request->get('email'));
    $user->setName($this->request->get('name'));
    $user->setAddress($this->request->get('address'));
    if($user->trySave($this->error_list))
    {
      $this->flashMessage('Your profile was changed');
      $this->toolkit->redirect();
    }
  }

  protected function _validateChangePasswordForm()
  {
    $this->validator->addRequiredRule('old_password');
    $this->_validatePasswordField();

    $user = $this->toolkit->getUser();
    if($old_password = $this->request->get('old_password'))
    {
      $hashed_password = User :: cryptPassword($old_password);
      if($user->getHashedPassword() != $hashed_password)
        $this->error_list->addError('Wrong old password', array('old_password'));
    }
  }

  function doRegister()
  {
    $this->useForm('register_form');
    $this->setFormDatasource($this->request);

    if($this->request->hasPost())
    {
      $this->_validatePasswordField();

      $user = new User();
      $user->setLogin($login = $this->request->get('login'));
      $user->setEmail($this->request->get('email'));
      $user->setName($this->request->get('name'));
      $user->setPassword($password = $this->request->get('password'));
      $user->setAddress($this->request->get('address'));
      $user->setIsAdmin(false);

      if($user->trySave($this->error_list) && $this->error_list->isValid())
      {
        $this->toolkit->getUser()->login($login, $password);
        $this->toolkit->getSession()->set('user_id', $user->getId());

        $this->flashMessage('Thank you for your registration!');

        $this->toolkit->redirect();
      }
    }
  }

  function _validatePasswordField()
  {
    $this->validator->addRequiredRule('password');
    $this->validator->addRequiredRule('repeat_password');
    lmb_require('limb/validation/src/rule/lmbMatchRule.class.php');
    $this->validator->addRule(new lmbMatchRule('password', 'repeat_password'));
    $this->validator->validate($this->request);
  }
}
?>
