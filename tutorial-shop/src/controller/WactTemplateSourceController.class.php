<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id$
 * @package    web_app
 */

lmb_require('limb/web_app/src/controller/lmbWactTemplateSourceController.class.php');

class WactTemplateSourceController extends lmbWactTemplateSourceController
{
  function doDisplay()
  {
    require_once 'limb/wact/src/compiler/templatecompiler.inc.php';
    return parent :: doDisplay();
  }
}

?>
