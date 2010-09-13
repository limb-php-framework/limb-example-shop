<?php
require_once('limb/cms/settings/navigation.conf.php');

$conf[lmbCmsUserRoles :: ADMIN][0]['children'][] =
  array(
    "title" => "Products",
    "url" => "/admin_product/",
    "icon" => "/shared/cms/images/icons/report.png",
);

$conf[lmbCmsUserRoles :: ADMIN][0]['children'][] =
  array(
    "title" => "Users",
    "url" => "/admin_user/",
    "icon" => "/shared/cms/images/icons/user.png",
);

$conf[lmbCmsUserRoles :: ADMIN][0]['children'][] =
  array(
    "title" => "Orders",
    "url" => "/admin_order/",
    "icon" => "/shared/cms/images/icons/money.png",
);
