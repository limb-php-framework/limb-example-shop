<?php
/**********************************************************************************
* Copyright 2004 BIT, Ltd. http://limb-project.com, mailto: support@limb-project.com
*
* Released under the LGPL license (http://www.gnu.org/copyleft/lesser.html)
***********************************************************************************
*
* $Id: lmbTreeNode.class.php 4239 2006-10-18 15:23:24Z pachanga $
*
***********************************************************************************/
lmb_require('limb/active_record/src/lmbActiveRecord.class.php');
lmb_require('limb/dbal/src/criteria/lmbSQLFieldCriteria.class.php');

class ClassName extends lmbActiveRecord
{
  static function generateIdFor($object)
  {
    if(is_object($object))
      $title = get_class($object);
    else
      $title = $object;

    $criteria = new lmbSQLFieldCriteria('title', $title);
    if($obj = lmbActiveRecord :: findFirst('ClassName', array('criteria' => $criteria)))
    {
      return $obj->id;
    }
    else
    {
      $class_name = new ClassName();
      $class_name->title = $title;
      $class_name->save();
      return $class_name->id;
    }

  }
}

?>
