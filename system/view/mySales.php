<?php require("view/header.php");?>


<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

<div class="container">
    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group">
        
        <form id="searchForSale" class="form-inline" action="?page=getMySales" method="post">
            <div class="form-group col-md-12 row">
                <div class="">
                    <input class="form-control" form="searchForSale" type="text" name="givenProductSearchWord" value="" placeholder="Søk etter salg.." autocomplete="off">  
                    <input class="form-control btn btn-primary" form="searchForSale" type="submit" value="Søk">
                    
                    <button onclick="UpdateSalesTable()" class="btn btn-primary " type="button">Alle Salg</button>
                </div>
                
            </div> 
        </form>
        
        <br><br><br>
        
    
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><b>Mine Salg</b></h3> 
            </div>
         <table class="table table-responsive"> 
            
            <tbody id="mySalesContainer">

                <!-- HER KOMMER INNHOLDET FRA HANDLEBARS  -->

            </tbody>
        </table> 
        </div>
    </div>    
</div>    
    
    
<div class="modal fade" id="editSaleModal" role="dialog">
    <div class="modal-dialog">
        <!-- Innholdet til Modalen -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Salgsinformasjon</h4>
            </div>
            <form action="?page=editMySale" method="post" id="editSale"> 
            
            <div class="modal-body">
                <table class="table" id="editSaleContainer">
                    

                <!-- Innhold fra Handlebars Template -->
                    
                </table>
            </div>
            
            <div class="modal-footer">
                <input class="btn btn-success" form="editSale" type="submit" value="Lagre">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
            </div>
            </form>
        </div>
    </div>
</div>     
    
    
<!-- Display editSale-->                    
<script id="editSaleTemplate" type="text/x-handlebars-template">
    {{#each sale}}    
    <input form="editSale" type="hidden" name="editSaleID" value="{{salesID}}">
    <tr>

    <th id="bordernone">Kundenr: </th> 
    <td id="bordernone"><input class="form-control" form="editSale" required="required" type="number" name="editCustomerNr" value="{{customerNr}}" autocomplete="off"></td> 

    </tr>
    <tr>
    <th id="bordernone">Kommentar: </th> 
    <td id="bordernone"><input class="form-control" form="editSale" required="required" type="text" name="editComment" value="{{comment}}" autocomplete="off"></td> 
    </tr>
    {{/each}}            
</script> 

<script id="mySalesTemplate" type="text/x-handlebars-template">        
<tr>
    <th>KundeNr</th>        
    <th>Produkt</th>
    <th>Lager</th>     
    <th>Antall</th>
    <th>Kommentar</th>
    <th>Dato</th> 
    <th></th>    
</tr>        
{{#each mySales}}  
<tr>
    <td>{{customerNr}}</td>        
    <td>{{productName}}</td>
    <td>{{storageName}}</td>
    <td>{{quantity}}</td>    
    <td>{{comment}}</td>    
    <td>{{date}}</td>   
    <td><button id="redigerknapp" data-id="{{salesID}}" class="editSales" data-toggle="tooltip" title="Rediger salg">
    <span class="glyphicon glyphicon-edit" style="color: green"></span>
    </button> </td>    
<tr>
{{/each}}
</script>  

<script>        
    $(function () {
        
        $.ajax({
            type: 'GET',
            url: '?page=getMySales',
            dataType: 'json',
            success: function (data) {
                mySalesTemplate(data);
            }
        });
    });     
    
</script>

<!-- Update sales information -->
<script>
    function UpdateSalesTable() {
        $(function () {
            $.ajax({
                type: 'GET',
                url: '?page=getMySales',
                dataType: 'json',
                success: function (data) {
                    mySalesTemplate(data);
                }
            });
        });
    }
</script>



<script>
    function mySalesTemplate(data) {
        var rawTemplate = document.getElementById("mySalesTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var mySalesnGeneratedHTML = compiledTemplate(data);

        var mySalesContainer = document.getElementById("mySalesContainer");
        mySalesContainer.innerHTML = mySalesnGeneratedHTML;

    }
</script>


<!-- SEARCH FOR SALES -->

<script>
    $(function POSTsearchForSale() {

        $('#searchForSale').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    $("#searchForSale")[0].reset();
                    mySalesTemplate(data);
                }
            });
            return false;
        });
    });

</script>

<script>
    $(function editMySales() {
        $('#mySalesContainer').delegate('.editSales', 'click', function () {

            var givenSalesID = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: '?page=getSalesFromID',
                data: {givenSalesID: givenSalesID},
                dataType: 'json',
                success: function (data) {
                    editSaleTemplate(data);
                   
                   $('#editSaleModal').modal('show'); 

                }
            });
            return false;

        });
    });               
</script>  

<!-- Display edit sale Template -->
<script>
    function editSaleTemplate(data) {
        var rawTemplate = document.getElementById("editSaleTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var editSaleGeneratedHTML = compiledTemplate(data);
        
        var saleContainer = document.getElementById("editSaleContainer");
        saleContainer.innerHTML = editSaleGeneratedHTML;
    }
</script>

<!-- POST results from editing, and updating the table-->
<script>
    $(function POSTeditSaleInfo() {

        $('#editSale').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function () {
                    $('#editSaleModal').modal('hide');
                    UpdateSalesTable();
                }
            });
            return false;
        });
    });

</script>
