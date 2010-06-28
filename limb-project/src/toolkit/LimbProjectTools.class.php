<?php
lmb_require('limb/toolkit/src/lmbAbstractTools.class.php');
lmb_require('src/model/SessionUser.class.php');

class LimbProjectTools extends lmbAbstractTools
{
  protected $user;
  protected $log_config;
  protected $tree;

  function getUser()
  {
    if(is_object($this->user))
      return $this->user;

    $session = lmbToolkit :: instance()->getSession();
    if(!is_object($session_user = $session->get('SessionUser')))
    {
      $session_user = new SessionUser();
      $session->set('SessionUser', $session_user);
    }

    $this->user = $session_user->getUser();

    return $this->user;
  }

  function resetUser()
  {
    $this->setUser(null);
    $session = lmbToolkit :: instance()->getSession();
    $session->destroy('SessionUser');
  }

  function setUser($user)
  {
    $this->user = $user;
  }

  function getTree()
  {
    if(is_object($this->tree))
      return $this->tree;

    $this->tree = new lmbMaterializedPathTree('node');

    return $this->tree;
  }

  function setTree($tree)
  {
    $this->tree = $tree;
  }
}
?>
