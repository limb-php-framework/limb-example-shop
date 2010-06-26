<?php
lmb_require('limb/validation/src/rule/lmbSingleFieldRule.class.php');
lmb_require('limb/dbal/src/criteria/lmbSQLFieldCriteria.class.php');
lmb_require('src/model/User.class.php');

class UserUniqueFieldRule extends lmbSingleFieldRule
{
  protected $current_user;

  function __construct($field, $current_user)
  {
    $this->current_user = $current_user;
    parent :: __construct($field);
  }

  function check($value)
  {
    $user = new User();
    $records = $user->findAllRecords(new lmbSQLFieldCriteria($this->field_name, $value));

    if($records->count() && ($this->current_user->isNew() || $this->_sameFieldUserRecordExists($records)))
      $this->error('User with the same value of {Field} already exists');
  }

  function _sameFieldUserRecordExists($records)
  {
    foreach($records as $record)
    {
      if($record->get('id') != $this->current_user->getId())
        return true;
    }
    return false;
  }
}

?>
