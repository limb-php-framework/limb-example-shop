<?php
lmb_require('src/model/Product.class.php');

class ProductController extends lmbController
{
  function doSearch()
  {
    $this->setTemplate($this->_findTemplateForAction('display'));
    $this->useForm('search_form');
    $this->setFormDatasource($this->request);
  }
}
?>

