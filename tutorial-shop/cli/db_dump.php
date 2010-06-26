<?php
require_once(dirname(__FILE__) . '/../setup.php');
set_time_limit(0);

$dsn = lmbToolkit :: instance()->getDefaultDbDSN();

$host = $dsn->getHost();
$user = $dsn->getUser();
$password = $dsn->getPassword();
$database = $dsn->getDatabase();

$production_sql = dirname(__FILE__) . '/../init/db.mysql';

dump($host, $user, $password, $database, $production_sql);

function dump($host, $user, $password, $database, $file)
{
  $cmd = "mysqldump -u$user -p$password -h$host $database " .
         "--skip-opt --compact --add-drop-table --create-options --quick --set-charset " .
         "--allow-keywords --max_allowed_packet=16M --quote-names " .
         "--complete-insert --result-file=$file --default-character-set=utf8";

  echo "Starting dumping to '$file' file...";

  system($cmd, $ret);

  if(!$ret)
    echo "done! (" . filesize($file) . " bytes)\n";
  else
    echo "error!\n";
}

?>
