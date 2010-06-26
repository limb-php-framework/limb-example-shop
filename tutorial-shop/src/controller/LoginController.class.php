<?php
lmb_require('src/model/User.class.php');

class LoginController extends lmbController
{
  function doDisplay()
  {
    if(!$this->request->hasPost())
      return;

    $this->useForm('login_form');
    $this->setFormDatasource($this->request);

    $this->_validateLoginForm();

    if(!$this->error_list->isValid())
      return;

    $user = $this->toolkit->getUser();

    $login = $this->request->get('login');
    $password = $this->request->get('password');
    if(!$user->login($login, $password))
    {
      $this->flashError('Login or password incorrect!');
      return;
    }

    $this->toolkit->getSession()->set('shop_user_id', $user->getId());

    $this->flashAndRedirect('You were logged in!', '/');
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
    $this->toolkit->getSession()->remove('shop_user_id');
    $this->redirect(array('controller' => 'main_page'));
  }
}

?>
