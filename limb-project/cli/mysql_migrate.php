<?php
require_once(dirname(__FILE__) . '/../setup.php');

function db_connect_string($host, $user, $password)
{
  return "mysql -h$host -u$user -p$password";
}

function db_load($host, $user, $password, $database, $file)
{
  $cmd = db_connect_string($host, $user, $password) . " $database < $file";

  echo "Starting loading '$file' file to '$database' DB...";

  system($cmd, $ret);

  if(!$ret)
    echo "done! (" . filesize($file) . " bytes)\n";
  else
    echo "error!\n";
}

function db_table_exists($host, $user, $password, $database, $table)
{
  $res = db_exec($host, $user, $password, $database, "show tables");
  return strpos($res, $table) !== false;
}

function db_exec($host, $user, $password, $database, $cmd)
{
  $cmd = db_connect_string($host, $user, $password) . " -e\"$cmd\" -N -B $database";
  return trim(`$cmd`);
}

function get_migration_files_since($base_version)
{
  $files = array();
  $migrations_dir = dirname(__FILE__) . '/../init/migrate/';
  foreach(glob($migrations_dir . '*') as $file)
  {
    list($version, ) = explode('_', basename($file));
    $version = intval($version);
    if($version > $base_version)
      $files[$version] = $file;
  }
  ksort($files);
  return $files;
}

function db_migrate($host, $user, $password, $database)
{
  if(!db_table_exists($host, $user, $password, $database, 'schema_info'))
  {
    db_exec($host, $user, $password, $database, 'CREATE TABLE schema_info ("version" integer default 0);');
    db_exec($host, $user, $password, $database, 'INSERT INTO schema_info VALUES (0);');
  }

  $schema_version = (int)db_exec($host, $user, $password, $database, 'SELECT version FROM schema_info');

  foreach(get_migration_files_since($schema_version) as $version => $file)
  {
    db_load($host, $user, $password, $database, $file);
    db_exec($host, $user, $password, $database, "UPDATE schema_info SET version = $version;");
  }
}

$dsn = lmbToolkit :: instance()->getDefaultDbDSN();

$host = $dsn->getHost();
$user = $dsn->getUser();
$password = $dsn->getPassword();
$database = $dsn->getDatabase();

db_migrate($host, $user, $password, $database);
db_migrate($host, $user, $password, $database . '_tests');

?>
