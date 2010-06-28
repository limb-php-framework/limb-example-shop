<?php
lmb_require('limb/active_record/src/lmbActiveRecord.class.php');

class Image extends lmbActiveRecord
{
  protected $_has_one = array('original' => array('field' => 'original_id',
                                                     'class' => 'ImageFileObject'),
                              'thumbnail' => array('field' => 'thumbnail_id',
                                                     'class' => 'ImageFileObject'),
                              'icon' => array('field' => 'icon_id',
                                              'class' => 'ImageFileObject'),
                              'node' => array('field' => 'node_id',
                                              'class' => 'Node'));

  protected function _createValidator()
  {
    $validator = new lmbValidator();
    $validator->addRequiredObjectRule('original');
    $validator->addRequiredObjectRule('thumbnail');
    $validator->addRequiredObjectRule('icon');
    return $validator;
  }

  static function findForParentNode($parent)
  {
    $sql = 'SELECT image.* '.
           ' FROM image LEFT JOIN node ON node.id = image.node_id '.
           ' WHERE node.parent_id = '. $parent->id;

    $stmt = lmbToolkit :: instance()->getDefaultDbConnection()->newStatement($sql);
    return lmbActiveRecord :: decorateRecordSet($stmt->getRecordSet(), 'Image');
  }
}
?>