<?php

require_once('limb/cms/settings/navigation.conf.php');

$navigations = array(
  'order' => array(        
    "title" => "Orders",
    "url" => "/admin_order/",
    "icon" => "/shared/cms/images/icons/money.png",
  ),
  'product' => array(        
    "title" => "Books",
    "url" => "/admin_product/",
    "icon" => "/shared/cms/images/icons/report.png",
  ),
  'user' => array(        
    "title" => "Site users",
    "url" => "/admin_user/",
    "icon" => "/shared/cms/images/icons/user_suit.png",
  ),
);

$conf[lmbCmsUserRoles::EDITOR][0]['children'] += $navigations; 
$conf[lmbCmsUserRoles::ADMIN][0]['children'] += $navigations;
