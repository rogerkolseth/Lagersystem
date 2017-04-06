
<?php require("view/header.php");?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <div class="container">
    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group">

        
        <!-- SØK ETTER PRODUKT-->
        
        <form id="searchForProduct" class="form-inline" action="?page=getAllProductInfo" method="post">
            <div class="form-group col-md-12 row">
                
                    <input class="form-control" form="searchForProduct" type="text" name="givenProductSearchWord" value="" placeholder="Søk etter produkt..">  
                    <input class="form-control btn btn-primary" form="searchForProduct" type="submit" value="Søk">
                    
                    <button onclick="UpdateProductTable()" class="btn btn-primary " type="button">Alle producter</button>
                
                <div class="pull-right row">
                    <button class="btn btn-success" onclick="createProductInfo();" type="button" data-toggle="modal" data-target="#createProductModal">Opprett Produkt</button>
                </div>
            </div> 
        </form>


        
        <br><br>
        <div id="success"></div>
         
        <!-- DISPLAY PRODUCT CONTAINER -->
        <br><br>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="panel-title text-center"><b>Produktoversikt</b></h2>
            </div>
        <table class="table table-responsive"> 
             
            <tbody id="displayProductContainer">

                <!-- HER KOMMER INNHOLDET FRA HANDLEBARS  -->

            </tbody>
        </table>   
        </div>
    </div>
</div>






    <!-- CREATE PRODUCT MODAL -->


    <div class="modal fade" id="createProductModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Opprett Produkt</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <table class="table">
                        <form action="?page=addProductEngine" method="post" id="createProduct">
                            <tr>
                                <th id="bordernone">Produktnavn:</th>
                                <td id="bordernone"><input class="form-control" type="text" required="required" name="givenProductName" value="" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <th>Pris:</th>
                                <td><input class="form-control" type="number" required="required" name="givenPrice" value="" placeholder="Kr" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <th>Kategori:</th>
                                <td>
                                    <select name="givenCategoryID" id="selectCategoryID" required="required" class="form-control" autocomplete="off">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Media:</th>
                                <td>
                                    <select name="givenMediaID" id="selectMediaID" required="required" class="form-control" autocomplete="off">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>MacAdresse:</th>
                                <td><input type="checkbox" id="TRUE" name="givenMacAdresse" value="TRUE"></td>
                            </tr>
                            
                            <input form="createProduct" type="hidden" id="date" name="date">
                        
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="error"></div>
                    <input class="btn btn-success" form="createProduct" type="submit" value="Opprett Produkt">
                    
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Avslutt</button>

                </div>
                </form>
            </div>
        </div>
    </div>



<!-- GET EDIT PRODUCT MODAL-->

<div class="modal fade" id="editProductModal" role="dialog">
    <div class="modal-dialog">
        <!-- Innholdet til Modalen -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Produkt informasjon</h4>
            </div>
            
            <form action="?page=editProductEngine" method="post" id="editProduct">
            <div class="modal-body" >
                <table class="table">
                    <tbody id="editProductContainer">
                <!-- Innhold fra Handlebars Template-->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <div id="errorEdit"></div>
                <input class="btn btn-success" type="submit" value="Lagre" form="editProduct">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
            </div>
            </form>
        </div>
    </div>
</div> 




<!-- GET PRODUCT INFORMATION MODAL -->

<div class="modal fade" id="showProductInformationModal" role="dialog">
    <div class="modal-dialog">
        <!-- Innholdet til Modalen -->
        <div class="modal-content row">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Product informasjon</h4>
            </div>
            <div class="modal-body">
                
                <div id="productInformationContainer">
                    <!-- Her kommer handlebars Template -->
                
                </div>
                
                <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lager med dette produktet</th>
                            <th>Antall</th>
                        </tr>
                    </thead>
                    
                    <tbody id="productLocationContainer">
                         
                            
                             
                              
                                <!-- Her kommer handlebars Template -->
                                
                            
                                
                    </tbody>
                </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
            </div>
        </div>
    </div>
</div> 




<!-- DELETE PRODUCT MODAL-->

<div class="modal fade" id="deleteProductModal" role="dialog">
    <div class="modal-dialog">
        <!-- Innholdet til Modalen -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Product informasjon</h4>
            </div>
            <form action="?page=deleteProductEngine" method="post" id="deleteProduct">
            <div class="modal-body" id="deleteProductContainer">
                
                <!-- Innhold fra Handlebars Template -->

            </div>
            <div class="modal-footer">
                <input class="btn btn-success" type="submit" value="Slett" form="deleteProduct">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
            </div>
            </form>
        </div>
    </div>
</div>

</div>


<!-- TEMPLATES-->

<script id="editProductTemplate" type="text/x-handlebars-template">
{{#each product}}    
    <input form="editProduct" type="hidden" name="editProductID" value="{{productID}}">
    <tr>
    <th id="bordernone">Produktnavn:</th>
    <td id="bordernone"><input class="form-control" form="editProduct" type="text" required="required" name="editProductName" value="{{productName}}" autocomplete="off"></td>
    </tr>
    <tr>
    <th>Pris: </th>
    <td><input class="form-control" form="editProduct" type="int" required="required" name="editPrice" value="{{price}}" autocomplete="off"></td>
    </tr>
    <tr>
    <th>Kategori:</th>
    <td>
        <select form="editProduct" type="text" required="required" name="editCategoryID" class="form-control" autocomplete="off">
        <option value="{{categoryID}}">{{categoryName}}</option>
    {{/each}}    
        {{#each category}}            
        <option value="{{categoryID}}">{{categoryName}}</option>
        {{/each}}
    </select>
    </td>
    </tr>
    <tr>
    <th>Media: </th>
    <td>
    <select form="editProduct" type="text" required="required" name="editMediaID" class="form-control" autocomplete="off">
        
        <option value="{{product.0.mediaID}}">{{product.0.mediaName}}</option>
      
        {{#each media}}            
        <option value="{{mediaID}}">{{mediaName}}</option>
        {{/each}}
    </select>
    </td>
    </tr>  

</script>  

<!-- Display productInformation-->
<script id="productInformationTemplate" type="text/x-handlebars-template">
{{#each product}}
<div class="col-md-6">
    <table class="table">
        <tr>
            <th id="bordernone">ProduktID: </th>
            <td id="bordernone">{{productID}}</td>
        </tr>
        <tr>
            <th>Produktnavn: </th>
            <td>{{productName}}</td>
        </tr>
        <tr>
            <th>Pris: </th>
            <td>{{price}}</td>
        </tr>
        <tr>
            <th>Kategori: </th>
            <td>{{categoryName}}</td>
        </tr>
        <tr>
            <th>Opprettet: </th>
            <td>{{date}}</td>
        </tr>
        
        
    </table>
</div>

<div class="col-md-6">
   <td><img class="img-responsive" src="image/{{mediaName}}" alt="{{mediaName}}"></td>
</div>
{{/each}}                                                  
</script>

<!-- Display location of product, and quantity-->
<script id="productLocationTemplate" type="text/x-handlebars-template">
{{#each productLocation}}
<tr>
    <td>{{storageName}}</td>
    <td>{{quantity}}</td>
</tr>
{{/each}}      
</script>

<!-- Display what product you are deleting-->
<script id="deleteProductTemplate" type="text/x-handlebars-template">
    <p> Er du sikker på at du vil slette:  <P>
{{#each product}}
    {{productName}}
    <input type="hidden" form="deleteProduct" name="deleteProductID" value="{{productID}}">    
{{/each}} 
</script>  

<!-- display all product template -->
<script id="displayProductTemplate" type="text/x-handlebars-template">

    {{#each productInfo}} 
    <tr> 
    <td class="text-center col-md-2">  


    <!-- Knapp som aktiverer Model for produktredigering  --> 

    <button id="redigerknapp" data-id="{{productID}}" class="edit" data-toggle="tooltip" title="Rediger produkt">
    <span class="glyphicon glyphicon-edit" style="color: green"></span>
    </button>


    <!-- Knapp som aktiverer Model for å vise productinformasjon  --> 

    <button id="redigerknapp" data-id="{{productID}}" class="information" data-toggle="tooltip" title="Vis informasjon">
    <span class="glyphicon glyphicon-menu-hamburger" style="color: #003366" ></span>
    </button>


    <!-- Knapp som aktiverer Model for sletting av produkt  --> 



    <button id="redigerknapp" data-id="{{productID}}" class="delete" data-toggle="tooltip" title="Slett produkt" >
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>

    </td>

    <!-- Printer ut productnavn og katerogi inn i tabellen -->

    <th>Produktnavn: </th>
    <td>{{productName}}</td>
    <th>Kategori: </th>
    <td>{{categoryName}}</td>    

    {{/each}}
    </tr>


</script>


<!-- DISPLAY PRODUCT MAIN TABLE -->

<!-- GET productInformation -->
<script>
    $('#dropdown').show();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllProductInfo',
            dataType: 'json',
            success: function (data) {
                productTableTemplate(data);
            }
        });
    });
</script>

<!-- Update product information -->
<script>
    function UpdateProductTable() {
        $(function () {
            $.ajax({
                type: 'GET',
                url: '?page=getAllProductInfo',
                dataType: 'json',
                success: function (data) {
                    productTableTemplate(data);
                }
            });
        });
    }
</script>

<!-- Display storage template -->
<script>
    function productTableTemplate(data) {

        var rawTemplate = document.getElementById("displayProductTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var productTableGeneratedHTML = compiledTemplate(data);

        var productContainer = document.getElementById("displayProductContainer");
        productContainer.innerHTML = productTableGeneratedHTML;
    }
</script>



<!-- DELETE PRODUCT -->

<!-- Delete product modal -->
<script>
    $(function POSTdeleteProductModal() {

        $('#displayProductContainer').delegate('.delete', 'click', function () {
            var givenProductID = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: '?page=getProductByID',
                data: {givenProductID: givenProductID},
                dataType: 'json',
                success: function (data) {
                    deleteProductTemplate(data);
                    $('#deleteProductModal').modal('show');
                }
            });
            return false;

        });
    });
</script>  

<!-- Delete product template-->         
<script>
    function deleteProductTemplate(data) {
        var rawTemplate = document.getElementById("deleteProductTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var deleteProductGeneratedHTML = compiledTemplate(data);

        var productContainer = document.getElementById("deleteProductContainer");
        productContainer.innerHTML = deleteProductGeneratedHTML;
    }
</script>

<!-- Delete the product that is selected-->
<script>
    $(function deleteProductByID() {

        $('#deleteProduct').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    successMessageDelete();
                    UpdateProductTable();
                    $('#deleteProductModal').modal('hide');

                }
            });
            return false;
        });
    });

</script>
<script>
function successMessageDelete() {    
    $('<div class="alert alert-success"><strong>Slettet!</strong> Produkt er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script> 



<!-- SHOW PRODUCT INFORMATION -->

<!-- get information from selected product-->
<script>
    $(function POSTproductInformationModal() {

        $('#displayProductContainer').delegate('.information', 'click', function () {
            var givenProductID = $(this).attr('data-id');
            POSTproductLocation(givenProductID);
            $.ajax({
                type: 'POST',
                url: '?page=getProductByID',
                data: {givenProductID: givenProductID},
                dataType: 'json',
                success: function (data) {
                    $('#showProductInformationModal').modal('show');
                    productInformationTemplate(data);

                }
            });
            return false;

        });
    });
</script>

<!-- Display storageInformation Template-->
<script>
    function productInformationTemplate(data) {
        var rawTemplate = document.getElementById("productInformationTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var productInformationGeneratedHTML = compiledTemplate(data);

        var productContainer = document.getElementById("productInformationContainer");
        productContainer.innerHTML = productInformationGeneratedHTML;
    }
</script>

<!-- Get productLocation from selected storage-->
<script>
    function POSTproductLocation(data) {
        var givenProductID = data;
        $(function () {
            $.ajax({
                type: 'POST',
                url: '?page=getProductLocation',
                data: {givenProductID: givenProductID},
                dataType: 'json',
                success: function (data) {
                    productLocationTemplate(data);
                }
            });
        });
    }
</script>

<!-- Display product location Template -->
<script>
    function productLocationTemplate(data) {
        var rawTemplate = document.getElementById("productLocationTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var storageProductGeneratedHTML = compiledTemplate(data);

        var storageContainer = document.getElementById("productLocationContainer");
        storageContainer.innerHTML = storageProductGeneratedHTML;
    }
</script>


<!-- EDIT PRODUCT -->

<!-- Get the selected product, and opens editProduct modal-->
<script>
    $(function POSTeditProductModal() {

        $('#displayProductContainer').delegate('.edit', 'click', function () {
            var givenProductID = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: '?page=getProductByID',
                data: {givenProductID: givenProductID},
                dataType: 'json',
                success: function (data) {
                    editProductTemplate(data);
                    $('#editProductModal').modal('show');
                }
            });
            return false;

        });
    });
</script>

<!-- Display edit product Template -->
<script>
    function editProductTemplate(data) {
        var rawTemplate = document.getElementById("editProductTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var editProductGeneratedHTML = compiledTemplate(data);

        var productContainer = document.getElementById("editProductContainer");
        productContainer.innerHTML = editProductGeneratedHTML;
    }
</script>

<!-- POST results from editing, and updating the table-->
<script>
    $(function POSTeditProductInfo() {

        $('#editProduct').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                error: function(){
                    errorMessageEdit();
                },
                success: function () {
                    $('#editProductModal').modal('hide');
                    successMessageEdit();
                    UpdateProductTable();
                }
            });
            return false;
        });
    });

</script>

<script>
function successMessageEdit() {    
    $('<div class="alert alert-success"><strong>Redigert!</strong> Produkt er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script> 

<script>
function errorMessageEdit() {    
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt produktnavn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script> 



<!-- CREATE PRODUCT -->

<script>
    $(function POSTproductInfo() {

        $('#createProduct').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                error: function() {         
                    errorMessage();
                },
                success: function () {
                    $("#createProduct")[0].reset();
                    $('#createProductModal').modal('hide');
                    UpdateProductTable();
                    successMessageCreate();
                }
            });
            return false;
        });
    });

</script>

<script>
function errorMessage() {    
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt brukernavn </div>').appendTo('#error')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script>

<script>
function successMessageCreate() {    
    $('<div class="alert alert-success"><strong>Opprettet!</strong> Bruker er opprettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script> 


<!-- SEARCH FOR PRODUCT -->

<script>
    $(function POSTsearchForProduct() {

        $('#searchForProduct').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    $("#searchForProduct")[0].reset();
                    productTableTemplate(data);
                }
            });
            return false;
        });
    });

</script>

<script>
function createProductInfo(){
    getMediaInfo();
    getCategoryInfo();
}                    
</script>
<script>
 function getMediaInfo() {
    var $displayMediaInformation = $('#selectMediaID');
    $displayMediaInformation.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllMediaInfo',
            dataType: 'json',
            success: function (data) {
                
                $.each(data.mediaInfo, function(i, item) {

                
                $displayMediaInformation.append('<option value="'+item.mediaID+'">'+item.mediaName+'</option>');
                    
                });                
                
                
            }
        });
    });
 }
</script>

<script>
 function getCategoryInfo() {
    var $displayCategoryInformation = $('#selectCategoryID');
    $displayCategoryInformation.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllCategoryInfo',
            dataType: 'json',
            success: function (data) {
                
                $.each(data.categoryInfo, function(i, item) {

                
                $displayCategoryInformation.append('<option value="'+item.categoryID+'">'+item.categoryName+'</option>');
                    
                });                
                
                
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