<?php
// auto-generated by sfCompileConfigHandler
// date: 2017/11/12 15:17:06


$sf_symfony_lib_dir = sfConfig::get('sf_symfony_lib_dir');
if (!sfConfig::get('sf_in_bootstrap'))
{
    require_once($sf_symfony_lib_dir.'/util/sfYaml.class.php');
    require_once($sf_symfony_lib_dir.'/cache/sfCache.class.php');
  require_once($sf_symfony_lib_dir.'/cache/sfFileCache.class.php');
    require_once($sf_symfony_lib_dir.'/config/sfConfigCache.class.php');
  require_once($sf_symfony_lib_dir.'/config/sfConfigHandler.class.php');
  require_once($sf_symfony_lib_dir.'/config/sfYamlConfigHandler.class.php');
  require_once($sf_symfony_lib_dir.'/config/sfAutoloadConfigHandler.class.php');
  require_once($sf_symfony_lib_dir.'/config/sfRootConfigHandler.class.php');
  require_once($sf_symfony_lib_dir.'/config/sfLoader.class.php');
    require_once($sf_symfony_lib_dir.'/exception/sfException.class.php');
  require_once($sf_symfony_lib_dir.'/exception/sfAutoloadException.class.php');
  require_once($sf_symfony_lib_dir.'/exception/sfCacheException.class.php');
  require_once($sf_symfony_lib_dir.'/exception/sfConfigurationException.class.php');
  require_once($sf_symfony_lib_dir.'/exception/sfParseException.class.php');
    require_once($sf_symfony_lib_dir.'/util/sfParameterHolder.class.php');
}
else
{
  require_once($sf_symfony_lib_dir.'/config/sfConfigCache.class.php');
}
sfCore::initAutoload();
try
{
  $configCache = sfConfigCache::getInstance();
    if (function_exists('date_default_timezone_get'))
  {
    if ($default_timezone = sfConfig::get('sf_default_timezone'))
    {
      date_default_timezone_set($default_timezone);
    }
    else if (sfConfig::get('sf_force_default_timezone', true))
    {
      date_default_timezone_set(@date_default_timezone_get());
    }
  }
    $sf_app_config_dir_name = sfConfig::get('sf_app_config_dir_name');
  $sf_debug = sfConfig::get('sf_debug');
    if ($sf_debug)
  {
    require_once($sf_symfony_lib_dir.'/debug/sfTimerManager.class.php');
    require_once($sf_symfony_lib_dir.'/debug/sfTimer.class.php');
  }
    // 'config/settings.yml' config file
// auto-generated by sfDefineEnvironmentConfigHandler
// date: 2017/11/12 15:17:06
sfConfig::add(array(
  'sf_default_module' => 'default',
  'sf_default_action' => 'index',
  'sf_error_404_module' => 'default',
  'sf_error_404_action' => 'error404',
  'sf_login_module' => 'default',
  'sf_login_action' => 'login',
  'sf_secure_module' => 'default',
  'sf_secure_action' => 'secure',
  'sf_module_disabled_module' => 'default',
  'sf_module_disabled_action' => 'disabled',
  'sf_unavailable_module' => 'default',
  'sf_unavailable_action' => 'unavailable',
  'sf_available' => true,
  'sf_use_database' => false,
  'sf_use_security' => false,
  'sf_use_flash' => false,
  'sf_i18n' => false,
  'sf_check_symfony_version' => false,
  'sf_use_process_cache' => true,
  'sf_compressed' => true,
  'sf_check_lock' => false,
  'sf_escaping_strategy' => 'bc',
  'sf_escaping_method' => 'ESC_ENTITIES',
  'sf_suffix' => '.',
  'sf_no_script_name' => true,
  'sf_validation_error_prefix' => ' &darr;&nbsp;',
  'sf_validation_error_suffix' => ' &nbsp;&darr;',
  'sf_validation_error_class' => 'form_error',
  'sf_validation_error_id_prefix' => 'error_for_',
  'sf_cache' => true,
  'sf_etag' => true,
  'sf_web_debug' => false,
  'sf_error_reporting' => 0,
  'sf_rich_text_js_dir' => 'js/tiny_mce',
  'sf_prototype_web_dir' => '/skyitech/sf/prototype',
  'sf_admin_web_dir' => '/sf/sf_admin',
  'sf_web_debug_web_dir' => '/skyitech/sf/sf_web_debug',
  'sf_calendar_web_dir' => '/sf/calendar',
  'sf_standard_helpers' => array (
  0 => 'Partial',
  1 => 'Cache',
  2 => 'Form',
),
  'sf_enabled_modules' => array (
  0 => 'default',
),
  'sf_charset' => 'utf-8',
  'sf_strip_comments' => true,
  'sf_autoloading_functions' => NULL,
  'sf_timeout' => 1800,
  'sf_max_forwards' => 5,
  'sf_path_info_array' => 'SERVER',
  'sf_path_info_key' => 'PATH_INFO',
  'sf_url_format' => 'PATH',
  'sf_orm' => 'propel',
));
  if (sfConfig::get('sf_logging_enabled', true))
  {
    // 'config/logging.yml' config file
// auto-generated by sfDefineEnvironmentConfigHandler
// date: 2017/11/12 15:17:06
sfConfig::add(array(
  'sf_logging_enabled' => false,
  'sf_logging_level' => 'debug',
  'sf_logging_rotate' => false,
  'sf_logging_period' => 7,
  'sf_logging_history' => 10,
  'sf_logging_purge' => true,
));
  }
  if ($file = $configCache->checkConfig($sf_app_config_dir_name.'/app.yml', true))
  {
    include($file);
  }
  if (sfConfig::get('sf_i18n'))
  {
    // 'config/i18n.yml' config file
// auto-generated by sfDefineEnvironmentConfigHandler
// date: 2017/11/12 15:17:06
sfConfig::add(array(
  'sf_i18n_default_culture' => 'en',
  'sf_i18n_source' => 'XLIFF',
  'sf_i18n_debug' => false,
  'sf_i18n_cache' => true,
  'sf_i18n_untranslated_prefix' => '[T]',
  'sf_i18n_untranslated_suffix' => '[/T]',
));
  }
    foreach ((array) sfConfig::get('sf_autoloading_functions', array()) as $callable)
  {
    sfCore::addAutoloadCallable($callable);
  }
    ini_set('display_errors', $sf_debug ? 'on' : 'off');
  error_reporting(sfConfig::get('sf_error_reporting'));
    if (!sfConfig::get('sf_in_bootstrap') && !$sf_debug && !sfConfig::get('sf_test'))
  {
    $configCache->checkConfig($sf_app_config_dir_name.'/bootstrap_compile.yml');
  }
      if (!$sf_debug && !sfConfig::get('sf_test'))
  {
    $core_classes = $sf_app_config_dir_name.'/core_compile.yml';
    $configCache->import($core_classes, false);
  }
  // 'config/php.yml' config file
// auto-generated by sfPhpConfigHandler
// date: 2017/11/12 15:17:06
ini_set('log_errors', '1');
ini_set('arg_separator.output', '&amp;');
if (ini_get('session.auto_start') != false)
{
  sfLogger::getInstance()->warning('{sfPhpConfigHandler} php.ini "session.auto_start" key is better set to "false" (current value is "\'\'" - php.ini location: "/opt/cpanel/ea-php56/root/etc/php.ini").');
}
  // 'config/routing.yml' config file
// auto-generated by sfRoutingConfigHandler
// date: 2017/11/12 15:17:06
$routes = sfRouting::getInstance();
$routes->setRoutes(
array (
  'categoryList' =>
  array (
    0 => '/categorylist/:parent/:fname/:sort/:page',
    1 => '#^/categorylist(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'parent',
      1 => 'fname',
      2 => 'sort',
      3 => 'page',
    ),
    3 =>
    array (
      'parent' => 1,
      'fname' => 1,
      'sort' => 1,
      'page' => 1,
    ),
    4 =>
    array (
      'module' => 'category',
      'action' => 'list',
      'page' => 1,
      'sort' => 'default',
      'fname' => 'music',
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'categorySearch' =>
  array (
    0 => '/findalbum/:type/:find/:sort/:page',
    1 => '#^/findalbum(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'type',
      1 => 'find',
      2 => 'sort',
      3 => 'page',
    ),
    3 =>
    array (
      'type' => 1,
      'find' => 1,
      'sort' => 1,
      'page' => 1,
    ),
    4 =>
    array (
      'module' => 'category',
      'action' => 'search',
      'page' => 1,
      'sort' => 'default',
      'type' => 'find',
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'filesList' =>
  array (
    0 => '/filelist/:parent/:fname/:sort/:page',
    1 => '#^/filelist(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'parent',
      1 => 'fname',
      2 => 'sort',
      3 => 'page',
    ),
    3 =>
    array (
      'parent' => 1,
      'fname' => 1,
      'sort' => 1,
      'page' => 1,
    ),
    4 =>
    array (
      'module' => 'files',
      'action' => 'list',
      'sort' => 'new2old',
      'fname' => 'music',
      'page' => 1,
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'filesShow' =>
  array (
    0 => '/download/:id/:name',
    1 => '#^/download(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'id',
      1 => 'name',
    ),
    3 =>
    array (
      'id' => 1,
      'name' => 1,
    ),
    4 =>
    array (
      'module' => 'files',
      'action' => 'show',
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'filesSingerList' =>
  array (
    0 => '/singer/:singer/:sort/:page',
    1 => '#^/singer(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'singer',
      1 => 'sort',
      2 => 'page',
    ),
    3 =>
    array (
      'singer' => 1,
      'sort' => 1,
      'page' => 1,
    ),
    4 =>
    array (
      'module' => 'files',
      'action' => 'singer',
      'sort' => 'new2old',
      'page' => 1,
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'artistList' =>
  array (
    0 => '/singerlist/:page',
    1 => '#^/singerlist(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'page',
    ),
    3 =>
    array (
      'page' => 1,
    ),
    4 =>
    array (
      'module' => 'artist',
      'action' => 'list',
      'page' => 1,
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'artistList2' =>
  array (
    0 => '/singerlist/:type/:find/:page',
    1 => '#^/singerlist(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'type',
      1 => 'find',
      2 => 'page',
    ),
    3 =>
    array (
      'type' => 1,
      'find' => 1,
      'page' => 1,
    ),
    4 =>
    array (
      'module' => 'artist',
      'action' => 'list',
      'page' => 1,
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'lastAddedFiles' =>
  array (
    0 => '/newitems/:page',
    1 => '#^/newitems(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'page',
    ),
    3 =>
    array (
      'page' => 1,
    ),
    4 =>
    array (
      'module' => 'files',
      'action' => 'lastadded',
      'page' => 1,
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'featured' =>
  array (
    0 => '/featured/:page',
    1 => '#^/featured(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'page',
    ),
    3 =>
    array (
      'page' => 1,
    ),
    4 =>
    array (
      'module' => 'files',
      'action' => 'featured',
      'page' => 1,
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'topFiles' =>
  array (
    0 => '/topdownload/:type',
    1 => '#^/topdownload(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'type',
    ),
    3 =>
    array (
      'type' => 1,
    ),
    4 =>
    array (
      'module' => 'files',
      'action' => 'top',
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'latestUpdates' =>
  array (
    0 => '/latest_updates/:page',
    1 => '#^/latest_updates(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'page',
    ),
    3 =>
    array (
      'page' => 1,
    ),
    4 =>
    array (
      'module' => 'info',
      'action' => 'latestupdates',
      'page' => 1,
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'homepage' =>
  array (
    0 => '/',
    1 => '/^[\\/]*$/',
    2 =>
    array (
    ),
    3 =>
    array (
    ),
    4 =>
    array (
      'module' => 'default',
      'action' => 'index',
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'default_symfony' =>
  array (
    0 => '/skyitech/:action/*',
    1 => '#^/skyitech(?:\\/([^\\/]+))?(?:\\/(.*))?$#',
    2 =>
    array (
      0 => 'action',
    ),
    3 =>
    array (
      'action' => 1,
    ),
    4 =>
    array (
      'module' => 'default',
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'default_index' =>
  array (
    0 => '/:module',
    1 => '#^(?:\\/([^\\/]+))?$#',
    2 =>
    array (
      0 => 'module',
    ),
    3 =>
    array (
      'module' => 1,
    ),
    4 =>
    array (
      'action' => 'index',
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
  'default' =>
  array (
    0 => '/:module/:action/*',
    1 => '#^(?:\\/([^\\/]+))?(?:\\/([^\\/]+))?(?:\\/(.*))?$#',
    2 =>
    array (
      0 => 'module',
      1 => 'action',
    ),
    3 =>
    array (
      'module' => 1,
      'action' => 1,
    ),
    4 =>
    array (
    ),
    5 =>
    array (
    ),
    6 => '',
  ),
)
);
    sfLoader::loadPluginConfig();
    ob_start(sfConfig::get('sf_compressed') ? 'ob_gzhandler' : '');
}
catch (sfException $e)
{
  $e->printStackTrace();
}
catch (Exception $e)
{
  if (sfConfig::get('sf_test'))
  {
    throw $e;
  }
  try
  {
        $sfException = new sfException();
    $sfException->printStackTrace($e);
  }
  catch (Exception $e)
  {
    header('HTTP/1.0 500 Internal Server Error');
  }
}

