<?php
require_once('limb/cms/settings/navigation.conf.php');

$conf[lmbCmsUserRoles :: ADMIN][0]['children'][] =
  array(
    "title" => "Товары",
    "url" => "/admin_product/",
    "icon" => "/shared/cms/images/icons/report.png",
);

$conf[lmbCmsUserRoles :: ADMIN][0]['children'][] =
  array(
    "title" => "Пользователи",
    "url" => "/admin_user/",
    "icon" => "/shared/cms/images/icons/user.png",
);
