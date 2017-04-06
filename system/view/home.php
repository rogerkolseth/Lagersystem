<?php require("view/header.php"); ?>

<?php
if (isset($GLOBALS["errorMessage"])) {
    $test = $GLOBALS["errorMessage"];
}
?>



<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <div id="message">
        <?php
        if (isset($GLOBALS["errorMessage"])) {
            echo $test;
        }
        ?>
    </div>
    
    <?php if ($_SESSION["userLevel"] == "Administrator") {?>
    <div id="snarveidiv">

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-bookmark"></span> Snarveier</h3>
                    </div>
                    <div class="panel-body">
                        <div class="">
                            <div class="col-xs-6 col-sm-6 col-md-12 text-center">
                                <div class="pull-left">
                                    <button class="btn btn-success btn-md" type="button" onclick="getMediaInfo();" data-toggle="modal" data-target="#createUserModal"><span class="glyphicon glyphicon-user"></span> <br/>Opprett bruker</button>
                                    <button class="btn btn-success btn-md" onclick="createProductInfo();" type="button" data-toggle="modal" data-target="#createProductModal"><span class="glyphicon glyphicon-shopping-cart"></span> <br/>Opprett produkt</button>
                                    <button class="btn btn-success btn-md" type="button" data-toggle="modal" data-target="#createStorageModal"><span class="glyphicon glyphicon-home"></span> <br/>Opprett lager</button>
                                    <button class="btn btn-success btn-md" role="button" data-toggle="modal" data-target="#createCategoryModal"><span class="glyphicon glyphicon-folder-open"></span> <br/>Opprett kategori</button>

                                    <button class="btn btn-success btn-md" onclick="getCategoryInfo()" type="button" data-toggle="modal" data-target="#uploadImageModal"><span class="glyphicon glyphicon-picture"></span> <br/>Last opp bilde</button>
                                    <button class="btn btn-success btn-md" onclick="getStorageInfo()" type="button" data-toggle="modal" data-target="#stockTakingModal"><span class="glyphicon glyphicon-flag"></span> <br/>Varetelling</button>
                                    <button class="btn btn-success btn-md" type="button" onclick="getStorageProduct()" data-toggle="modal" data-target="#stockDeliveryModal"><span class="glyphicon glyphicon-th-list"></span> <br/>Varelevering</button>

                                </div>
                                <div class="pull-right">
                                    <a href="?page=editUser" class="btn btn-warning btn-md" role="button"><span class="glyphicon glyphicon-user"></span> <br/>Rediger Profil</a>
                                    <a href="../" class="btn btn-danger btn-md" role="button"><span class="glyphicon glyphicon-log-out"></span> <br/>Logg ut</a>
                                </div>
                            </div>
                                
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
    <?php }?>
   
    
 
<div class="container">

    <div class="col-md-12">
        <?php if ($_SESSION["userLevel"] == "Administrator") {?>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2 class="panel-title text-center"><b>Snart tom lagerbeholdning</b></h2>
                </div>
                <table class="table fontSizeTableContainer">
                    <thead>
                        <tr>
                            <th>Produkt</th>
                            <th>Antall</th>
                            <th>Lager</th>
                        </tr>
                    </thead>
                    <tbody id="lowInvContainer">
                    <!-- Handlebars -->
                    </tbody>
                </table>
            </div>
            
        </div>
        <?php }?>
        <?php if ($_SESSION["userLevel"] == "User") {?>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading" id="panelcolor">
                    <h2 class="panel-title text-center"><b>Siste salg</b></h2>
                </div>
                
                <table class="table fontSizeTableContainer">
                    <thead>
                        <tr>
                            <th>Selger</th>
                            <th>KundeNr</th>
                            <th>Lager</th>
                            <th>Antall</th>
                            <th>Kommentar</th>
                            <th>Dato</th>
                        </tr>
                    </thead>
                    
                    <tbody id="allLastSaleContainer">
                        <!-- Handlebars -->
                    </tbody>
                </table>
            </div>
            
        </div>
        <?php }?>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2 class="panel-title text-center"><b>Dine siste salg</b></h2>
                    
                </div>
                <table class="table fontSizeTableContainer">
                    <thead>
                        <tr>
                            <th>KundeNr</th>
                            <th>Produkt</th>
                            <th>Lager</th>
                            <th>Antall</th>
                            <th>Kommentar</th>
                            <th>Dato</th>
                        </tr>
                    </thead>
                    <tbody id="lastSaleContainer">
                        <!-- Handlebars -->
                    </tbody>
                </table>
                
            </div>
            
        </div>
    </div>
        <?php if ($_SESSION["userLevel"] == "Administrator") {?>
        <div class="col-md-12">
            
            <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading" >
                    
                    <h2 class="panel-title text-center"><b>Siste hendeleser</b></h2>
                </div>
                <table class="table fontSizeTableContainer" id="loggTableContainer">
                <!-- Innhold fra Handlebars Template -->
                </table>
                
            </div>
            </div>
           
            
        </div>
        <?php }?>


        <div class="col-md-12">
        <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title text-center"><b>Lagerbeholdning</b></h2>
                        <br>
                        <select name="fromStorageID" id="chooseStorageContainer" class="form-control">

                                <!-- Her kommer Handlebars Template-->

                        </select>
                        
                    </div>
                

                
                <table class="table table-bordered fontSizeTableContainer">
                    <thead>
                        <tr>
                            <th>Produkt</th>
                            <th>Antall</th>
                        </tr>
                    </thead>
                    
                    <tbody id="chosenStorageContainer">
                        
                    <!-- Her kommer Handlebars Template-->
                    </tbody>
                </table>
            </div>
            </div>
            <div class="col-md-6">
                
                <canvas id="myChart" height="290"></canvas>
                
              
            </div>
        </div>
    </div>
    
        <!-- HER KOMMER INNHOLDET>   -->                

        <!-- Opprett bruker modal -->


        <div class="modal fade" id="createUserModal" role="dialog">
            <div class="modal-dialog">
                <!-- Innholdet til Modalen -->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Opprett bruker</h4>
                    </div>
                    <form action="?page=addUserEngine" method="post" id="createUser">
                        <div class="modal-body">
                            <div class="text-center">
                                <table class="table">
                                
                                    <tr>
                                        <th style="border: none">Name:</th>
                                        <td style="border: none"><input class="form-control" type="text" name="givenName" required="required" value="" autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <th>Brukernavn:</th>
                                        <td><input class="form-control" type="text" name="givenUsername" required="required" value="" autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <th>Passord:</th>
                                        <td><input class="form-control" type="text" name="givenPassword" required="required" value="" autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><input class="form-control" type="text" name="givenEmail" required="required" value="" autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <th>UserLevel:</th>                                       
                                        <td>
                                            <select name="givenUserLevel" required="required" class="form-control" autocomplete="off">
                                                <option></option>
                                                <option value="User">User</option>
                                                <option value="Administrator">Administrator</option>
                                                </select>  
                                         </td>
                                    </tr>
                                    <tr>
                                        <th>Media:</th>
                                        <td>
                                            <select name="givenMediaID" id="selectMediaIDuser" required="required" class="form-control" autocomplete="off">
                                            </select>
                                        </td>
                                    </tr>

                                    </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <p id="errorMessage"></p>
                            <input class="btn btn-success" form="createUser" type="submit" value="Opprett bruker">


                            <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                        </div>
                        </form>
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
                                    <td><input class="form-control" type="number" required="required" name="givenPrice" value="" autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <th>Kategori:</th>
                                    <td>
                                        <select name="givenCategoryID" id="selectCategoryPro" required="required" class="form-control" autocomplete="off">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Media:</th>
                                    <td>
                                        <select name="givenMediaID" id="selectMediaIDpro" required="required" class="form-control" autocomplete="off">
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

                                    <input class="btn btn-success" form="createProduct" type="submit" value="Opprett Produkt">

                                    <button class="btn btn-danger" type="button" data-dismiss="modal">Avslutt</button>

                                </div>
                            </form>
                    </div>
                </div>
            </div>


            <!-- CREATE STORAGE MODAL -->


            <div class="modal fade" id="createStorageModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Innholdet til Modalen -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Opprett bruker</h4>
                        </div>
                        <div class="modal-body">
                            <form action="?page=addStorageEngine" method="post" id="createStorage">
                                <div style="text-align: center">
                                    <table class="table">                   
                                        <tr>
                                            <th id="bordernone">Lagernavn:</th>
                                            <td id="bordernone"><input class="form-control" type="text" required="required" name="givenStorageName" value=""></td>
                                        </tr>

                                    </table>
                                </div>
                        </div>
                        <div class="modal-footer">

                            <input class="btn btn-success" form="createStorage" type="submit" value="Opprett Lager" href="?page=storageAdm">


                            <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                        </div>
                        </form>
                    </div>
                </div>
            </div> 

            <!-- UPLOAD IMAGE MODAL -->


            <div class="modal fade" id="uploadImageModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Innholdet til Modalen -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Last opp bilde</h4>
                        </div>
                        <div class="modal-body">
                            <div style="text-align: center">

                                <form action="?page=uploadImageShortcut" id="uploadImage" method="post" enctype="multipart/form-data">
                                    <h4 class="text-center">Velg bilde for å laste opp</h4>
                                    <table class="table">
                                        <tr>
                                            <th class="col-sm-4 col-md-4" id="bordernone">Velg en fil:</th>
                                            <th class="col-sm-4 col-md-4" id="bordernone"></th>
                                            <th class="col-sm-4 col-md-4" id="bordernone">Velg en katerogi:</th>
                                        </tr>


                                        <tr>                           
                                            <td id="bordernone">
                                                <label class="btn btn-primary" for="fileToUpload">
                                                    Legg til bilde
                                                    <input type="file" name="fileToUpload" required="required" id="fileToUpload" style="display: none;" onchange="$('#upload-file-info').html($(this).val());"></td>
                                                </label>
                                            <td id="bordernone"><span class="label label-default" id="upload-file-info"></span></td>
                                            <td id="bordernone">
                                                <select name="givenCategoryID" id="selectCategoryMed" required="required" class="form-control" autocomplete="off">
                                                </select>
                                            </td>
                                        </tr>
                                    </table>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <input class="btn btn-success" form="uploadImage" type="submit" value="Upload Image" name="submit" href="?page=uploadImage">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>  


            <!-- CREATE CATEGORY MODAL -->


            <div class="modal fade" id="createCategoryModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Innholdet til Modalen -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Opprett bruker</h4>
                        </div>
                        <div class="modal-body">
                            <form action="?page=addCategoryEngine" method="post" id="createCategory">
                                <div style="text-align: center">
                                    <table class="table">                   
                                        <tr>
                                            <th id="bordernone">Kateroginavn:</th>
                                            <td id="bordernone"><input class="form-control" type="text" required="required" name="givenCategoryName" value=""></td>
                                        </tr>

                                    </table>
                                </div>
                        </div>
                        <div class="modal-footer">

                            <input class="btn btn-success" form="createCategory" type="submit" value="Opprett Kategori">


                            <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                        </div>
                        </form>
                    </div>
                </div>
            </div>     

            <!-- Tom modal til Roger -->


            <div class="modal fade" id="stockTakingModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Innholdet til Modalen -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Varetelling</h4>
                        </div>
                        <form action="?page=stocktacking" method="post" id="stocktaking">

                            <div class="modal-body">
                                <select name="storageID" form="stocktaking" id="selectStorageContainer" class="form-control">

                                </select>
                                <br>
                                <table class="table" id="stockTakingContainer">

                                </table>

                            </div>
                            <div class="modal-footer">
                                <input form="stocktaking" class="btn btn-success" type="submit" value="Oppdater">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                            </div>
                        </form>
                        </form>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="stockDeliveryModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Innholdet til Modalen -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Varelevering</h4>
                        </div>
                        <form action="?page=stockDelivery" method="post" id="stockDelivery">
                            <div class="modal-body">
                                <label>Velg produkt som inn på hovedlager</label>
                                <div id="stockDeliveryContainer">

                                </div>
                                <br><br>
                                <div>
                                    <table class="table table-responsive" id="deliveryQuantityContainer">

                                        <!-- Lar deg velge antall enheter -->

                                    </table>

                                    
                                </div>                        


                            </div>
                            <div class="modal-footer">
                                <button form="stockDelivery" type="submit" class="btn btn-success" id="deliveryButton">Overfør</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                            </div>
                        </form>
                        </form>

                    </div>
                </div>
            </div>        

            
<script id="deliveryQuantityTemplate" type="text/x-handlebars-template">
{{#each product}} 
    <tr class="selectQuantity">
        <th>Produkt:   </th>
        <td>{{productName}}</td>
        <input name="deliveryProductID[]" id="{{productID}}" form="stockDelivery" type="hidden" value="{{productID}}"/>
        <th>Antall:</th>
        <td><input class="form-control" name="deliveryQuantity[]" form="stockDelivery" required="required" type="number" min="1" max="1000" value="" autocomplete="off"/></td>  
        
        <td>
            <button id="redigerknapp" class="remove" data-toggle="tooltip" >
                <span class="glyphicon glyphicon-remove" style="color: red"></span>
            </button>
        </td>    
        
    </tr>
{{/each}}  
</script>

            <script id="stockTakingTemplate" type="text/x-handlebars-template">
            <input form="stocktaking" name="givenStorageID" type="hidden" value="{{storageProduct.0.storageID}}">
            {{#each storageProduct}}
    
                <tr>
                   <th id="bordernone">{{productName}}:</th>    
                       <input form="stocktaking" name="givenProductArray[]" type="hidden" value="{{productID}}">
                       <input form="stocktaking" name="oldQuantityArray[]" type="hidden" value="{{quantity}}">                       
                   <td id="bordernone"><input class="form-control" type="int" required="required" name="givenQuantityArray[]" value="{{quantity}}" autocomplete="off"></td>
                </tr>
    
    
              {{/each}} 
            </script>    

            <script id="stockDeliveryTemplate" type="text/x-handlebars-template">
                <br>  
                {{#each productInfo}} 
                <button data-id="{{productID}}" class="btn btn-default product">{{productName}}</button>
                {{/each}} 
            </script>

            <!-- Display storages in drop down meny Template -->
            <script id="selectStorageTemplate" type="text/x-handlebars-template">
            <option data-id="0" value="0" class="stockTaking">Velg et lager</option>
                {{#each storageInfo}}    
                    <tr>
                        <option data-id="{{storageID}}" value="{{storageID}}" class="stockTaking">{{storageName}}</option>
                    </tr>   
                {{/each}}
            </script>        

            <!-- Get the selected storage, and POST this to retrive inventory-->
            <script>

                function getStorageProduct() {
                    $.ajax({
                        type: 'GET',
                        url: '?page=getAllProductInfo',
                        dataType: 'json',
                        success: function (data) {
                            stockDeliveryTemplate(data);
                        }
                    });
                    return false;
                }
            </script>

            <!-- Display products in storage Template -->
            <script>
                function stockDeliveryTemplate(data) {
                    var rawTemplate = document.getElementById("stockDeliveryTemplate").innerHTML;
                    var compiledTemplate = Handlebars.compile(rawTemplate);
                    var deliverytGeneratedHTML = compiledTemplate(data);

                    var deliveryContainer = document.getElementById("stockDeliveryContainer");
                    deliveryContainer.innerHTML = deliverytGeneratedHTML;
                }
            </script>
            
            <!-- Get productInfo from selected ID -->
<script>
                            
    $(function POSTselectedProduct() {

        $('#stockDeliveryContainer').delegate('.product', 'click', function () {
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
                    deliveryQuantityTemplate(data);
                }
            });
            return false;

        }
        });
    });
</script> 

<script>
    function deliveryQuantityTemplate(data) {
        var rawTemplate = document.getElementById("deliveryQuantityTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var transferProductGeneratedHTML = compiledTemplate(data);

        var transferContainer = document.getElementById("deliveryQuantityContainer");
        transferContainer.innerHTML += transferProductGeneratedHTML;
        
    }
</script>

<script>
    $(function POSTtransferProducts() {

        $('#stockDelivery').submit(function () {
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
                    $('#deliveryQuantityContainer').empty();
                    $('#stockDeliveryModal').modal('hide');
                }
            });
            return false;
        });
    });
</script>

<!-- remove product -->
<script>
    $(function POSTdeleteStorageModal() {

        $('#deliveryQuantityContainer').delegate('.remove', 'click', function () {
           var $tr = $(this).closest('tr');        
          
           $tr.fadeOut(150, function() {
            $(this).remove();
           });       
        });
    });
</script>  

            <!-- Get storage information  -->
            <script>
                function getStorageInfo() {
                    $.ajax({
                        type: 'GET',
                        url: '?page=getAllStorageInfo',
                        dataType: 'json',
                        success: function (data) {
                            selectStorageTemplate(data);
                        }
                    });
                }
            </script>

            <!-- Display storages in drop down meny Template -->
            <script>
                function selectStorageTemplate(data) {

                    var rawTemplate = document.getElementById("selectStorageTemplate").innerHTML;
                    var compiledTemplate = Handlebars.compile(rawTemplate);
                    var selectStorageGeneratedHTML = compiledTemplate(data);

                    var storageContainer = document.getElementById("selectStorageContainer");
                    storageContainer.innerHTML = selectStorageGeneratedHTML;


                }
            </script>

            <!-- Get the selected storage, and POST this to retrive inventory-->

            <script>
                var givenStorageID;
                $(function POSTfromStorageModal() {

                    $('#selectStorageContainer').on('change', function () {
                        givenStorageID = $(this).find("option:selected").data('id');

                        if (givenStorageID > 0) {
                            $.ajax({
                                type: 'POST',
                                url: '?page=getStorageProduct',
                                data: {givenStorageID: givenStorageID},
                                dataType: 'json',
                                success: function (data) {
                                    stockTakingTemplate(data);
                                }
                            });
                        }
                        return false;

                    });
                });
            </script>

            <!-- Display products in storage Template -->
            <script>
                function stockTakingTemplate(data) {
                    var rawTemplate = document.getElementById("stockTakingTemplate").innerHTML;
                    var compiledTemplate = Handlebars.compile(rawTemplate);
                    var stockTakingGeneratedHTML = compiledTemplate(data);

                    var stockTaking = document.getElementById("stockTakingContainer");
                    stockTaking.innerHTML = stockTakingGeneratedHTML;
                }
            </script>


            <!-- Create product -->
            <script>
                $(function POSTuserInfo() {

                    $('#createUser').submit(function () {
                        var url = $(this).attr('action');
                        var data = $(this).serialize();

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            dataType: 'json',
                            error: function () {
                                var $displayError = $('#errorMessage');
                                $displayError.empty().append("Brukernavn trolig i bruk");
                            },
                            success: function () {
                                $("#createUser")[0].reset();
                                $('#createUserModal').modal('hide');
                                $('#errorMessage').remove();

                            }
                        });
                        return false;
                    });
                });

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
                            success: function () {
                                $("#createProduct")[0].reset();
                                $('#createProductModal').modal('hide');
                            }
                        });
                        return false;
                    });
                });

            </script>

            <!-- CREATE STORAGE -->


            <script>
                $(function POSTstorageInfo() {

                    $('#createStorage').submit(function () {
                        var url = $(this).attr('action');
                        var data = $(this).serialize();

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            dataType: 'json',
                            success: function () {
                                $("#createStorage")[0].reset();
                                $('#createStorageModal').modal('hide');
                            }
                        });
                        return false;
                    });
                });

            </script>

            <script>
                $(function POSTstorageInfo() {

                    $('#createCategory').submit(function () {
                        var url = $(this).attr('action');
                        var data = $(this).serialize();

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            dataType: 'json',
                            success: function () {
                                $("#createCategory")[0].reset();
                                $('#createCategoryModal').modal('hide');
                            }
                        });
                        return false;
                    });
                });

            </script>



            <script>
                function createProductInfo() {
                    getMediaInfo();
                    getCategoryInfo();
                }
            </script>
            <script>
                function getMediaInfo() {
                    
                    var $displayMediaInformationUser = $('#selectMediaIDuser');
                    $displayMediaInformationUser.empty();
                    
                    var $displayMediaInformationPro = $('#selectMediaIDpro');
                    $displayMediaInformationPro.empty();
                    $(function () {
                        $.ajax({
                            type: 'GET',
                            url: '?page=getAllMediaInfo',
                            dataType: 'json',
                            success: function (data) {

                                $.each(data.mediaInfo, function (i, item) {

                                    $displayMediaInformationPro.append('<option value="' + item.mediaID + '">' + item.mediaName + '</option>');
                                    $displayMediaInformationUser.append('<option value="' + item.mediaID + '">' + item.mediaName + '</option>');
                                });


                            }
                        });
                    });
                }
            </script>
            <script>
                function getCategoryInfo() {
                    var $displayCategoryInformationMed = $('#selectCategoryMed');
                    var $displayCategoryInformationPro = $('#selectCategoryPro');
                    $displayCategoryInformationMed.empty();
                    $displayCategoryInformationPro.empty();
                    $(function () {
                        $.ajax({
                            type: 'GET',
                            url: '?page=getAllCategoryInfo',
                            dataType: 'json',
                            success: function (data) {

                                $.each(data.categoryInfo, function (i, item) {

                                    $displayCategoryInformationMed.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');
                                    $displayCategoryInformationPro.append('<option value="' + item.categoryID + '">' + item.categoryName + '</option>');

                                });


                            }
                        });
                    });
                }
            </script>


            <!-- POST results from stocktaking, and updating the table-->
            <script>
                $(function POSTeditStorageInfo() {

                    $('#stocktaking').submit(function () {
                        var url = $(this).attr('action');
                        var data = $(this).serialize();
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            dataType: 'json',
                            success: function () {
                                $('#stockTakingModal').modal('hide');
                            }
                        });
                        return false;
                    });
                });

            </script>

            <script>
$(document).ready(function()
{
    $('#stockDeliveryModal').on('hidden.bs.modal', function(e)
    { 
      $('#deliveryQuantityContainer').empty();
    }) ;
});
            </script>


            <!-- Get storage information with user restriction -->
<script>
 

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
        var rawTemplate = document.getElementById("chooseStorageTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var transferRestrictionGeneratedHTML = compiledTemplate(data);

        var transferContainer = document.getElementById("chooseStorageContainer");
        transferContainer.innerHTML = transferRestrictionGeneratedHTML;

    }
</script>

<!-- Display storages in drop down meny Template -->
<script id="chooseStorageTemplate" type="text/x-handlebars-template">
<option data-id="0" value="0" class="withdrawStorage">Velg et lager</option>
{{#each transferRestriction}}    
<tr>
    <option data-id="{{storageID}}" value="{{storageID}}" class="withdrawStorage">{{storageName}}</option>
</tr>   
{{/each}}
        
</script> 
<!-- Get the selected storage, and POST this to retrive inventory-->
<script>
    var givenStorageID;
    $(function POSTfromStorageModals() {
        
        $('#chooseStorageContainer').on('change', function () {
            givenStorageID = $(this).find("option:selected").data('id');
            
            chartTest(givenStorageID);

            if (givenStorageID > 0) {
                $.ajax({
                    type: 'POST',
                    url: '?page=getStorageProduct',
                    data: {givenStorageID: givenStorageID},
                    dataType: 'json',
                    success: function (data) {
                        chosenStorageTemplate(data);
                        rowColor();
                       
                    }
                });
            } 

            return false;

        });
    });
</script>

<script>
    function chosenStorageTemplate(data) {
        var rawTemplate = document.getElementById("chosenStorageTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var transferProductGeneratedHTML = compiledTemplate(data);

        var transferContainer = document.getElementById("chosenStorageContainer");
        transferContainer.innerHTML = transferProductGeneratedHTML;
    }
</script>

<script id="chosenStorageTemplate" type="text/x-handlebars-template">
     
    {{#each storageProduct}}
    <tr class="quantityColor">
    <td class="quantityColor">{{productName}}</td>
    <td class="quantityColor">{{quantity}}</td>
    </tr>
    {{/each}} 
</script>

<!-- Get storageInventory from selected storage-->
<script>
    function chartTest(data) {
         
        var givenStorageID = data;
       
        $(function () {
          
            $.ajax({
                type: 'POST',
                url: '?page=chartProduct',
                data: {givenStorageID: givenStorageID},
                dataType: 'json',
                success: function (data) {

                    
                    drawChart(data);
                    
                }
            });
        });
    }
    
    var myObjBar;
    
    function drawChart(data)
    {
        if(myObjBar)
        {
            myObjBar.destroy();
        }
          var ctx = document.getElementById('myChart').getContext('2d');
          var product = [];
          var antall = [];
          var farge = [];
          


                        $.each(data, function(i, item){
                        product.push(item.productName);
                        antall.push(item.quantity);
                        });     
                        
                        var bars = antall;
                        for(i = 0; i < bars.length; i++){
                        //You can check for bars[i].value and put your conditions here
                        if(bars[i] >= 10)
                        {
                            //grønn
                            farge.push("#5cb85c");
                        }
                        else if(bars[i] < 10 && bars[i] >=5)
                        {
                            //orange
                            farge.push("#f0ad4e");
                        }
                        else if(bars[i] < 5)
                        {
                            //rød
                            farge.push("#d9534f");
                        }
                        }
                            window.myObjBar = new Chart(ctx, {
                                
                            type: 'bar',
                            data: {
                                labels: product,
                                datasets: [
                                    {
                                        label: "Antall",
 
                                        borderColor: "black",
                                        backgroundColor: farge,
                                        borderWidth: 1,
                                        data: antall
                                      }
                            ]
                            },                    
                        
                            options: {
                                legend: {
                                    display: false
                                },
                                scales:{
                                    yAxes: [{
                                        ticks: {
                                        beginAtZero: true
                                        }
                                    }]
                                }
                            },
                            responsive : true

                        });
}
</script>


<script id="loggTableTemplate" type="text/x-handlebars-template">
<thead>
    <tr>
        <th>Type</th>
        <th>Beskrivelse</th>
        <th>Lagernavn</th>
        <th>Til lager</th>
        <th>Fra lager</th>      
        <th>Antall</th>
        <th>Gammelt antall</th>
        <th>Nytt antall</th>
        <th>Differanse</th>
        <th>Brukernavn</th>
        <th>På bruker</th>
        <th>Produkt</th>    
        <th>KundeNr</th>
        <th>Dato</th>            
        </tr>
</thead>
<tbody id="tbodyid">
{{#each latestLoggInfo}}
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
        url: '?page=getLatestLoggInfo',
        dataType: 'json',
        success: function (data) {
            displayLoggTable(data);
        }
    });
});   
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

<script>
 $(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getLowInventory',
        dataType: 'json',
        success: function (data) {
            displayLowInvTable(data);
            rowColor();
        }
    });
});   
</script> 


<script>
    function displayLowInvTable(data) {
        var rawTemplate = document.getElementById("lowInvTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var loggTableGeneratedHTML = compiledTemplate(data);

        var loggContainer = document.getElementById("lowInvContainer");
        loggContainer.innerHTML = loggTableGeneratedHTML;
    }
</script>

<script id="lowInvTemplate" type="text/x-handlebars-template">


{{#each lowInv}}
    <tr>
        <td class="quantityColor">{{productName}}</td>
        <td class="quantityColor">{{quantity}}</td>
        <td class="quantityColor" >{{storageName}}</td>
    <tr>

{{/each}}
            
  
</script>



<script>
 $(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getLastSaleInfo',
        dataType: 'json',
        success: function (data) {
            displayLastSaleTable(data);
            
        }
    });
});   
</script>

<script>
    function displayLastSaleTable(data) {
        var rawTemplate = document.getElementById("lastSaleTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var loggTableGeneratedHTML = compiledTemplate(data);

        var loggContainer = document.getElementById("lastSaleContainer");
        loggContainer.innerHTML = loggTableGeneratedHTML;
    }
</script>

<script id="lastSaleTemplate" type="text/x-handlebars-template">


{{#each lastSaleInfo}}
    <tr>
    <td>{{customerNr}}</td>
    <td>{{productName}}</td>
    <td>{{storageName}}</td>
    <td>{{quantity}}</td>
    <td>{{comment}}</td>
    <td>{{date}}</td>
    </tr>

{{/each}}
            
  
</script>
<script>
 $(function () {
    $.ajax({
        type: 'GET',
        url: '?page=getAllLastSaleInfo',
        dataType: 'json',
        success: function (data) {
            displayAllLastSaleTable(data);
            
        }
    });
});   
</script>

<script>
    function displayAllLastSaleTable(data) {
        var rawTemplate = document.getElementById("allLastSaleTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var loggTableGeneratedHTML = compiledTemplate(data);

        var loggContainer = document.getElementById("allLastSaleContainer");
        loggContainer.innerHTML = loggTableGeneratedHTML;
    }
</script>

<script>
function rowColor(){
    
// storageInformation    
$('.quantityColor').filter(function(index){
    return parseInt(this.innerHTML) >= 10;
}).siblings().andSelf().attr('class', 'bg-success');

$('.quantityColor').filter(function(index){
    return parseInt(this.innerHTML) < 10 && parseInt(this.innerHTML) >= 5;
}).siblings().andSelf().attr('class', 'bg-warning');

$('.quantityColor').filter(function(index){
    return parseInt(this.innerHTML) < 5;
}).siblings().andSelf().attr('class', 'bg-danger');


// stocktaking result
// || 
    
$('.stockResult').filter(function(index){
    return parseInt(this.innerHTML) >= 10 || parseInt(this.innerHTML) <= -10;
}).siblings().andSelf().attr('class', 'bg-danger');

$('.stockResult').filter(function(index){
    return parseInt(this.innerHTML) < 10 && parseInt(this.innerHTML) >= 5 || parseInt(this.innerHTML) > -10 && parseInt(this.innerHTML) <= -5;
}).siblings().andSelf().attr('class', 'bg-warning');

$('.stockResult').filter(function(index){
    return parseInt(this.innerHTML) < 5 || parseInt(this.innerHTML) > -5;
}).siblings().andSelf().attr('class', 'bg-success');

}

</script>

<script id="allLastSaleTemplate" type="text/x-handlebars-template">

{{#each allLastSaleInfo}}
    <tr>
    <td>{{username}}</td>
    <td>{{customerNr}}</td>
    <td>{{productName}}</td>
    <td>{{storageName}}</td>
    <td>{{quantity}}</td>
    <td>{{comment}}</td>
    <td>{{date}}</td>
    

{{/each}}
            
  
</script>