<?php
lmb_require('limb/filter_chain/src/lmbInterceptingFilter.interface.php');

class SetViewTemplatePathFilter implements lmbInterceptingFilter
{
  function run($filter_chain)
  {
    $toolkit = lmbToolkit :: instance();
    $view = $toolkit->getView();
    $view->set('this_template_path', $view->getTemplate());
    $filter_chain->next();
  }
}
?>