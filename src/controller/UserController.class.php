<?php

class UserController extends lmbController
{
	function doRegister()
  {
    $this->useForm('register_form');
    $this->setFormDatasource($this->request);

    if($this->request->hasPost())
    {
    	$user_properties = $this->request->getPost(
    	  array('login', 'name', 'email', 'password', 'address')
    	);

    	$user = new User($user_properties);
    	$user->trySave($this->error_list);

      $this->_validatePasswordField();

      if($this->error_list->isValid())
      {
        $this->toolkit->getUser()->login($user->login, $user->password);
        $this->toolkit->getSession()->set('user_id', $user->getId());

        $this->flashMessage('Thank you for your registration!');
        $this->toolkit->redirect('/');
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

  function doEdit()
  {
    $this->setFormDatasource($this->toolkit->getUser(), 'profile_form');

    if($this->request->has('change_password'))
      $this->_changeUserPassword();
    if($this->request->has('edit'))
      $this->_updateUserProfile();
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

    $user_properties = $this->request->getPost(
      array('login', 'name', 'email', 'password', 'address')
    );
    $user = $this->toolkit->getUser();
    $user->import($user_properties);

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

  function doLogin()
  {
    if(!$this->request->hasPost())
      return;

    $user = $this->toolkit->getUser();

    $this->useForm('login_form');
    $this->setFormDatasource($this->request);

    $this->_validateLoginForm();

    if(!$this->error_list->isValid())
      return;

    $login = $this->request->get('login');
    $password = $this->request->get('password');

    if(!$user->login($login, $password))
    {
      $this->addError('Login or password incorrect!');
    }
    else
    {
      $this->toolkit->getSession()->set('user_id', $user->getId());
      $this->flashAndRedirect('You were logged in!', '/');
    }
  }

  protected function _validateLoginForm()
  {
    $this->validator->addRequiredRule('login');
    $this->validator->addRequiredRule('password');
    $this->validator->validate($this->request);
  }

  function doLogout()
  {
    $user = $this->toolkit->getUser();
    $user->logout();
    $this->toolkit->getSession()->remove('user_id');
    $this->response->redirect('/');
  }

  function doShowOrder()
  {
    try
    {
      $order = new Order($this->request->getInteger('id'));
      if(!$order->belongsToUser($this->toolkit->getUser()))
        $this->flashAndRedirect('You can see only your orders!', '/');
      else
        $this->order = $order;
    }
    catch(lmbARException $e)
    {
      $this->flashAndRerdirect('Can\'t load order!', '/');
    }
  }
}
