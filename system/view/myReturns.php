<?php require("view/header.php"); ?>


<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

   <div class="container"> 

    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group"> 
        
        <form id="searchForReturns" class="form-inline" action="?page=getMyReturns" method="post">
            <div class="form-group col-md-12 row">
                <div class="">
                    <input class="form-control" form="searchForReturns" type="text" name="givenProductSearchWord" value="" placeholder="Søk etter returer.." autocomplete="off">  
                    <input class="form-control btn btn-primary" form="searchForReturns" type="submit" value="Søk">
                    
                    <button onclick="UpdateReturnsTable()" class="btn btn-primary " type="button">Alle Returer</button>
                </div>
                
            </div> 
        </form>
        
        <br><br><br><br>
        
        
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><b>Mine Returer</b></h3>
            </div>
         <table class="table table-responsive"> 
            
            <tbody id="myReturnsContainer">

                <!-- HER KOMMER INNHOLDET FRA HANDLEBARS  -->

            </tbody>
        </table> 
            </div>
        
        </div>
   </div>
</div>  


<div class="modal fade" id="editReturnsModal" role="dialog">
    <div class="modal-dialog">
        <!-- Innholdet til Modalen -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Returinformasjon</h4>
            </div>
            <form action="?page=editMyReturn" method="post" id="editReturn"> 
            
            <div class="modal-body">
                <table class="table" id="editReturnContainer">
                    

                <!-- Innhold fra Handlebars Template -->
                    
                </table>
            </div>
            
            <div class="modal-footer">
                <input class="btn btn-success" form="editReturn" type="submit" value="Lagre">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
            </div>
            </form>
        </div>
    </div>
</div>     
    

<!-- Display editSale-->                    
<script id="editReturnTemplate" type="text/x-handlebars-template">
    {{#each returns}}    
    <input form="editReturn" type="hidden" name="editReturnID" value="{{returnID}}">
    <tr>

    <th id="bordernone">Kundenr: </th> 
    <td id="bordernone"><input class="form-control" form="editReturn" required="required" type="number" name="editCustomerNr" value="{{customerNr}}" autocomplete="off"></td> 

    </tr>
    <tr>
    <th id="bordernone">Kommentar: </th> 
    <td id="bordernone"><input class="form-control" form="editReturn" required="required" type="text" name="editComment" value="{{comment}}" autocomplete="off"></td> 
    </tr>
    {{/each}}            
</script> 
    
   
<script id="myReturnsTemplate" type="text/x-handlebars-template">        
<tr>
    <th>KundeNr</th>        
    <th>Produkt</th>
    <th>Lager</th>     
    <th>Antall</th>
    <th>Kommentar</th>
    <th>Dato</th>   
    <th></th> 
</tr>        
{{#each myReturns}}  
<tr>
    <td>{{customerNr}}</td>        
    <td>{{productName}}</td>
    <td>{{storageName}}</td>
    <td>{{quantity}}</td>    
    <td>{{comment}}</td>    
    <td>{{date}}</td>  
    <td><button id="redigerknapp" data-id="{{returnID}}" class="editReturns" data-toggle="tooltip" title="Rediger retur">
    <span class="glyphicon glyphicon-edit" style="color: green"></span>
    </button> </td>    
<tr>
{{/each}}
</script>  


<script>        
    $(function () {
        
        $.ajax({
            type: 'GET',
            url: '?page=getMyReturns',
            dataType: 'json',
            success: function (data) {
                myReturnsTemplate(data);
            }
        });
    });     
    
</script>

<!-- Update return information -->
<script>
    function UpdateReturnsTable() {
        $(function () {
            $.ajax({
                type: 'GET',
                url: '?page=getMyReturns',
                dataType: 'json',
                success: function (data) {
                    myReturnsTemplate(data);
                }
            });
        });
    }
</script>

<script>
    function myReturnsTemplate(data) {
        var rawTemplate = document.getElementById("myReturnsTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var mySalesnGeneratedHTML = compiledTemplate(data);

        var myReturnsContainer = document.getElementById("myReturnsContainer");
        myReturnsContainer.innerHTML = mySalesnGeneratedHTML;

    }
</script>

<!-- SEARCH FOR RETURNS -->

<script>
    $(function POSTsearchForReturn() {

        $('#searchForReturns').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    $("#searchForReturns")[0].reset();
                    myReturnsTemplate(data);
                }
            });
            return false;
        });
    });

</script>

<script>
    $(function editMyReturns() {
        $('#myReturnsContainer').delegate('.editReturns', 'click', function () {
            
            var givenReturnsID = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: '?page=getReturnsFromID',
                data: {givenReturnsID: givenReturnsID},
                dataType: 'json',
                success: function (data) {
                    editReturnsTemplate(data);
                    $('#editReturnsModal').modal('show'); 
                }
            });
            return false;

        });
    });               
</script>  

<!-- Display edit sale Template -->
<script>
    function editReturnsTemplate(data) {
        var rawTemplate = document.getElementById("editReturnTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var editReturnGeneratedHTML = compiledTemplate(data);
        
        var returnContainer = document.getElementById("editReturnContainer");
        returnContainer.innerHTML = editReturnGeneratedHTML;
    }
</script>

<!-- POST results from editing, and updating the table-->
<script>
    $(function POSTeditReturnsInfo() {

        $('#editReturn').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function () {
                    $('#editReturnsModal').modal('hide');
                    UpdateReturnsTable();
                }
            });
            return false;
        });
    });

</script>
