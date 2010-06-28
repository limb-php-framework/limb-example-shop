<?php

class User extends lmbActiveRecord
{
  protected $_has_many = array('news' => array('field' => 'author_id',
                                               'class' => 'News'));

  protected $password;
  protected $is_logged_in = false;

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
    $this->removeAll();
    $this->is_logged_in = false;
  }

  function isLoggedIn()
  {
    return $this->is_logged_in;
  }

  function setLoggedIn($logged_in)
  {
    $this->is_logged_in = $logged_in;
  }

  function getFullName()
  {
    return $this->getLastName() . ' ' . $this->getFirstName() . ' ' . $this->getMiddleName();
  }

  function cryptPassword($password)
  {
    return md5($password);
  }

  function passwordIsCorrect($password)
  {
    return ($this->getHashedPassword() == self :: cryptPassword($password));
  }

  function generatePassword()
  {
    $alphabet = array(
        array('b','c','d','f','g','h','g','k','l','m','n','p','q','r','s','t','v','w','x','z',
              'B','C','D','F','G','H','G','K','L','M','N','P','Q','R','S','T','V','W','X','Z'),
        array('a','e','i','o','u','y','A','E','I','O','U','Y'),
    );

    $new_password = '';
    for($i = 0; $i < 9 ;$i++)
    {
      $j = $i%2;
      $min_value = 0;
      $max_value = count($alphabet[$j]) - 1;
      $key = rand($min_value, $max_value);
      $new_password .= $alphabet[$j][$key];
    }
    return $new_password;
  }
}
?>