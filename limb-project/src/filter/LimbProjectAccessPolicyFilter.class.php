<?php
lmb_require('limb/filter_chain/src/lmbInterceptingFilter.interface.php');

class LimbProjectAccessPolicyFilter implements lmbInterceptingFilter
{
  function run($filter_chain)
  {
    $toolkit = lmbToolkit :: instance();
    $user = $toolkit->getUser();
    $current_path = '/' . ltrim($toolkit->getRequest()->getUriPath(), '/');

    if(strpos($current_path, '/admin') === 0 && !$user->isLoggedIn())
    {
      $url = $toolkit->getRoutesUrl(array('controller' => 'user', 'action' => 'login'));
      $url .= '?redirect=' . $current_path;
      $toolkit->flashMessage("Not enough access permissions");
      $toolkit->redirect($url);
      return;
    }

    $filter_chain->next();
  }
}

?>