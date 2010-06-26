<?php
lmb_require('limb/web_app/src/controller/lmbController.class.php');

class RssController extends lmbController
{
	function doDisplay() {
		$params = array(
			'sort' => array('date' => 'DESC', 'title' => 'ASC'),
			'limit' => 5
		);
		$this->news = lmbActiveRecord::find('News', $params);
		$this->response->addHeader('Content-type: application/xml');
	}
}