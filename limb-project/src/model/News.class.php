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
lmb_require('limb/i18n/src/datetime/lmbLocaleDate.class.php');
lmb_require('limb/datetime/src/lmbDate.class.php');

class News extends lmbActiveRecord
{
  protected $_many_belongs_to = array('author' => array('field' => 'author_id',
                                                        'class' => 'User'));

  protected $_default_sort_params = array('news_date' => 'DESC');

  protected $_lazy_attributes = array('content');

  protected function _createValidator()
  {
    $validator = new lmbValidator();
    $validator->addRequiredRule('title');
    $validator->addRequiredRule('content');
    $validator->addRequiredRule('news_date');
    $validator->addRequiredObjectRule('author');
    return $validator;
  }

  function findPublished()
  {
    return lmbActiveRecord :: find('News', array('criteria' => 'is_published = 1',
                                                 'sort' => array('news_date' => 'DESC')));
  }

  function publish()
  {
    $this->setIsPublished(true);
  }

  function unpublish()
  {
    $this->setIsPublished(false);
  }
}

?>
