<?php
/**********************************************************************************
* Copyright 2004 BIT, Ltd. http://limb-project.com, mailto: support@limb-project.com
*
* Released under the LGPL license (http://www.gnu.org/copyleft/lesser.html)
***********************************************************************************
*
* $Id$
*
***********************************************************************************/
lmb_require('limb/web_app/src/controller/lmbController.class.php');
lmb_require('limb/mail/src/lmbMailer.class.php');
lmb_require('src/model/TrudStudent.class.php');

class UserController extends lmbController
{
  function doLogin()
  {
    if($this->request->hasPost())
    {
      $user = $this->toolkit->getUser();

      $login = $this->request->get('login');
      $password = $this->request->get('password');

      if(!$redirect_url = urldecode($this->request->get('redirect')))
        $redirect_url = '/';

      if(!$user->login($login, $password))
        $this->flashError("Wrong login or password");
      else
        $this->toolkit->redirect($redirect_url);
    }
  }

  function doLogout()
  {
    $user = $this->toolkit->getUser();
    $user->logout();
    $this->response->redirect('/');
  }

  function doEdit()
  {
    $user = $this->toolkit->getUser();
    if(!$user->isLoggedIn())
    {
      $this->response->redirect('/');
      return;
    }

    $this->useForm('edit_form');
    $this->setViewFormDatasource($user);

    if($this->request->hasPost())
    {
      $user->import($this->request);
      if($user->trySave($this->error_list))
      {
        $this->_sendUpdateEmail($user);
        $this->flash("User info was changed successfully");
      }
    }
  }
}

?>
