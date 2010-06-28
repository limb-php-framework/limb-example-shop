<?php

require_once('limb/core/common.inc.php');
require_once('limb/web_app/common.inc.php');
require_once(dirname(__FILE__) . '/toolkit.inc.php');
lmb_require(dirname(__FILE__) . '/src/model/*');

function get_url_contents($url)
{
  //if(!defined('USE_PROXY'))
  //  return file_get_contents($url);

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 2);
  if(defined('USE_PROXY'))
    curl_setopt($curl, CURLOPT_PROXY, USE_PROXY);
  $page = trim(curl_exec($curl));
  curl_close($curl);
  return $page;
}

?>
