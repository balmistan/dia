<?php

session_start();

require_once "../class/db.class.php";

$arr_in = json_decode(file_get_contents('php://input'), true);

    $db = new db();
    $ret_var = $db->getColumnsName($arr_in["tbl_name"]);


echo json_encode($ret_var);

