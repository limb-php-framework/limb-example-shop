<?php
lmb_require('limb/validation/src/rule/lmbSingleFieldRule.class.php');
lmb_require('limb/dbal/src/criteria/lmbSQLFieldCriteria.class.php');

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
    $criteria = new lmbSQLFieldCriteria($this->field_name, $value);
    if(!$this->current_user->isNew())
      $criteria->addAnd(lmbSQLCriteria::notEqual('id', $this->current_user->getId()));

    if(User :: findOne($criteria))
      $this->error('Пользователь со значением поля {Field} уже существует');
  }
}