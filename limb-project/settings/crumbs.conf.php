<?php

lmb_require('src/fetcher/RequestedNodeFetcher.class.php');

//admin
function crumb_for_admin_news_index($crumbs, $params)
{
  $crumbs->addRoute('News');
}

//front
function crumb_for_user_login($crumbs, $params)
{
  $crumbs->addRoute('Login');
}

function crumb_for_news_index($crumbs, $params)
{
  $crumbs->addRoute('News');
}

function crumb_for_news_show($crumbs, $params)
{
  $crumbs->addRoute('News');
}

function crumb_for_main_page_index($crumbs, $params)
{
  $crumbs->addUrl('Home', '/');
}

function crumb_for_document_show($crumbs, $params)
{
  $crumbs->need('main_page', 'index');

  $fetcher = new RequestedNodeFetcher();
  $current_node = $fetcher->fetchOne();

  $node = $current_node;
  while($parent = $node->getParent())
  {
    $crumbs->addUrl($parent->getTitle(), $parent->getUrlPath());
    $node = $parent;
  }

  $crumbs->addUrl($current_node->getTitle(), $current_node->getUrlPath());
}

?>
