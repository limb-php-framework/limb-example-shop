<?php
lmb_require('limb/datasource/src/lmbArrayDataset.class.php');
lmb_require('limb/web_app/src/fetcher/lmbFetcher.class.php');

@define('LAST_FORUM_TOPICS_CACHE', LIMB_VAR_DIR . '/forum_topics.cache');
@define('LAST_FORUM_TOPICS_CACHE_TIME', 5*60);
@define('LAST_FORUM_TOPICS_RSS_URL', 'http://forum.limb-project.com/rss.php');
@define('LAST_FORUM_TOPICS_ITEMS', 5);

class LastForumTopicsFetcher extends lmbFetcher
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

    $xml = get_url_contents(LAST_FORUM_TOPICS_RSS_URL);
    if($rss = @simplexml_load_string($xml))
    {
      foreach($rss->channel->item as $item)
      {
        $items[] = array('date' => (string)$item->pubDate,
                           'link' => (string)$item->link,
                           'author' => (string)$item->author,
                           'title' => (string)$item->title);

        if(sizeof($items) + 1 > LAST_FORUM_TOPICS_ITEMS)
          break;
      }
      $this->_writeCache($items);
    }
    else  //fallback
      $items = $this->_readCache(false);

    return $items;
  }

  protected function _readCache($check_time = true)
  {
    if(file_exists(LAST_FORUM_TOPICS_CACHE))
    {
      if(!$check_time || ((time() - filemtime(LAST_FORUM_TOPICS_CACHE)) < LAST_FORUM_TOPICS_CACHE_TIME))
        return unserialize(file_get_contents(LAST_FORUM_TOPICS_CACHE));
    }
    return array();
  }

  protected function _writeCache($cache)
  {
    file_put_contents(LAST_FORUM_TOPICS_CACHE, serialize($cache), LOCK_EX);
  }
}

?>
