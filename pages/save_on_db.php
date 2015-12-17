<?php

session_start();

require_once "../class/db.class.php";
require_once "../class/csv.class.php";

$arr_in = json_decode(file_get_contents('php://input'), true);


$csv = new csv("../uploads/" . $_SESSION["filename"],   //csv file link
        $_SESSION["separator"],
        $_SESSION["enclosure"],
                "ISO-8859-1"
                );

$arr_csv = $csv->getArrCsv();


$db = new db();

$issue = $db->insert($_SESSION["tablename"], $_SESSION["dbcolumnslist"], $arr_in, $arr_csv);


    
    echo json_encode($issue);



function debug($string) {

    ob_start();
    $var = func_get_args();
    call_user_func_array('var_dump', $var);
    $string = ob_get_clean();

    if ($fp = fopen("../debug.txt", "w")) {
        fwrite($fp, $string . "\n");
        fclose($fp);
    }
}

