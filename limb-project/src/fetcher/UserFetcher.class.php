<?php
lmb_require('limb/web_app/src/fetcher/lmbFetcher.class.php');

class UserFetcher extends lmbFetcher
{
  function _createDataset()
  {
    return new lmbArrayDataset(array(lmbToolkit :: instance()->getUser()));
  }
}

?>
