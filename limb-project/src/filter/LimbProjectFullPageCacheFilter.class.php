<?php
lmb_require('limb/web_cache/src/filter/lmbFullPageCacheFilter.class.php');

class LimbProjectFullPageCacheFilter extends lmbFullPageCacheFilter
{
  function run($filter_chain)
  {
    lmbActiveRecord :: registerGlobalOnAfterSaveCallback($this, '_onUpdateCallback');
    lmbActiveRecord :: registerGlobalOnAfterDestroyCallback($this, '_onUpdateCallback');

    parent :: run($filter_chain);
  }

  function _onUpdateCallback($object)
  {
    $this->cache->flush();
  }
}

?>