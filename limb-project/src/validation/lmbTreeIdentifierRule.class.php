<?php
/**********************************************************************************
* Copyright 2004 BIT, Ltd. http://limb-project.com, mailto: support@limb-project.com
*
* Released under the LGPL license (http://www.gnu.org/copyleft/lesser.html)
***********************************************************************************
*
* $Id: lmbTreeIdentifierRule.class.php 3491 2006-05-11 12:43:11Z pachanga $
*
***********************************************************************************/
lmb_require('limb/validation/src/rule/lmbSingleFieldRule.class.php');

class lmbTreeIdentifierRule extends lmbSingleFieldRule
{
  protected $parent_node_id_field_name;

  function check($value)
  {
    if(!preg_match('~^[a-zA-Z0-9-_\.]+$~', $value))
    {
      $this->error(tr('/validation', '{Field} must contains numeric, latin alphabet and `-`, `_`, `.` symbols only'));
      return;
    }
  }
}

?>