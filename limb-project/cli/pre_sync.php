<?php
require_once(dirname(__FILE__) . '/../setup.php');

echo "Pre syncing...\n";

$all_shared = dirname(__FILE__) . "/../www/shared";

`rm -rf $all_shared`;
mkdir($all_shared);

foreach(glob(dirname(__FILE__) . "/../lib/limb/*/shared") as $pkg_shared)
{
  echo "Moving $pkg_shared..\n";

  $pkg = basename(dirname($pkg_shared));
  rename($pkg_shared, "$all_shared/$pkg");
}

echo "done.\n";

?>
