<?php
require_once(dirname(__FILE__) . '/../setup.php');
set_time_limit(0);

$dsn = lmbToolkit :: instance()->getDefaultDbDSN();

$host = $dsn->getHost();
$user = $dsn->getUser();
$password = $dsn->getPassword();
$database = $dsn->getDatabase();

$production_sql = dirname(__FILE__) . '/../init/db.mysql';

load($host, $user, $password, $database, $production_sql);

function load($host, $user, $password, $database, $file)
{
  $cmd = "mysql -u$user -p$password -h$host --default-character-set=utf8 $database < $file";

  echo "Starting loading '$file' file to '$database' DB...";

  system($cmd, $ret);

  if(!$ret)
    echo "done! (" . filesize($file) . " bytes)\n";
  else
    echo "error!\n";
}

?>
