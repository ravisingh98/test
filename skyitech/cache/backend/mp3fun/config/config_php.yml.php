<?php
// auto-generated by sfPhpConfigHandler
// date: 2017/11/12 15:28:55
ini_set('log_errors', '1');
ini_set('arg_separator.output', '&amp;');
if (ini_get('session.auto_start') != false)
{
  sfLogger::getInstance()->warning('{sfPhpConfigHandler} php.ini "session.auto_start" key is better set to "false" (current value is "\'\'" - php.ini location: "/opt/cpanel/ea-php56/root/etc/php.ini").');
}

