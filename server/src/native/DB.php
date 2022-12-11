<?php

class NativeDb
{
    private $dsn;
    private $options;
    public $db;

    public function __construct($host, $port, $db, $user, $pass, $charset)
    {
        $this->dsn = "mysql:host=$host:$port;dbname=$db;charset=$charset";
        $this->options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->db = new PDO($this->dsn, $user, $pass, $this->options);
       } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
       }
    }
}

$nativeDb = new NativeDb($_ENV["DB_HOST"], $_ENV["DB_PORT"], $_ENV["SERVER_DB"],
                $_ENV["DB_USER"],$_ENV["DB_PASS"], $_ENV["DB_CHARSET"]);

?>