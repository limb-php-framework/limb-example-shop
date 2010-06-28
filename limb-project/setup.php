<?php
set_include_path(dirname(__FILE__) . '/' . PATH_SEPARATOR .
                 dirname(__FILE__) . '/lib/' . PATH_SEPARATOR .
                 get_include_path());

if(file_exists(dirname(__FILE__) . '/setup.override.php'))
  require_once(dirname(__FILE__) . '/setup.override.php');

@define('MEDIA_REPOSITORY_DIR', dirname(__FILE__) . '/media/');
@define('LIMB_USE_NATIVE_SESSION_DRIVER', true);
@define('LIMB_VAR_DIR', dirname(__FILE__) . '/var/');
@define('WACT_CONFIG_DIRECTORY', dirname(__FILE__) . '/settings/wact/');

require_once(dirname(__FILE__) . '/common.inc.php');
?>
