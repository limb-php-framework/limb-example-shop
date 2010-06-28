<?php
lmb_require('limb/datasource/src/lmbArrayDataset.class.php');
lmb_require('limb/web_app/src/fetcher/lmbFetcher.class.php');

@define('LAST_COMMITS_CACHE', LIMB_VAR_DIR . '/last_commmits.cache');
@define('LAST_COMMITS_CACHE_TIME', 15*60);
@define('LAST_COMMITS_ITEMS', 5);
@define('LAST_COMMITS_RSS_URL', 'http://fisheye.limb-project.com/changelog/%7Erss%2Cfeedmax%3D' . LAST_COMMITS_ITEMS . '/limb/rss.xml');

class LastCommitsFetcher extends lmbFetcher
{
  protected function _createDataSet()
  {
    return new lmbArrayDataset($this->_retrieveItems());
  }

  protected function _retrieveItems()
  {
    $items = array();

    if($items = $this->_readCache())
      return $items;

    $xml = get_url_contents(LAST_COMMITS_RSS_URL);
    if($rss = @simplexml_load_string($xml))
    {
      foreach($rss->channel->item as $item)
      {
        $items[] = array('date' => (string)$item->pubDate,
                           'link' => (string)$item->link,
                           'author' => (string)$item->author,
                           //removing weird first string "root:author:..."
                           'title' => preg_replace('~^root\s*:\s*\w+\s*:~', '', (string)$item->title));
      }
      $this->_writeCache($items);
    }
    else  //fallback
      $items = $this->_readCache(false);

    return $items;
  }

  protected function _readCache($check_time = true)
  {
    if(file_exists(LAST_COMMITS_CACHE))
    {
      if(!$check_time || ((time() - filemtime(LAST_COMMITS_CACHE)) < LAST_COMMITS_CACHE_TIME))
        return unserialize(file_get_contents(LAST_COMMITS_CACHE));
    }
    return array();
  }

  protected function _writeCache($cache)
  {
    file_put_contents(LAST_COMMITS_CACHE, serialize($cache), LOCK_EX);
  }
}

?>