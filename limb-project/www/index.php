<?php
require_once(dirname(__FILE__) . '/../setup.php');
require_once('src/LimbProjectApplication.class.php');

$application = new LimbProjectApplication();
$application->process();

?>
