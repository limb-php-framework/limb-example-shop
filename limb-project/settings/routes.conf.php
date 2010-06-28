<?php
$conf = array(

'HomePage' =>
  array('path' => '/',
        'defaults' => array('controller' => 'home_page', 'action' => 'index')),

'ControllerActionId' =>
  array('path' => '/:controller/:action/:id',
        'defaults' => array('action' => 'index')),

'ControllerAction' =>
  array('path' => '/:controller/:action',
        'defaults' => array('action' => 'index')),

'Controller' =>
  array('path' => '/:controller',
        'defaults' => array('action' => 'index')),

);
?>
