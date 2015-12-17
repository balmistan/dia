<?php

session_start();

require_once "../class/csv.class.php";

$charset = "Windows-1252";

$arr_in = json_decode(file_get_contents('php://input'), true);

$ret = array();

$separator = "";


switch (stripslashes($arr_in["separator"])) {
    case "TAB":
        $separator = "\t";
        break;
    case "space":
        $separator = " ";
        break;
    case "other":
        $separator = stripslashes($arr_in["otherseparator"]);
        break;
    default:
        $separator = stripslashes($arr_in["separator"]);
        break;
}


$csv = new csv("../uploads/" . $arr_in["filename"],   //csv file link
        $separator,
        stripslashes($arr_in["enclosure"]),
                "ISO-8859-1"
                );


/*
if ($fp = fopen("../uploads/" . $arr_in["filename"], "r")) {

    while (!feof($fp)) {
        $ret[] = fgetcsv(
                $fp, 0, $separator, stripslashes($arr_in["enclosure"])
        );
    }

    fclose($fp);
}
*/



$_SESSION["filename"] = $arr_in["filename"];
$_SESSION["separator"] = $separator;
$_SESSION["enclosure"] = stripslashes($arr_in["enclosure"]);



//echo json_encode(converter($ret));  

/*
function converter($array) {
    array_walk_recursive($array, function(&$item, $key) {

        $item = mb_convert_encoding($item, 'ISO-8859-1', 'UTF-8');
        
    });

    return $array;
}
*/

echo json_encode($csv->getArrCsv());