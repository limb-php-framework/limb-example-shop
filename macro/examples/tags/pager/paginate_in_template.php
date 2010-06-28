<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id: pager_with_elipses.php 5021 2007-02-12 13:04:07Z pachanga $
 * @package    macro_examples
 */

require_once 'limb/macro/common.inc.php';
require_once 'limb/macro/src/lmbMacroTemplate.class.php';
require_once 'limb/core/src/lmbCollection.class.php';

$page = new lmbMacroTemplate('paginate/page.html');
include('data.inc.php');
$page->set('php_modules', new lmbCollection($data));
echo $page->render();

?>
