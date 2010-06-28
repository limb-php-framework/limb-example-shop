<?php
lmb_require('limb/datasource/src/lmbArrayDataset.class.php');
lmb_require('limb/web_app/src/fetcher/lmbFetcher.class.php');

@define('DOWNLOADS_CACHE', LIMB_VAR_DIR . '/downloads.cache');
@define('DOWNLOADS_CACHE_TIME', 24*60*60);
//@define('DOWNLOADS_CACHE_TIME', 0);
@define('DOWNLOADS_RSS_URL', 'http://sourceforge.net/export/rss2_projfiles.php?group_id=109345');
@define('DOWNLOADS_2X_PACKAGE', '118079');
@define('DOWNLOADS_3X_PACKAGE', '154950');

class DownloadsFetcher extends lmbFetcher
{
  protected function _createDataSet()
  {
    return new lmbArrayDataset($this->_retrieveItems());
  }

  protected function _retrieveItems()
  {
    if($items = $this->_readCache())
      return $items;

    $items3x = array();
    $items2x = array();

    $xml = get_url_contents(DOWNLOADS_RSS_URL);
    if($rss = @simplexml_load_string($xml))
    {
      foreach($rss->channel->item as $item)
      {
        list($package,$release,) = preg_split('~\s+~', trim((string)$item->title));

        if($package == 'limb-3.x' || $package == 'limb-2.x')
        {
          $link = $this->_extractDownloadLink((string)$item->description);

          if($package == 'limb-3.x')
          {
            //for some reason SF doesn't provide package id :(
            $link .= '&package_id=' . DOWNLOADS_3X_PACKAGE;
            $items3x[] = array('date' => (string)$item->pubDate,
                                   'link' => $link,
                                   'package' => $package,
                                   'release' => $release,
                                   );
          }
          else
          {
            $link .= '&package_id=' . DOWNLOADS_2X_PACKAGE;
            $items2x[] = array('date' => (string)$item->pubDate,
                                   'link' => $link,
                                   'package' => $package,
                                   'release' => $release,
                                   );
          }

        }
      }
      //using only latest releases
      $items = array(array('3x' => array_splice($items3x, 0, 1)),
                     array('2x' => array_splice($items2x, 0, 1)));
      $this->_writeCache($items);
    }
    else  //fallback
      $items = $this->_readCache(false);

    return $items;
  }

  protected function _extractDownloadLink($description)
  {
    //http://sourceforge.net/project/showfiles.php?group_id=xxx&#38;release_id=xxx&#34;&#62;[Download]
    $regex = sprintf(preg_quote("http://sourceforge.net/project/showfiles.php?group_id=%s&release_id=%s"),
                                '\d+', '\d+');
    preg_match("~($regex)~", $description, $m);
    return $m[1];
  }

  protected function _readCache($check_time = true)
  {
    if(file_exists(DOWNLOADS_CACHE))
    {
      if(!$check_time || ((time() - filemtime(DOWNLOADS_CACHE)) < DOWNLOADS_CACHE_TIME))
        return unserialize(file_get_contents(DOWNLOADS_CACHE));
    }
    return array();
  }

  protected function _writeCache($cache)
  {
    file_put_contents(DOWNLOADS_CACHE, serialize($cache), LOCK_EX);
  }
}

?>