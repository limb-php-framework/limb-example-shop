<?php
lmb_require('src/model/Product.class.php');

class AdminProductController extends lmbController
{
  function doCreate()
  {
    $this->_performCreateOrEdit();
  }

  function doEdit()
  {
    $this->_performCreateOrEdit();
  }

  protected function _performCreateOrEdit()
  {
    if(!$id = (int)$this->request->getInteger('id'))
      $id = null;

    $item = new Product($id);
    $this->useForm('product_form');
    $this->setFormDatasource($item);
    $this->product = $item;

    if($this->request->hasPost())
    {
      $item->import($this->request);
      $this->_uploadImage($item);

      if($item->trySave($this->error_list))
        $this->redirect();
    }
  }

  function _uploadImage($item)
  {
    if(!$uploaded_image = $this->request->get('image'))
      return;

    if(!$uploaded_image['name'] || !$uploaded_image['tmp_name'])
      return;

    $file_name = $uploaded_image['name'];
    $file_path = $uploaded_image['tmp_name'];

    lmb_require('limb/util/system/lmbFs.class.php');

    $dest_path = PRODUCT_IMAGES_DIR . $file_name;
    lmbFs :: cp($file_path, $dest_path);

    unlink($file_path);

    $item->setImageName($file_name);
  }

  function doDelete()
  {
    $item = new Product($this->request->getInteger('id'));
    $item->destroy();
    $this->redirect();
  }

  function doChangeAvailability()
  {
    if(!$ids = $this->request->getArray('ids'))
    {
      $this->redirect();
      return;
    }

    $available = false;
    if($this->request->get('set_available'))
      $available = true;

    $products = lmbActiveRecord :: findByIds('Product', $ids);
    foreach($products as $product)
    {
      $product->setIsAvailable($available);
      $product->save();
    }

    $this->redirect();
  }
}
?>