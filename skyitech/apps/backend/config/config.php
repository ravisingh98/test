<?php

// include project configuration
include(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');

// symfony bootstraping
require_once($sf_symfony_lib_dir.'/util/sfCore.class.php');
sfCore::bootstrap($sf_symfony_lib_dir, $sf_symfony_data_dir);

$sf_root_dir = sfConfig::get('sf_root_dir');
sfConfig::add(array(
  'sf_web_dir_name' => '../',
  'sf_web_dir'      => $sf_root_dir.DIRECTORY_SEPARATOR.'../',
  'sf_upload_dir_name' => 'siteuploads',
  'sf_upload_dir'   => $sf_root_dir.DIRECTORY_SEPARATOR.'../'.'siteuploads',
));
