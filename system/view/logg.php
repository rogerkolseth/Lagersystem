<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <div class="container">
        
        <div class="col-sm-3 col-sm-offset-1 col-md-11 col-md-offset-1 form-group">
        
        <form id="searchForLog" class="form-inline" action="?page=getAllLoggInfo" method="post">
            <div class="form-group">
                <div class="col-md-12">
                    <input class="form-control" form="searchForLog" type="text" name="givenLogSearchWord" value="" placeholder="Søk etter hendelse.." autocomplete="off">  
                    <input class="form-control btn btn-primary" form="searchForLog" type="submit" value="Søk">
                    
                    <button onclick="updateLogTable()" class="btn btn-primary " type="button">All Logg</button>
                </div>
                
            </div> 
            <a href="#" id="loggToCSV" class="btn btn-success">Eksporter til csv</a>
        </form>
        
        <br><br>
        
        </div>
        <table class="table fontSizeTableContainer" id="loggTableContainer">
                <!-- Innhold fra Handlebars Template -->
        </table>

    </div>
    
</div>   


<script id="loggTableTemplate" type="text/x-handlebars-template">
<thead>
    <tr>
        <th>Type</th>
        <th>Beskrivelse</th>
        <th>Lagernavn</th>
        <th>Til lager</th>
        <th>Fra lager</th>    
        <th>Antall</th>
        <th>Gammelt Antall</th>
        <th>Nytt Antall</th>
        <th>Differanse</th>
        <th>Brukernavn</th>
        <th>På bruker</th>
        <th>Produkt</th>    
        <th>KundeNr</th>
        <th>Dato</th>            
        </tr>
</thead>
<tbody id="tbodyid">
{{#each allLoggInfo}}
    <tr>
        <td>{{type}}</td>
        <td>{{desc}}</td>
        <td>{{storageName}}</td>
        <td>{{toStorage}}</td>
        <td>{{fromStorage}}</td>
        <td>{{quantity}}</td>
        <td>{{oldQuantity}}</td>
        <td>{{newQuantity}}</td>
        <td>{{differential}}</td>  
        <td>{{username}}</td>
        <td>{{onUsername}}</td>
        <td>{{productName}}</td>
        <td>{{customerNr}}</td>
        <td>{{date}}</td>
    </tr>
</tbody>
{{/each}}
            
  
</script>

<script>
 $(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getAllLoggInfo',
        dataType: 'json',
        success: function (data) {
            displayLoggTable(data);
        }
    });
});   
</script>    

<script>
function updateLogTable() {
    $.ajax({
        type: 'GET',
        url: '?page=getAllLoggInfo',
        dataType: 'json',
        success: function (data) {
            displayLoggTable(data);
        }
    });
}  
</script>   

<script>
    function displayLoggTable(data) {
        var rawTemplate = document.getElementById("loggTableTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var loggTableGeneratedHTML = compiledTemplate(data);

        var loggContainer = document.getElementById("loggTableContainer");
        loggContainer.innerHTML = loggTableGeneratedHTML;
    }
</script>

<!-- SEARCH FOR LOGG -->

<script>
    $(function POSTsearchForSale() {

        $('#searchForLog').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    $("#searchForLog")[0].reset();
                    displayLoggTable(data);
                }
            });
            return false;
        });
    });

</script>


<script>
$(document).ready(function () {

	function exportTableToCSV($table, filename) {
    
        var $rows = $table.find('tr:has(td),tr:has(th)'),
    
            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character
    
            // actual delimiter characters for CSV format
            colDelim = '","',
            rowDelim = '"\r\n"',
    
            // Grab text from table into CSV formatted string
            csv = '"' + $rows.map(function (i, row) {
                var $row = $(row), $cols = $row.find('td,th');
    
                return $cols.map(function (j, col) {
                    var $col = $(col), text = $col.text();
    
                    return text.replace(/"/g, '""'); // escape double quotes
    
                }).get().join(tmpColDelim);
    
            }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '"',
    
            
    
            // Data URI
            csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
            
            console.log(csv);
            
        	if (window.navigator.msSaveBlob) { // IE 10+
        		//alert('IE' + csv);
        		window.navigator.msSaveOrOpenBlob(new Blob([csv], {type: "text/plain;charset=utf-8;"}), "csvname.csv");
        	} 
        	else {
        		$(this).attr({ 'download': filename, 'href': csvData, 'target': '_blank' }); 
        	}
    }
    
    // This must be a hyperlink
    $("#loggToCSV").on('click', function (event) {
    	
        exportTableToCSV.apply(this, [$('#loggTableContainer'), 'LagersystemLogg.csv']);
        
        // IF CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });

});
</script>