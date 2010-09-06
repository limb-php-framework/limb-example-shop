<?php

class Product extends lmbActiveRecord
{
  protected $_default_sort_params = array('title' => 'ASC');

  protected function _createValidator()
  {
    $validator = new lmbValidator();
    $validator->addRequiredRule('title');
    $validator->addRequiredRule('description');
    $validator->addRequiredRule('price');
    return $validator;
  }

  static function findForFront()
  {
    $request = lmbToolkit :: instance()->getRequest();

    lmb_require('limb/dbal/src/criteria/lmbSQLFieldCriteria.class.php');
    lmb_require('limb/dbal/src/criteria/lmbSQLRawCriteria.class.php');

    $criteria = new lmbSQLRawCriteria('is_available = 1');

    if(!self :: _appendLetterCriteria($criteria, $request))
      self :: _appendSearchCriteria($criteria, $request);

    return lmbActiveRecord :: find('Product', $criteria);
  }

  static protected function _appendSearchCriteria($criteria, $request)
  {
    if(!$request->get('search'))
      return false;

    if($product = $request->get('product'))
      $criteria->addAnd(new lmbSQLFieldCriteria('title', '%' . $product . '%', lmbSQLFieldCriteria :: LIKE));

    if($price_less = $request->get('price_less'))
      $criteria->addAnd(new lmbSQLFieldCriteria('price', $price_less, lmbSQLFieldCriteria :: LESS));

    if($price_greater = $request->get('price_greater'))
      $criteria->addAnd(new lmbSQLFieldCriteria('price', $price_greater, lmbSQLFieldCriteria :: GREATER));
  }

  static protected function _appendLetterCriteria($criteria, $request)
  {
    if($letter = $request->get('letter'))
    {
      $criteria->addAnd(new lmbSQLFieldCriteria('title', $letter . '%', lmbSQLFieldCriteria :: LIKE));
      return true;
    }
  }

  function getImagePath()
  {
    if(($image_name = $this->getImageName()) && file_exists(PRODUCT_IMAGES_DIR . $image_name))
      return LIMB_HTTP_BASE_PATH . 'product_images/' . $image_name;
    else
      return LIMB_HTTP_BASE_PATH . 'images/no_image.gif';
  }
}

?>
