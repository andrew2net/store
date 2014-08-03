<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
$console_config = array(
  'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
  'name' => 'Cron',
  // preloading 'log' component
  'preload' => array('log'),
  // application components
  'components' => array(
    'log' => array(
      'class' => 'CLogRouter',
      'routes' => array(
        array(
          'class' => 'CFileLogRoute',
          'logFile' => 'cron.log',
          'levels' => 'error, warning',
        ),
      ),
    ),
  ),
);

include_once (dirname(__FILE__) . '/sites_connect.php');
if (count($sites_connect) > 1)
  $console_config['components'] = array_merge($console_config['components'], $sites_connect);
else 
  $console_config['components']['db'] = current($sites_connect);

return $console_config;