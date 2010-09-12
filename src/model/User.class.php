<?php

class User extends lmbActiveRecord
{
  protected $_db_table_name = 'user';

  protected $_has_many = array('orders' => array('field' => 'user_id',
                                                 'class' => 'Order',
                                                 'sort_params' => array('date' => 'DESC')));

  protected $_default_sort_params = array('name' => 'ASC');

  protected $_lazy_attributes = array('address');

  protected $password;

  protected $is_logged_in = false;

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

  function login($login, $password)
  {
    $hashed_password = User :: cryptPassword($password);

    $criteria = new lmbSQLFieldCriteria('login', $login);
    $criteria->addAnd(new lmbSQLFieldCriteria('hashed_password', $hashed_password));

    if($user = lmbActiveRecord :: findFirst('User', array('criteria' => $criteria)))
    {
      $this->import($user);
      $this->setIsNew(false);
      $this->setIsLoggedIn(true);
      return true;
    }
    else
    {
      $this->setIsLoggedIn(false);
      return false;
    }
  }

  function logout()
  {
    $this->is_logged_in = false;
  }

}