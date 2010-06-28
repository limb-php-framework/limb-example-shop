<?php

$dir = dirname(__FILE__);

echo "Post syncing...";

`rm -rf $dir/../var/fpcache`;
`rm -rf $dir/../var/compiled`;
`rm -rf $dir/../var/locators`;

echo "done.\n";

?>
