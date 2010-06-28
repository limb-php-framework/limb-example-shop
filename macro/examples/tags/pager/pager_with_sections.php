<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id: pager_with_sections.php 5021 2007-02-12 13:04:07Z pachanga $
 * @package    macro_examples
 */

require_once 'limb/macro/common.inc.php';
require_once 'limb/macro/src/lmbMacroTemplate.class.php';
require_once 'limb/core/src/lmbCollection.class.php';

$page = new lmbMacroTemplate('sections/page.html');
include('data.inc.php');
$iterator = new lmbCollection($data);

// here is an example how to do pagination without using {{paginate}} tag
lmb_require('limb/macro/src/tags/pager/lmbMacroPagerHelper.class.php');
$pager = new lmbMacroPagerHelper('pager');
$pager->setItemsPerPage($items_per_page = 5);
$pager->setTotalItems($total_items = $iterator->count());
$pager->prepare();
$iterator->paginate($pager->getCurrentPageOffset(), $items_per_page);
$page->set('items_per_page', $items_per_page);
$page->set('total_items', $total_items);

$page->set('php_modules', $iterator);

echo $page->render();

?>
