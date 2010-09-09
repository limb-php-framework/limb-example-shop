<?php

class User extends lmbActiveRecord
{
  protected $_db_table_name = 'user';

  protected $_default_sort_params = array('name' => 'ASC');

  protected $_lazy_attributes = array('address');

  protected $password;

  protected function _defineRelations()
  {
    $this->_has_many = array (
      'orders' =>
        array (
          'field' => 'user_id',
          'class' => 'Order',
        ),
    );  }

  protected function _createValidator()
  {
    $validator = new lmbValidator();
    $validator->addRequiredRule('login');

    $validator->addRequiredRule('email');
    $validator->addRequiredRule('name');

    lmb_require('src/validation/UserUniqueFieldRule.class.php');
    $validator->addRule(new UserUniqueFieldRule('login', $this));
    $validator->addRule(new UserUniqueFieldRule('email', $this));

    lmb_require('limb/validation/src/rule/lmbEmailRule.class.php');
    $validator->addRule(new lmbEmailRule('email'));
    return $validator;
  }

  protected function _onBeforeSave()
  {
    $this->_generatePassword();
  }

  protected function _generatePassword()
  {
    if($this->password)
      $this->setHashedPassword(self :: cryptPassword($this->password));
  }

  static function cryptPassword($password)
  {
    return md5($password);
  }

}