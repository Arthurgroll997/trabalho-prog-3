<?php

class NativeDb
{
    private $dsn;
    private $options;
    public $db;

    public function __construct($host, $db, $user, $pass, $charset)
    {
        $this->dsn = "mysql:host=$host;dbname=$db;charset=$charset";
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

?>