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
lmb_require('src/FileRepositoryBrowser.class.php');
lmb_require('src/ImageRepositoryBrowser.class.php');

class AdminMediaController extends lmbController
{
  function doProcessCommand()
  {
    $resource_type = $this->request->get('Type');
    $current_folder = $this->request->get('CurrentFolder');
    $command = $this->request->get('Command');

    $browser = $this->_createBrowser($resource_type);
    $browser->setCurrentFolderPath($current_folder);

    $this->_setXmlHeaders();

    $xml = 	'<?xml version="1.0" encoding="utf-8" ?>';
    $xml .= '<Connector command="' . $command . '" resourceType="' . $resource_type . '">' ;
    $xml .= '<CurrentFolder path="' . $current_folder . '" url="/" />' ;

    $xml .= '<Folders>' . $browser->renderFolders() . '</Folders>';


    $xml .= '<Files>' . $browser->renderFiles() . '</Files>';

    $xml .= '</Connector>';

    return $xml;
  }

  protected function _createBrowser($type)
  {
    if($type == 'File')
      return new FileRepositoryBrowser();
    if($type == 'Image')
      return new ImageRepositoryBrowser();

    return new FileRepositoryBrowser();
  }

  protected function _setXmlHeaders()
  {
    $this->response->header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
    $this->response->header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
    $this->response->header('Cache-Control: no-store, no-cache, must-revalidate') ;
    $this->response->header('Cache-Control: post-check=0, pre-check=0', false) ;
    $this->response->header('Pragma: no-cache') ;
    $this->response->header( 'Content-Type:text/xml; charset=utf-8' ) ;
  }
}

?>
