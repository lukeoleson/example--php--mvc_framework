<?php

    /*
    *   PDO Database Class
    *   connects to the database
    *   creates prepared statements
    *   binds values
    *   returns rows and results
    */
    class Database {
        // get the values from our config file
        private $host = DB_HOST;
        private $user = DB_USER;
        private $pass = DB_PASS;
        private $dbname = DB_NAME;

        // whenever we prepare a stmt we use this db handler
        private $dbh;
        // this is our statement
        private $stmt;
        // and for errors
        private $error;

        public function __construct(){
            // set DSN
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
            // all the option attributes can be seen in the pdo docs
            $options = array(
                // this is a persistent connection
                // checks for existing connectin to the db
                PDO::ATTR_PERSISTENT => true,
                // so we can throw exceptions
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            );

            // create pdo instance
            try{
                $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            } catch(PDOException $e){
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }

        // prepare statement with query
        public function query($sql){
            $this->stmt = $this->dbh->prepare($sql);
        }

        // bind values
        public function bind($param, $value, $type = null){
            if(is_null($type)){
                switch($value){
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;

                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;

                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                }
            }

            $this->stmt->bindValue($param, $value, $type);
        }

        // execute the prepared statement
        public function execute(){
            return $this->stmt->execute();
        }

        // get result set as array of objects
        public function resultSet(){
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }

        // get single record as object
        public function single(){
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }

        // get row count
        public function rowCount(){
            return $this->stmt->rowCount();
        }
    }
