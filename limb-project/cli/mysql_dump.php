<?php
require_once(dirname(__FILE__) . '/../setup.php');

function dump($host, $user, $password, $database, $file)
{
  $password = ($password)? '-p' . $password : '';
  $cmd = "mysqldump -u$user $password -h$host $database " .
         "--skip-opt --compact --add-drop-table --create-options --quick --set-charset " .
         "--allow-keywords --max_allowed_packet=16M --quote-names " .
         "--complete-insert --result-file=$file";

  echo "Starting dumping to '$file' file...";

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

if(!is_dir(dirname(__FILE__) . '/../init'))
  mkdir(dirname(__FILE__) . '/../init');

$production_sql = dirname(__FILE__) . '/../init/db.mysql';
$tests_sql = dirname(__FILE__) . '/../init/db_tests.mysql';

dump($host, $user, $password, $database, $production_sql);
//dump($host, $user, $password, $database . '_tests', $tests_sql);

?>
