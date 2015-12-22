<?php

ini_set('auto_detect_line_endings', true);

class csv {

    private $Filename;
    private $Charset;
    private $Separator;
    private $Enclosure;
    private $Debug;
    private $ArrCSV;

    public function __construct($filename, $separator, $enclosure, $charset = "UTF-8", $debug = false) {
        $this->Charset = $charset;
        $this->Debug = $debug;
        $this->Filename = $filename;
        $this->Enclosure = $enclosure;
        $this->Separator = $separator;
        $this->ArrCSV = Array();

        $this->setArrCSV();

        $this->converter();
    }

    private function setArrCSV() {
        if ($fp = fopen($this->Filename, "r")) {

            $arr_row = array();

            while (!feof($fp)) {

                $arr_row = fgetcsv(
                        $fp, 0, $this->Separator, $this->Enclosure
                );
                if (array_filter($arr_row)) {      //ignores empty rows
                    $this->ArrCSV[] = $arr_row;
                }
            }

            fclose($fp);
        }
    }

    private function converter() {
        if ($this->Charset != "UTF-8") {
            array_walk_recursive($this->ArrCSV, function(&$item) {
                //$item = mb_convert_encoding($item, "ISO-8859-1", 'UTF-8');
                $item = iconv($this->Charset, 'UTF-8', $item);
            });
        }
    }

    public function getArrCsv() {
        return $this->ArrCSV;
    }

    private function remove_empty_row() {
        foreach ($this->ArrCSV as $key => $arr_columns) {
            
        }
    }

}
