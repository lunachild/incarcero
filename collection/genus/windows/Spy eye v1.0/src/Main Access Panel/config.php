<?
  # Database

  define('DB_SERVER',   'localhost');
  define('DB_NAME',     'botnet1');
  define('DB_USER',     'root');
  define('DB_PASSWORD', '');

  # Admin

  define('ADMIN_PASSWORD', 'admin');
  
  # Config
  define('CONFIG_FILE', 'bin/config.bin');
  
  # Setting timezone for php
  //putenv("TZ=US/Eastern"); //hmmm .... timezone_identifier
  // or ... "date.timezone = UTC" in php.ini
?>