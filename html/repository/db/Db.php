   <?php

class db{

    private $dsn = "mysql:host=localhost;dbname=2022test";
    private $dbUser = "root";
    private $dbPass = "";



    
protected function connect(){
    $pdo = new PDO($this->dsn,$this->dbUser,$this->dbPass);
    
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
    return $pdo;
}

}