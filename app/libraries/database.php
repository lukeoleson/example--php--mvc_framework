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
    }
