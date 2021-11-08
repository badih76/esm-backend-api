<?php

    class Response {
        public $funcSuccess = "";
        public $funcResult = "";
        public $funcError = "";
        public $funcWarning = "";
        public $funcFailReason = "";
        public $funcDebug = "";
    }

    class PDODataClass {
        public $dbConn = null;
        private $host = "localhost";
        private $dbName = "fms";
        private $uName = "root";
        private $pWord = "Malmak-101";

        function __construct() {
            $dsn = "mysql:host=localhost;dbname=".$this->dbName.";port=3306;charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                 $this->dbConn = new PDO($dsn, $this->uName, $this->pWord, $options);
            } catch (\PDOException $e) {
                 throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
    }
?>