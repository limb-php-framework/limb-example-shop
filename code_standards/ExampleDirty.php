<?php

require_once('limb/some/package/Foo.class.php'); //warning

class ExampleDirtyAnotherClass //error in file/class name
{
  protected $fooName;

  function do_it()
  {
    if($this->foo_name == 'Bar') {
      $res = globalDoIt();
      echo $res;
    }

    False;

    //в простых случаях {} можно опускать
    if($this->foo_name == 'Test') return;

  }
}

/**
 *  Some global function
 *  @return integer some number
 */
function globalDoIt()
{
  $some_var = 1;
  return 1;
}