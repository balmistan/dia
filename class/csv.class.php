<?php

class csv {
    
    private $Filename;
    private $Charset;
    private $Separator;
    private $Enclosure;
    private $Debug;
    
    private $ArrCSV;
    
    
    public function __construct($filename, $separator, $enclosure, $charset="UTF-8", $debug = false){
        $this->Charset = $charset;
        $this->Debug = $debug;
        $this->Filename = $filename;
        $this->Enclosure = $enclosure;
        $this->Separator = $separator;
        $this->ArrCSV = Array();
        
        $this->setArrCSV();
        
        $this->converter();
    }
    
    
    
    private function setArrCSV(){
        if ($fp = fopen($this->Filename, "r")) {

    while (!feof($fp)) {
        $this->ArrCSV[] = fgetcsv(
                $fp, 0, $this->Separator, $this->Enclosure
        );
    }

    fclose($fp);
}

    }
    
    
    
    private function converter() {
        if($this->Charset != "UTF-8"){
    array_walk_recursive($this->ArrCSV, function(&$item, $key) {

        $item = mb_convert_encoding($item, $this->Charset, 'UTF-8');
        
    });
        }
   
}
    
public function getArrCsv() {
    return $this->ArrCSV;
}
    
}


