<?php
lmb_require('limb/cms/src/controller/lmbAdminObjectController.class.php');
lmb_require('limb/util/system/lmbFs.class.php');

class AdminProductController extends lmbAdminObjectController
{
  protected $_object_class_name = 'Product';

  protected function _onAfterImport()
  {
  	return $this->_uploadImage($this->item, $this->request->get('image'));
  }

  protected function _uploadImage($item, $uploaded_image)
  {
    if(!$uploaded_image)
      return;

    if(!$uploaded_image['name'] || !$uploaded_image['tmp_name'])
      return;

    $file_name = $uploaded_image['name'];
    $file_path = $uploaded_image['tmp_name'];

    $dest_path = lmb_env_get('PRODUCT_IMAGES_DIR') . $file_name;
    lmbFs :: cp($file_path, $dest_path);

    unlink($file_path);

    $item->setImageName($file_name);
  }

  function doSetAvailable()
  {
  	return $this->_changeAvailability(true);
  }

  function doSetUnavailable()
  {
  	return $this->_changeAvailability(false);
  }

  protected function _changeAvailability($is_available)
  {
  	if(!$ids = $this->request->getArray('ids'))
    {
      $this->_endDialog();
      return;
    }

    $products = lmbActiveRecord :: findByIds('Product', $ids);
    foreach($products as $product)
    {
      $product->setIsAvailable((int) $is_available);
      $product->save();
    }

    $this->_endDialog();
  }
}
