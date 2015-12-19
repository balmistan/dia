<?php

class db {

    private $conn;
    private $dbname;
    private $hostname;
    private $user;
    private $password;
    private $port;
    private $debug;
    private $error;

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
            $this->error = $e->getMessage();
            if ($this->debug) {
                echo 'ERROR: ' . $this->error;
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

        while ($res = $sql->fetchColumn()) {
            array_push($nameslist, $res);
        }

        return $nameslist;
    }

    /**
     * This method before performing query, check if table name exists. This protects from mysql-Injection.
     * @param type $table_name
     */
    public function getColumnsName($table_name) {

        if (!in_array($table_name, $this->getTableNames())) {
            return array();
        }

        $columnslist = array();

        $sql = $this->conn->prepare("SHOW COLUMNS FROM " . $table_name);
        $sql->execute();

        while ($res = $sql->fetchColumn()) {
            array_push($columnslist, $res);
        }

        return $columnslist;
    }

    public function getError() {
        return $this->error;
    }

    public function insert($tablename, $arr_columnsname, $config_assoc, $csvarray) {

        $str_debug = "";
        $index_full_field_array = array();
        $str_values = " VALUES(";
        $query = "INSERT INTO " . $tablename . "(";

        for ($i = 0; $i < count($config_assoc); $i++) {
            if ($config_assoc[$i] != "") {
                array_push($index_full_field_array, $i);
                $str_values .= "?,";
                $query .= $arr_columnsname[$config_assoc[$i]] . ",";
            }
        }


        $str_values = substr_replace($str_values, ")", strrpos($str_values, ","));

        $query = substr_replace($query, ")", strrpos($query, ","));

        $str_debug .= "\$sql = \$this->conn->prepare(" . $query . $str_values . ");\n<br />";

        try {

            $this->conn->beginTransaction();

            $sql = $this->conn->prepare($query . $str_values);

            $row = 1; //stert from row 1. Row 0 are columns name in csv file
            while (isset($csvarray[$row])) {
                
                    for ($index = 0; $index < count($index_full_field_array); $index++) {

                        $str_debug .= "\$sql->bindValue(" . ($index + 1) . ", '" . $csvarray[$row][$index_full_field_array[$index]] . "');\n<br />";
                        $sql->bindValue(($index + 1), $csvarray[$row][$index_full_field_array[$index]]);
                    }
                    $str_debug .= "\$sql->execute();\n<br />";

                    $sql->execute();

                    $row++;
                }
            
            $this->conn->commit();
            $issue = "Success!";
        } catch (PDOException $er) {
            $issue = $er->getMessage();
            $this->conn->rollBack();
        }

        return $issue;
    }

}
