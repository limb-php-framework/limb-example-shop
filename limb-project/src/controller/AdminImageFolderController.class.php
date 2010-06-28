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
lmb_require('src/controller/FolderController.class.php');

class AdminImageFolderController extends FolderController
{
  protected $_form_id = 'image_folder_form';
  protected $_controller_name = 'image_folder';

  protected function _initNode($id = null)
  {
    return new ImageFolder($id);
  }
}

?>
