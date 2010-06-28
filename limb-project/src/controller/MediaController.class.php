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
lmb_require('src/model/Media.class.php');

class MediaController extends lmbController
{
  function doDownload()
  {
    if($media = Media :: findByUid($this->request->get('id')))
    {
      header('Content-type: ' . $media->getMimeType());
      header('Content-Disposition: filename=' . $media->getFileName());
      print($media->getContents());
      exit();
    }
  }
}
?>