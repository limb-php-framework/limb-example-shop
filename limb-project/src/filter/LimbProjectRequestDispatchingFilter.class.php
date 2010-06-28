<?php
/**********************************************************************************
* Copyright 2004 BIT, Ltd. http://limb-project.com, mailto: support@limb-project.com
*
* Released under the LGPL license (http://www.gnu.org/copyleft/lesser.html)
***********************************************************************************
*
* $Id: lmbCombinedRequestDispatchingFilter.class.php 3491 2006-05-11 12:43:11Z pachanga $
*
***********************************************************************************/
lmb_require('limb/web_app/src/request/lmbRoutesRequestDispatcher.class.php');
lmb_require('src/request/NodeBasedRequestDispatcher.class.php');
lmb_require('limb/web_app/src/filter/lmbRequestDispatchingFilter.class.php');
lmb_require('limb/web_app/src/request/lmbCompositeRequestDispatcher.class.php');

class LimbProjectRequestDispatchingFilter extends lmbRequestDispatchingFilter
{
  function __construct()
  {
    $dispatcher = new lmbCompositeRequestDispatcher();
    $dispatcher->addDispatcher(new NodeBasedRequestDispatcher());
    $dispatcher->addDispatcher(new lmbRoutesRequestDispatcher());
    parent :: __construct($dispatcher);
  }
}

?>