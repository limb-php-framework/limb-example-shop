<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id$
 * @package    wact
 */

set_include_path(dirname(__FILE__) . '/' . PATH_SEPARATOR .
                 dirname(__FILE__) . '/lib/' . PATH_SEPARATOR);

if(file_exists(dirname(__FILE__) . '/setup.override.php'))
  require_once(dirname(__FILE__) . '/setup.override.php');

@define('LIMB_VAR_DIR', dirname(__FILE__) . '/var/');

@define('MACRO_HTTP_BASE_PATH', "http://examples.limb-project.com/macro/");

require_once('limb/macro/common.inc.php');

?>
