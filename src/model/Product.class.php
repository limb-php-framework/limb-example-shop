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

  static function findForFront($params = array())
  {
  	$criteria = lmbSQLCriteria::equal('is_available', 1);
  	if (isset($params['title']))
  	  $criteria->addAnd(lmbSQLCriteria :: like('title', $params['title'].'%'));
  	if (isset($params['price_greater']))
      $criteria->addAnd(lmbSQLCriteria :: greater('price', (int) $params['price_greater']));
    if (isset($params['price_less']))
      $criteria->addAnd(lmbSQLCriteria :: less('price', (int) $params['price_less']));
  	return Product :: find($criteria);
  }

  function getImagePath()
  {
    if(($image_name = $this->getImageName()) && file_exists(lmb_env_get('PRODUCT_IMAGES_DIR') . $image_name))
      return LIMB_HTTP_BASE_PATH . 'product_images/' . $image_name;
    else
      return LIMB_HTTP_BASE_PATH . '/shared/cms/images/icons/cancel.png';
  }
}