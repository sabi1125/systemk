<?php
if(false === defined('BASEPATH')) {
    define('BASEPATH', realpath(__DIR__ . '/..'));
}

require_once(BASEPATH. '/libs/Config.php');

class db{
protected function connect(){
    $db_config = Config::get('db');
    $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}";
    $dbUser = "root";
    $dbPass = "";

    $options = [
        \PDO::ATTR_EMULATE_PREPARES => false,
        \PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    ];
    $pdo = new PDO($dsn,$dbUser,$this->dbPass,$options);
    
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
    return $pdo;
}

}