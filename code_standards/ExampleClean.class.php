<?php
/*
 * Limb PHP Framework
 *
 * @link http://limb-project.com
 * @copyright  Copyright &copy; 2004-2007 BIT(http://bit-creative.com)
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 */

/**
 * @package foo
 * @version $Id$
 */
lmb_require('limb/some/package/Foo.class.php');

/**
 * Some FooClass description
 *
 * @version $Id$
 * @package foo
 */
class ExampleClean
{
  /**
   * @var string foo name
   */
  protected $foo_name;
  /**
   * @var object request
   */
  protected $request;

  /**
   *  Constructor.
   *  Creates an instance of FooClass object in different ways depending on passed argument
   *  @param object request
   */
  function __construct($request)
  {
    $this->request = $request;
    $this->foo_name = 'Foo';
  }

  /**
   *  Echoes some stuff
   */
  function doIt()
  {
    if($this->foo_name == 'Bar')
    {
      $res = globalDoIt();
      echo $res;
    }

    //в простых случаях {} можно опускать
    if($this->foo_name == 'Test')
      return;

    //логически сгруппированный блок кода
    $db = getDbConnection();
    $it = $db->exec('select * from a');

    foreach($it as $record)
      echo $record->get('id');

    $i++;
  }

  protected function _createBar()
  {
    return new Bar();
  }
}

/**
 *  Some global function
 *  @return integer some number
 */
function global_do_it()
{
  $some_var = 1;
  return 1;
}