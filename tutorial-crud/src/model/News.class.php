<?php
lmb_require('limb/active_record/src/lmbActiveRecord.class.php');

class News extends lmbActiveRecord
{
  protected function _createValidator()
  {
    $validator = new lmbValidator();

    $validator->addRequiredRule('title');
    $validator->addRequiredRule('annotation');
    $validator->addRequiredRule('date');

    lmb_require('limb/validation/src/rule/lmbSizeRangeRule.class.php');
    $validator->addRule(new lmbSizeRangeRule('title', 75));
    return $validator;
  }
}
?>