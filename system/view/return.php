<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <div class="container">

    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group"> 
        <div id="success"></div>
        <form id="returnProducts" action="?page=returnProduct" method="post">
            <div class="col-sm-3 col-md-4 row">
            <label>Returner til:</label>
            <select name="toStorageID" form="returnProducts" id="returnRestrictionContainer" class="form-control">

                <!-- Her kommer Handlebars Template-->

            </select>
        </div>
        
        
                <br><br><br><br>
        
        <div id="returnProductContainer">
            

            <!-- Viser Product som er valgt i FRA lager -->


        </div>  
                
        
        <br><br><br>

        <div>
            <table class="table table-responsive" id="commentContainer">

                <tr>
                    <th class="col-md-1" id="bordernone">Kundenr:   </th> 
                    <td id="bordernone"><input class="form-control" name="customerNumber" required="required" form="returnProducts" type="number" value=""/></td> 
                </tr>  
                <tr>
                    <th class="col-md-1">kommentar:  </th>
                    <td><input class="form-control" name="returnComment" required="required" form="returnProducts" type="text" value=""/></td>
                </tr>

            </table>
        </div>   
        
        <div>
            <table class="table table-responsive" id="returnQuantityContainer">
            
                <!-- Lar deg velge antall enheter -->

            </table>

            
            <input form="returnProducts" type="hidden" id="date" name="date">
            
            <button form="returnProducts" type="submit" class="btn btn-success" id="returnButton">Overfør</button>
            <p id="errorMessage"></p>
        </div> 
        </form>
        
        
        
        
    </div>
</div>
</div>

<script id="returnQuantityTemplate" type="text/x-handlebars-template">
{{#each product}} 
    <tr class="selectQuantity">
        <th>Produkt:   </th>
        <td>{{productName}}</td>
        <input name="returnProductID[]" id="{{productID}}" form="returnProducts" type="hidden" value="{{productID}}"/>
        <th>Antall:</th>
        <td><input name="returnQuantity[]" form="returnProducts" required="required" type="number" min="1" max="1000" value="" autocomplete="off"/></td>  
        
        <td>
            <button id="redigerknapp" class="remove" data-toggle="tooltip">
                <span class="glyphicon glyphicon-remove" style="color: red"></span>
            </button>
        </td>    
        
    </tr>
{{/each}}  
</script>

<script id="returnProductTemplate" type="text/x-handlebars-template">
    <br>  
    {{#each storageProduct}} 
    <button data-id="{{productID}}" class="btn btn-primary product">{{productName}}</button>
    {{/each}} 
</script>

<!-- Display storages in drop down meny Template -->
<script id="returnRestrictionTemplate" type="text/x-handlebars-template">
<option data-id="0" value="0" class="returnStorage">Velg et lager</option>
{{#each transferRestriction}}    
<tr>
    <option data-id="{{storageID}}" value="{{storageID}}" class="returnStorage">{{storageName}}</option>
</tr>   
{{/each}}
        
</script>  


<!-- Get storage information with user restriction -->
<script>
    $('#returnButton').hide(); // hides transferbutton  
    $('#commentContainer').hide();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getTransferRestriction',
            dataType: 'json',
            success: function (data) {
                returnRestrictionTemplate(data);
            }
        });
    });
</script>


<!-- Display storages in drop down meny Template -->
<script>
    function returnRestrictionTemplate(data) {
        var rawTemplate = document.getElementById("returnRestrictionTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var transferRestrictionGeneratedHTML = compiledTemplate(data);

        var transferContainer = document.getElementById("returnRestrictionContainer");
        transferContainer.innerHTML = transferRestrictionGeneratedHTML;

    }
</script>


<!-- Get the selected storage, and POST this to retrive inventory-->
<script>
 
    $(function POSTfromStorageModal() {
        
        $('#returnRestrictionContainer').on('change', function () {
            givenStorageID = $(this).find("option:selected").data('id');
            $('#returnButton').hide();
            if (givenStorageID > 0) {
                $.ajax({
                    type: 'POST',
                    url: '?page=getStorageProduct',
                    data: {givenStorageID: givenStorageID},
                    dataType: 'json',
                    success: function (data) {
                        returnProductTemplate(data);
                        $('#returnButton').hide();
                        $('#commentContainer').hide();
                        $('.selectQuantity').remove();
                        
                
                    }
                });
            } else {
                $('.product').remove();
                $('.selectQuantity').remove();
                $('#commentContainer').hide();
                $('#returnButton').hide();
            }

            return false;

        });
    });
</script>

<!-- Display products in storage Template -->
<script>
    function returnProductTemplate(data) {
        var rawTemplate = document.getElementById("returnProductTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var transferProductGeneratedHTML = compiledTemplate(data);

        var productContainer = document.getElementById("returnProductContainer");
        productContainer.innerHTML = transferProductGeneratedHTML;
    }
</script>

<!-- Get productID from selected ID -->
<script>
                            
    $(function POSTselectedProduct() {

        $('#returnProductContainer').delegate('.product', 'click', function () {
        var givenProductID = $(this).attr('data-id');
              if( $('#'+givenProductID).length )   
            {
                return false;
            } else {


            $.ajax({
                type: 'POST',
                url: '?page=getProductByID',
                data: {givenProductID: givenProductID},
                dataType: 'json',
                success: function (data) {

                    returnQuantityTemplate(data);
                    $('#commentContainer').show();
                    $('#returnButton').show();
                }
            });
            return false;

            }
        });
    });
</script> 

<script>
    function returnQuantityTemplate(data) {
        var rawTemplate = document.getElementById("returnQuantityTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var transferProductGeneratedHTML = compiledTemplate(data);

        var transferContainer = document.getElementById("returnQuantityContainer");
        transferContainer.innerHTML += transferProductGeneratedHTML;
        
    }
</script>

<script>
    $(function POSTtransferProducts() {

        $('#returnProducts').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                error: function () {
                    var $displayUsers = $('#errorMessage');
                    $displayUsers.empty().append("Kunne ikke overføre");
                },
                success: function (data) {
                    $('.product').remove();
                    $('.selectQuantity').remove();
                    $('#errorMessage').remove();
                    successMessage();
                    updateReturn();
                }
            });
            return false;
        });
    });
</script>

<script>
function successMessage() {    
    $('<div class="alert alert-success"><strong>Registrert!</strong> Ditt uttak er registrert </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script> 

<script> 
function updateReturn() {                            
$('#returnButton').hide();// hides transferbutton 
$('#commentContainer').hide();
$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getTransferRestriction',
        dataType: 'json',
        success: function (data) {
            returnRestrictionTemplate(data);
            }  
        });
    });
}
</script>

<script>
Date.prototype.yyyymmdd = function() {
   var yyyy = this.getFullYear();
   var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
   var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
   return "".concat(yyyy).concat(mm).concat(dd);
  };

var d = new Date();
document.getElementById("date").value  = d.yyyymmdd();

</script>

<!-- remove product modal -->
<script>
    $(function POSTdeleteStorageModal() {

        $('#returnQuantityContainer').delegate('.remove', 'click', function () {
           var $tr = $(this).closest('tr');        
          
           $tr.fadeOut(150, function() {
            $(this).remove();
           });       
        });
    });
</script>  