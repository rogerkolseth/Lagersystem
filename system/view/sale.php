<?php require("view/header.php"); ?>


<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <div class="container">


    <!-- DIV som holder på all informasjon til venstre på skjermen  -->


    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group ">
        <div id="success"></div>
        <form id="withdrawProducts" action="?page=withdrawProduct" method="post">
        <div class="col-sm-3 col-md-4 row">
            <label>Uttak fra:</label>
            <select name="fromStorageID" form="withdrawProducts" id="withdrawrRestrictionContainer" class="form-control">

                <!-- Her kommer Handlebars Template-->

            </select>
        </div>


        <br><br><br><br>
        
        <div id="withdrawProductContainer">
            

            <!-- Viser Product som er valgt i FRA lager -->


        </div>        

        <br><br><br>

        <div>
            <table class="table table-responsive" id="commentContainer" hidden>

                <tr>
                    <th id="bordernone" class="col-md-1">Kundenr:   </th> 
                    <td id="bordernone"><input class="form-control" name="customerNumber" required="required" form="withdrawProducts" type="number" value=""/></td> 
                </tr>  
                <tr>
                    <th id="bordernone">Kommentar:  </th>
                    <td id="bordernone"><input class="form-control" name="withdrawComment" required="required" form="withdrawProducts" type="text" value=""/></td>
                </tr>

            </table>
        </div>

        <br>
        
        <div>
            <table class="table table-responsive" id="withdrawQuantityContainer">

                <!-- Lar deg velge antall enheter -->

            </table>

            
            <input form="withdrawProducts" type="hidden" id="date" name="date">
            
            <button form="withdrawProducts" type="submit" class="btn btn-success" id="withdrawButton" hidden>Overfør</button>
            <p id="errorMessage"></p>
        </div>
        </form>




    </div>  
</div>    
</div> 



<script id="withdrawQuantityTemplate" type="text/x-handlebars-template">
{{#each prodInfo}} 
    <tr class="selectQuantity">
        <th>Produkt:   </th>
        <td>{{productName}}</td>
        <input name="withdrawProductID[]" id="{{productID}}" form="withdrawProducts" type="hidden" value="{{productID}}"/>
        <th>Antall:</th>
        <td><input class="form-control" name="withdrawQuantity[]" form="withdrawProducts" required="required" type="number" min="1" max="{{quantity}}" value="" autocomplete="off"/></td> 
        <th>Tilgjengelig:</th>
        <td>{{quantity}} stk</td>    
         
        <td>
            <button id="redigerknapp" class="remove" data-toggle="tooltip">
                <span class="glyphicon glyphicon-remove" style="color: red"></span>
            </button>
        </td> 
    </tr>
{{/each}}
     
</script>

<!-- Display storages in drop down meny Template -->
<script id="withdrawRestrictionTemplate" type="text/x-handlebars-template">
<option data-id="0" value="0" class="withdrawStorage">Velg et lager</option>
{{#each transferRestriction}}    
<tr>
    <option data-id="{{storageID}}" value="{{storageID}}" class="withdrawStorage">{{storageName}}</option>
</tr>   
{{/each}}
        
</script>  


<script id="withdrawProductTemplate" type="text/x-handlebars-template">
    <br>  
    {{#each storageProduct}} 
    <button data-id="{{productID}}" class="btn btn-primary product">{{productName}}</button>
    {{/each}} 
</script>

<!-- Get storage information with user restriction -->
<script>
 
$('#withdrawButton').hide();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getTransferRestriction',
            dataType: 'json',
            success: function (data) {
                withdrawRestrictionTemplate(data);
            }
        });
    });
</script>


<!-- Display storages in drop down meny Template -->
<script>
    function withdrawRestrictionTemplate(data) {
        var rawTemplate = document.getElementById("withdrawRestrictionTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var transferRestrictionGeneratedHTML = compiledTemplate(data);

        var transferContainer = document.getElementById("withdrawrRestrictionContainer");
        transferContainer.innerHTML = transferRestrictionGeneratedHTML;

    }
</script>


<!-- Get the selected storage, and POST this to retrive inventory-->
<script>
    var givenStorageID;
    $(function POSTfromStorageModal() {
        
        $('#withdrawrRestrictionContainer').on('change', function () {
            givenStorageID = $(this).find("option:selected").data('id');

            if (givenStorageID > 0) {
                $.ajax({
                    type: 'POST',
                    url: '?page=getStorageProduct',
                    data: {givenStorageID: givenStorageID},
                    dataType: 'json',
                    success: function (data) {
                        withdrawProductTemplate(data);
                        $('.selectQuantity').remove();
                        $('#withdrawButton').hide();
                        $('#commentContainer').hide();
                    }
                });
            } else {
                $('.product').remove();
                $('.selectQuantity').remove();
                $('#commentContainer').hide();
                $('#withdrawButton').hide();
            }

            return false;

        });
    });
</script>

<!-- Display products in storage Template -->
<script>
    function withdrawProductTemplate(data) {
        var rawTemplate = document.getElementById("withdrawProductTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var transferProductGeneratedHTML = compiledTemplate(data);

        var transferContainer = document.getElementById("withdrawProductContainer");
        transferContainer.innerHTML = transferProductGeneratedHTML;
    }
</script>

<!-- Get productID from selected ID -->
<script>
                            
    $(function POSTselectedProduct() {

        $('#withdrawProductContainer').delegate('.product', 'click', function () {
        var givenProductID = $(this).attr('data-id');
              if( $('#'+givenProductID).length )   
            {
                return false;
            } else {


            $.ajax({
                type: 'POST',
                url: '?page=getProdQuantity',
                data: {givenProductID: givenProductID, givenStorageID: givenStorageID},
                dataType: 'json',
                success: function (data) {

                    withdrawQuantityTemplate(data);
                    $('#commentContainer').show();
                    $('#withdrawButton').show();
                }
            });
            return false;

        }
        });
    });
</script> 


<script>
    function withdrawQuantityTemplate(data) {
        var rawTemplate = document.getElementById("withdrawQuantityTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var transferProductGeneratedHTML = compiledTemplate(data);

        var transferContainer = document.getElementById("withdrawQuantityContainer");
        transferContainer.innerHTML += transferProductGeneratedHTML;
        
    }
</script>


<script>
    $(function POSTtransferProducts() {

        $('#withdrawProducts').submit(function () {
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
                    updateSale();
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
function updateSale() {                            
$('#withdrawButton').hide();// hides transferbutton 
$('#commentContainer').hide();
$(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getTransferRestriction',
        dataType: 'json',
        success: function (data) {
            withdrawRestrictionTemplate(data);
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

<script>
    $(function removeSelectedProductModal() {

        $('#withdrawQuantityContainer').delegate('.remove', 'click', function () {
           var $tr = $(this).closest('tr');
          
           $tr.fadeOut(150, function() {
            $(this).remove();
           });
           
        });
    });
</script>  

