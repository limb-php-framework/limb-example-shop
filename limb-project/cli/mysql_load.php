<?php
require_once(dirname(__FILE__) . '/../setup.php');

function load($host, $user, $password, $database, $file)
{
  $password = ($password)? '-p' . $password : '';
  $cmd = "mysql -u$user $password -h$host --default-character-set=utf8 $database < $file";

  echo "Starting loading '$file' file to '$database' DB...";

  system($cmd, $ret);

  if(!$ret)
    echo "done! (" . filesize($file) . " bytes)\n";
  else
    echo "error!\n";
}

$dsn = lmbToolkit :: instance()->getDefaultDbDSN();

$host = $dsn->getHost();
$user = $dsn->getUser();
$password = $dsn->getPassword();
$database = $dsn->getDatabase();

$production_sql = dirname(__FILE__) . '/../init/db.mysql';
$tests_sql = dirname(__FILE__) . '/../init/db_tests.mysql';

load($host, $user, $password, $database, $production_sql);
#load($host, $user, $password, $database . '_tests', $tests_sql);

?>
