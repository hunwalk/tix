<?php

$login = $_ENV['DB_LOGIN'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_DATABASE'];
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$charset = $_ENV['DB_CHARSET'];
$driver = $_ENV['DB_DRIVER'];
$table_prefix = $_ENV['DB_TABLE_PREFIX'];

$dsn =
    $driver.":".
    "host=".$host.";".
    "port=".$port.";".
    "dbname=".$database.";".
    "";

$config = [
    'class' => 'yii\db\Connection',
    'dsn' => $dsn,
    'username' => $login,
    'password' => $password,
    'charset' => $charset,

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

if ($table_prefix){
    $config['tablePrefix'] = $table_prefix;
}

return $config;
