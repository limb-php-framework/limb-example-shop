<?php
/*
 * Limb PHP Framework
 *
 * @link http://limb-project.com 
 * @copyright  Copyright &copy; 2004-2007 BIT(http://bit-creative.com)
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html 
 */
 
require_once(dirname(__FILE__) . '/../setup.php');
require_once('src/lmbShopApplication.class.php');

$application = new lmbShopApplication();
$application->process();
