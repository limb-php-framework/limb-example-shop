<?php
lmb_require('src/model/User.class.php');

class AdminUserController extends lmbController
{
  function doCreate()
  {
    $this->_performCreateOrEdit();
  }

  function doEdit()
  {
    $this->_performCreateOrEdit();
  }

  protected function _performCreateOrEdit()
  {
    if(!$id = (int)$this->request->getInteger('id'))
      $id = null;

    $item = new User($id);
    $this->useForm('user_form');
    $this->setFormDatasource($item);

    if($this->request->hasPost())
    {
      $item->import($this->request);

      if($this->request->get('create'))
        $this->_validatePasswordField();

      $item->validate($this->error_list);

      if($this->error_list->isEmpty())
      {
        $item->saveSkipValidation();
        $this->redirect();
      }
    }
  }

  function doDelete()
  {
    $item = new User($this->request->getInteger('id'));
    $item->destroy();
    $this->redirect();
  }

  function doChangePassword()
  {
    if(!$this->request->hasPost())
      return;

    $this->useForm('user_form');
    $this->setFormDatasource($this->request);

    $this->_validatePasswordField();

    if(!$this->error_list->isValid())
      return;

    $user = new User($this->request->getInteger('id'));
    $user->setPassword($this->request->get('password'));

    if($user->trySave($this->error_list))
      $this->redirect();
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
