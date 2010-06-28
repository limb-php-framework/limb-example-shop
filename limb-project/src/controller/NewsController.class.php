<?php
/**********************************************************************************
* Copyright 2004 BIT, Ltd. http://limb-project.com, mailto: support@limb-project.com
*
* Released under the LGPL license (http://www.gnu.org/copyleft/lesser.html)
***********************************************************************************
*
* $Id$
*
***********************************************************************************/
lmb_require('limb/web_app/src/controller/lmbController.class.php');

class NewsController extends lmbController
{
  function doRss()
  {
    $host = "http://{$_SERVER['SERVER_NAME']}";

    $xw = new xmlWriter();
    $xw->openMemory();

    $xw->startDocument('1.0','UTF-8');
    $xw->startElement ('rss'); // <rss>

    $xw->startElement('channel'); // <channel>
    $xw->writeAttribute('version', '2.0');

    $xw->writeElement('link', "$host/news/rss");

    $xw->writeElement('description', 'Limb PHP framework last news');
    $xw->writeElement('language', 'english');
    $xw->writeElement('managingEditor', 'support@limb-project.com');
    $xw->writeElement('webMaster', 'support@limb-project.com');

    $now = date("F j, Y, g:i a");
    $xw->writeElement('pubDate', $now);
    $xw->writeElement('lastBuildDate', $now);

    $xw->writeElement('generator', 'internal generator');
    $xw->writeElement('ttl', '1');

    foreach(News :: findPublished()->paginate(15, 0) as $news)
    {
      $xw->startElement('item');

      $xw->writeElement('title', $news->getTitle());
      $xw->writeElement('link', $host . '/news/show/' . $news->getId());

      $xw->startElement('description');
      $xw->writeCData($news->getContent());
      $xw->endElement();

      $xw->writeElement('author', $news->getAuthor()->getLogin());

      $xw->startElement('guid');
      $xw->writeAttribute('isPermaLink', 'true');
      $xw->text($host . '/news/show/' . $news->getId());
      $xw->endElement();

      $xw->writeElement('pubDate',  date("F j, Y, g:i a", $news->getNewsDate()));

      $xw->endElement();
    }

    $xw->endElement(); // </channel>
    $xw->endElement(); // </rss>

    header("Content-Type: application/xml");
    return $xw->outputMemory(true);
  }
}

?>
