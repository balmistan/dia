<?php

class db {

    private $conn;
    private $dbname;
    private $hostname;
    private $user;
    private $password;
    private $port;
    private $debug;

    public function __construct($debug = false) {
        $this->conn = NULL;
        $this->dbname = (isset($_SESSION["dbname"]) ? $_SESSION["dbname"] : "");
        $this->hostname = (isset($_SESSION["hostname"]) ? $_SESSION["hostname"] : "");
        $this->user = (isset($_SESSION["user"]) ? $_SESSION["user"] : "");
        $this->password = (isset($_SESSION["password"]) ? $_SESSION["password"] : "");
        $this->port = (isset($_SESSION["port"]) ? $_SESSION["port"] : "");

        $this->debug = $debug;


        $this->dbConnect();
    }

    private function dbConnect() {
        /* Connect to an ODBC database using driver invocation */
        $dsn = 'mysql:dbname=' . $this->dbname . ';host=' . $this->hostname . ';port=' . $this->port . ';charset=UTF8';

        try {
            $this->conn = new PDO($dsn, $this->user, $this->password, array(
                PDO::ATTR_PERSISTENT => false
            ));

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->conn = NULL;
            if ($this->debug) {
                echo 'ERROR: ' . $e->getMessage();
            }
        }
    }

    public function isLogged() {

        if ($this->conn == NULL) {
            return false;
        }
        return true;
    }

    public function getTableNames() {
        
        $nameslist = array();
        
        $sql = $this->conn->prepare("SHOW TABLES");
        $sql->execute(); 
       
        while($res=$sql->fetchColumn()){
            array_push($nameslist, $res);
        }
       
        return $nameslist;
    }
    
    /**
     * This method before performing query, check if table name exists. This protects from mysql-Injection.
     * @param type $table_name
     */
    
    public function getColumnsName($table_name){
        
        if(!in_array($table_name, $this->getTableNames())){
            return array();
        }
        
        $columnslist = array();
       
        $sql = $this->conn->prepare("SHOW COLUMNS FROM " .$table_name);
          $sql->execute();   
         
        while($res=$sql->fetchColumn()){
            array_push($columnslist, $res);
        }
     
        return $columnslist;
    }
    
    
    
    

}
