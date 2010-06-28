<?php
/**********************************************************************************
* Copyright 2004 BIT, Ltd. http://limb-project.com, mailto: support@limb-project.com
*
* Released under the LGPL license (http://www.gnu.org/copyleft/lesser.html)
***********************************************************************************
*
* $Id: lmbRoutesRequestDispatcher.class.php 4243 2006-10-18 15:26:17Z pachanga $
*
***********************************************************************************/
lmb_require('limb/web_app/src/request/lmbRequestDispatcher.interface.php');
lmb_require('src/model/Node.class.php');
lmb_require('limb/active_record/src/lmbActiveRecord.class.php');

class NodeBasedRequestDispatcher implements lmbRequestDispatcher
{
  function dispatch($request)
  {
    $result = array();

    if(!$node = Node :: findByPath('Node', $request->getUri()->getPath()))
       return $result;

    $result['controller'] = $node->getControllerName();

    if($action = $request->get('action'))
      $result['action'] = $action;
    else
      $result['action'] = 'show';

    return $result;
  }
}

?>