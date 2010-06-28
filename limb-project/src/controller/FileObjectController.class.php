<?php
lmb_require('limb/web_app/src/controller/lmbController.class.php');

class FileObjectController extends lmbController
{
  function doShow()
  {
    if($file_object = FileObject :: findByUid('FileObject', $this->request->get('id')))
    {
      if(!file_exists($file_path = $file_object->getFilePath()))
        return;

      header('Content-type: ' . $file_object->getMimeType());
      header('Content-Disposition: filename=' . $file_object->getName());
      print(file_get_contents($file_path));
      exit();
    }
  }
}
?>