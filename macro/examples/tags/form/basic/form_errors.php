<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id: form_errors.php 5174 2007-03-02 09:53:27Z serega $
 * @package    macro_examples
 */

require_once 'limb/macro/common.inc.php';
require_once 'limb/macro/src/lmbMacroTemplate.class.php';
require_once 'limb/macro/src/tags/form/lmbMacroFormErrorList.class.php';

$page = new lmbMacroTemplate('2/page.html');

if($_SERVER['REQUEST_METHOD'] == 'POST')
  _processPost($page);

echo $page->render();

function _processPost($page)
{
  if(isset($_POST['my_input']) && $_POST['my_input'])
    $page->set('text', $_POST['my_input']);
  else
  {
    
    $error_list = new lmbMacroFormErrorList();
    $error_list->addError('"{field1}" must have a value', $fields = array('field1' => 'my_input'));
    $error_list->addError('Any other form error.');
    $page->set('form_my_form_error_list', $error_list);
  }
}
?>
