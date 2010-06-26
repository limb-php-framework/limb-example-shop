<?php
set_include_path(dirname(__FILE__) . PATH_SEPARATOR .
                 dirname(__FILE__) . '/lib/' . PATH_SEPARATOR);

if(file_exists(dirname(__FILE__) . '/setup.override.php'))
  require_once(dirname(__FILE__) . '/setup.override.php');

if(!defined('LIMB_VAR_DIR') && is_dir(dirname(__FILE__). '/var/'))
  define('LIMB_VAR_DIR', dirname(__FILE__) . '/var/');
elseif(defined('LIMB_VAR_DIR') && !is_dir(LIMB_VAR_DIR))
  throw new Exception('Constant LIMB_VAR_DIR defined but no directory really exists at "' . LIMB_VAR_DIR. '"');

@define('PRODUCT_IMAGES_DIR', dirname(__FILE__) . '/www/product_images/');

require_once('limb/cms/common.inc.php');
require_once('common.inc.php');

error_reporting(E_ALL);

lmb_require('src/model/*.class.php')
?>