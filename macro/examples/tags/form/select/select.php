<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id: select.php 5021 2007-02-12 13:04:07Z pachanga $
 * @package    macro_examples
 */

require_once 'limb/macro/common.inc.php';
require_once 'limb/macro/src/lmbMacroTemplate.class.php';

$page = new lmbMacroTemplate('1/page.html');
$page->set('my_form1', $_POST);
$page->set('my_form2', $_POST);

$page->set('options', array('no_value' => '------',
                            'first' => 'First option',
                            'second' => 'Second option'));

echo $page->render();

?>
