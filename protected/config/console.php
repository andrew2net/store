<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
$console_config = array(
  'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
  'name' => 'Console Application',
  // preloading 'log' component
  'preload' => array('log'),
  // application components
  'components' => array(
    /*
      'db'=>array(
      'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
      ),
      // uncomment the following to use a MySQL database
      'db' => array(
      'connectionString' => 'mysql:host=localhost;dbname=demark',
      'emulatePrepare' => true,
      'username' => 'root',
      'password' => 'passdb',
      'charset' => 'utf8',
      'tablePrefix' => 'tbl_',
      ),
     */
    'log' => array(
      'class' => 'CLogRouter',
      'routes' => array(
        array(
          'class' => 'CFileLogRoute',
          'levels' => 'error, warning',
        ),
      ),
    ),
  ),
  'modules' => array(
    'admin',
    'auth' => array(
      'userNameColumn' => 'username',
      'defaultLayout' => 'application.modules.admin.views.layouts.main'
    ),
    'user' => array(
# encrypting method (php hash function)
      'hash' => 'md5',
      # send activation email
      'sendActivationMail' => true,
      # allow access for non-activated users
      'loginNotActiv' => false,
      # activate user on registration (only sendActivationMail = false)
      'activeAfterRegister' => true,
      # automatically login from registration
      'autoLogin' => true,
      # registration path
      'registrationUrl' => array('/user/registration'),
      # recovery password path
      'recoveryUrl' => array('/user/recovery'),
      # login form path
      'loginUrl' => array('/user/login'),
      # page after login
      'returnUrl' => array('/user/profile'),
      # page after logout
      'returnLogoutUrl' => array('/user/login'),
    ),
  )
);

include_once (dirname(__FILE__) . '/sites_connect.php');

$i = 0;
foreach ($sites_connect as $conn) {
  if ($i == 0)
    $db = 'db';
  else
    $db = 'db' . $i;
  $console_config['components'][$db] = $conn;
  $i++;
}

return $console_config;