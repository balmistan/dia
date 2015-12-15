<?php
error_reporting(E_ALL | E_STRICT); // all = 2147483647  

ini_set('display_error', 1);
ini_set('ignore_repeated_errors', 0);
ini_set('ignore_repeated_source', 0);

session_start();

require_once "../class/db.class.php";

$db = new db();



if (!$db->isLogged()) {
    header("Location: ../index.php");
    die();
}



$arr_table_names = $db->getTableNames();
?>

<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title>DIA</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/main.js"></script>
    </head>

    <body>
        
        <div id="left-div">


            <fieldset>
                <legend><?php echo $_SESSION["dbname"]; ?></legend>
                <select id="select-mysql-table">
                    <option value = "" >Wählen Sie die Tabelle</option>
                    <?php
                    foreach ($arr_table_names as $value) {
                        echo "<option value=\"" . $value . "\">" . $value . "</option>\n";
                    }
                    ?>
                </select> 
            </fieldset>

            <fieldset>
                <legend>Konfigurations csv</legend>
                <br />
                <label for="separator">Spaltentrenn:<br />
                
                <select name="separator" id="separator">
                    <option value=";">SEMIKOLON</option>
                    <option value=",">KOMMA</option>
                    <option value="TAB">TAB</option>
                    <option value="space">RAUM</option>
                    <option value=",">ANDERES</option>
                </select>
               
                    <input type="text" name="otherseparator" maxlength="1" value ="" /></label>
                <br/>
                <label for="enclosure">Kapselung Text:</label>
                <select name="enclosure" id="enclosure">
                    <option value='"'>Anführungszeichen</option>
                    <option value ="'">Apostroph</option>
                </select>

            </fieldset>

            <fieldset>
                <legend>Wählen Sie die CSV zu laden</legend>
                <form method="post" enctype="multipart/form-data" id="upload-form">

                    <input type="file" accept="text/csv" name="fileToUpload" />
                    <input id="upload-btn" type="button" value="hochladen" />
                </form>
            </fieldset>

        </div>

        <div id="right-div">

            <table border="1" id="configuration-table">

            </table>
        </div>

        <div id="central-div">

            <div id="div-preview-table">
                <table id="preview-table">

                </table>
            </div>

        </div>

       
        <!-- <button id="test-btn">Test</button>-->  
        <button id="logout-btn" onclick="window.location.href = 'logout.php'">Logout</button>
    
    </body>
    
</html>