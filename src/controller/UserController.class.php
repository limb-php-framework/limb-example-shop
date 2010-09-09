<?php

class UserController extends lmbController
{
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
}
