<?php

global $argv, $argc;
//$type = explode('=', $argv[1]);
Yii::trace('Start', 'cron');
echo 'Cron ';
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
$console_config = array(
  'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
  'aliases' => array(
    'webroot' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..',
  ),
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
    'mail' => array(
      'class' => 'ext.yii-mail.YiiMail',
      'transportType' => 'smtp',
      'viewPath' => 'application.views.mail',
    ),
    'urlManager' => array(
      'urlFormat' => 'path',
      'showScriptName' => FALSE,
      'rules' => array(
        '<controller:\w+>/<id:\d+>' => '<controller>',
      ),
    ),
    'log' => array(
      'class' => 'CLogRouter',
      'routes' => array(
        array(
          'class' => 'CFileLogRoute',
          'levels' => 'trace, error, warning, info',
        ),
      ),
    ),
  ),
  'modules' => array(
    'user',
  ),
);

include_once (dirname(__FILE__) . '/sites_connect.php');
include_once (dirname(__FILE__) . '/sites_config.php');
if (count($sites_connect) > 1 && $argc > 2 && strpos($argv[2], '--connectionID=') === 0) {
  $conn = explode('=', $argv[2]);
  $console_config['components']['db'] = $sites_connect[$conn[1]];
  $console_config['components']['mail'] = array_merge($console_config['components']['mail'], $sites_config[$conn[1]]['mail']);
  $console_config['components']['request'] = $sites_config[$conn[1]]['request'];
  $console_config['params'] = $sites_config[$conn[1]]['params'];
}
else {
  $console_config['components']['db'] = current($sites_connect);
  $conf = current($sites_config);
  $console_config['components']['mail'] = array_merge($console_config['components']['mail'], $conf['mail']);
  $console_config['components']['request'] = $conf['request'];
  $console_config['params'] = $conf['params'];
}

return $console_config;
