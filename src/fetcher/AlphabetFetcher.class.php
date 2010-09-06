<?php
lmb_require('limb/core/src/lmbCollection.class.php');
lmb_require('limb/web_app/src/fetcher/lmbFetcher.class.php');

class AlphabetFetcher extends lmbFetcher
{
  function _createDataset()
  {
    return new lmbCollection(AlphabetHelper :: getAlphabet());
  }
}

?>
