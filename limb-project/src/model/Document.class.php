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

class Document extends lmbActiveRecord
{
  protected $_many_belongs_to = array('author' => array('field' => 'author_id',
                                                        'class' => 'User'));

  protected $_has_one = array('node' => array('field' => 'node_id',
                                              'class' => 'Node'));

  protected function _createValidator()
  {
    $validator = new lmbValidator();
    $validator->addRequiredRule('content');
    $validator->addRequiredObjectRule('author');
    return $validator;
  }

  static function findPublished()
  {
    return lmbActiveRecord :: find('Document', "is_published = 1");
  }

  static function findKidsForParent($parent_id)
  {
    $sql = 'SELECT document.* '.
           ' FROM document LEFT JOIN node ON node.object_id = document.id '.
           ' WHERE node.parent_id = '. (int)$parent_id;

    return lmbActiveRecord :: findBySql('Document', $sql);
  }

  function getPublishedKids()
  {
    $sql = 'SELECT document.* '.
           ' FROM document LEFT JOIN node ON node.object_id = document.id '.
           ' WHERE node.parent_id = '. $this->getNode()->getId() .
           ' AND document.is_published = 1 ' .
           ' ORDER BY node.priority ASC';

    return lmbActiveRecord :: findBySql('Document', $sql);
  }
}

?>
