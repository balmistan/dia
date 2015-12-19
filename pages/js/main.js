$(document).ready(function () {
   
    var csv_file_name = ""; //name csv selected. Init on upload success.

    var arr_columns_table = Array(); //will to hold the names of columns in database table
    var arr_column_title = Array(); //will to hold the names of columns in csv file

    ////////////////EVENTS HANDLE//////////////////////////

    $("#select-mysql-table").change(function () {
        setColumnsNameDb(); //performs an Ajax request to get the names of the columns in the database. They will be saved in arr_columns_table.
        createTable();
        showhidebtn();  //show or hide button "Import"
    });
    $('#upload-btn').on('click', function () {
        var fd = new FormData(document.getElementById("upload-form"));
        var ret = sendToServer(fd, "csvupload.php");
        csv_file_name = ret["filename"]; //The file name is returned by server.
        updateAll();
    });

    $("#enclosure, #separator").change(function () {
        if (csv_file_name != "") {
            updateAll();
        }
    });
    $("#otherseparator").keyup(function () {
        if (csv_file_name != "" && ($("#otherseparator").val().trim() != "")) {
            updateAll();
        }
    });
    ////////////////////////////////////////////////////

    function update_csv_preview() {

        if (csv_file_name != "") {

            var datasend = {
                "filename": csv_file_name,
                "separator": $("#separator").val(),
                "otherseparator": $("#otherseparator").val(),
                "enclosure": $("#enclosure").val()
            };
            var arr = sendToServer(JSON.stringify(datasend), "csvhandle.php"); //return an array with csv data.
            if (arr === false)  //invalid json received from the server.
                return;
            arr_column_title = arr[0]; //setting global variable 

            html = "";
            if (arr.length > 0) {
                //create table header
                html += "<tr>\n";
                for (var i = 0; i < arr[0].length; i++) {
                    html += "<th>" + arr[0][i] + "</th>\n";
                }
                html += "</tr>\n";
                //create table body

                var row = 1; //in row 0 there are header

                while (arr[row] !== undefined) {
                    html += "<tr>\n";
                    for (var col = 0; col < arr[row].length; col++) {
                        html += "<td>" + arr[row][col] + "</td>\n";
                    }
                    html += "</tr>\n";
                    row++;
                }
            }

            $("#preview-table").html(html);
        }
    }// close function update_csv_preview()


    function setColumnsNameDb() {
        if ($("#select-mysql-table").val() !== "") {
            arr_columns_table = sendToServer(JSON.stringify({tbl_name: $("#select-mysql-table").val()}), "getcolumnsname.php");
        } else {
            arr_columns_table = Array();
        }
    }
    function createTable() {

        if ($("#select-mysql-table").val() != "" && csv_file_name != "") {  //no mysqltable selected or not csv uploaded  


            var select_html_db = getCodeForSelectboxDb();
            var html = "";
            for (var i = 0; i < arr_column_title.length; i++) {
                html += "<tr><td>" + arr_column_title[i] + "</td><td>---></td><td>" + select_html_db + "</td><tr>\n";
            }

            $("#configuration-table").html(html);
        } else {
            $("#configuration-table").html("");
        }
    }


    function getCodeForSelectboxDb() {
        var html = "<select class=\"db_assoc\">\n" +
                "<option value=\"\">&nbsp;</option>";
        for (var i = 0; i < arr_columns_table.length; i++) {
            html += "<option value=\"" + i + "\">" + arr_columns_table[i] + "</option>\n";
        }

        html += "</select>";
        return html;
    }




    function sendToServer(datatosend, linktosend) {
        var ret = Array();
        $.ajax({
            url: linktosend,
            type: 'POST',
            data: datatosend,
            async: false,
            success: function (data) {
                ret = JSON.parse(data);
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return ret;
    }

    /////////////////////////////////////

    function updateAll() {

        update_csv_preview();  //make/update/show preview of csv file.

        createTable();   //creates and displays tables on right side (if it has enough information)

        showhidebtn();   //show or hide button "Import"
    }



    //////////////////////////////////


    $("#import-btn").click(function () {
        sendAssocCsvDb()
    })




    /**
     * I do not need the names of the columns in the CSV, but only their position
     * This function,
     * returns an array where 
     * indexes are the position in csv file and  
     * values are the position of the columns in arr_columns_table. 
     * arr_columns_table has the list of names of the columns in database.
     * A copy of this variable is already in $_SESSION and so it is accessible with PHP 
     * @returns associative array
     */
    function sendAssocCsvDb() {
        var arr = [];
        $("#configuration-table").find("tr").each(function () {
            $(this).find("td:nth-child(3)").each(function () {
                arr.quadro = $(this).find("select").val();
                arr.push($(this).find("select").val());

            });

        });
        
          var issue = sendToServer(JSON.stringify(arr), "save_on_db.php");
          
          //$("#debug-div").html(issue);
          
          alert(issue)
           
    }
    
   ///////  Show/Hide Button Import (START CODE) ///////
   
   
   
   $(document).on('change', '.db_assoc', function() {
       showhidebtn();
   });
   
   
   function showhidebtn(){
        var show = false;
        
       $(".db_assoc").each (function(){
           if($(this).val()!=""){
               show = true;
               return 0;  //break
           }
       });
       
       if(show){
           $("#import-btn").css("visibility", "visible");
       }else{
           $("#import-btn").css("visibility", "hidden");
       }
   }
   
   ///////  Show/Hide Button Import  (END CODE)  ///////
   
   
   
});