
<?php require("view/header.php");?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <div class="container">
        
        <div class="row">
        <div class="pull-right">
            <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
            </label>
        </div>
    </div>
    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group">

        
        <!-- Search for products-->
        
        <form id="searchForProduct" class="form-inline" action="?request=getAllProductInfo" method="post">
            <div class="form-group col-md-12 row">
                
                    <input class="form-control" form="searchForProduct" type="text" name="givenProductSearchWord" value="" placeholder="Søk etter produkt..">  
                    <input class="form-control btn btn-primary" form="searchForProduct" type="submit" value="Søk">
                    
                    <select id="chooseCategoryContainer" class="form-control btn btn-primary">
                        
                    </select>
                    <!-- Refresh search -->
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

                <!-- Content of displayProductContainer handlebars  -->

            </tbody>
        </table>   
        </div>
    </div>
</div>






    <!-- CREATE PRODUCT MODAL -->


    <div class="modal fade" id="createProductModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Opprett Produkt</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <table class="table">
                        <form action="?request=addProductEngine" method="post" id="createProduct">
                            <tr>
                                <th id="bordernone">Produktnavn:</th>
                                <td id="bordernone"><input class="form-control" type="text" required="required" name="givenProductName" value="" autocomplete="off" autofocus></td>
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
                                <td><input type="checkbox" id="TRUE" name="givenMacAdresse" value="1"></td>
                            </tr>
                            
                        
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
            
            <form action="?request=editProductEngine" method="post" id="editProduct">
            <div class="modal-body" >
                <table class="table">
                    <tbody id="editProductContainer">
                <!-- Content of editProductContainer handlebars-->
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
        <!-- Content of modal -->
        <div class="modal-content row">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Produkt informasjon</h4>
            </div>
            <div class="modal-body">
                
                <div id="productInformationContainer">
                    <!-- Content of productInformationContainer handlebars -->
                
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
                         
                            
                             
                              
                                <!-- Content of productLocationContainer handlebars-->
                                
                            
                                
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
        <!-- Content of modal -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Produkt informasjon</h4>
            </div>
            <form action="?request=deleteProductEngine" method="post" id="deleteProduct">
            <div class="modal-body" id="deleteProductContainer">
                
                <!-- Content of deleteProductContainer -->

            </div>
            <div class="modal-footer">
                <input class="btn btn-success" type="submit" value="Slett" form="deleteProduct">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- WARNING PRODUCT MODAL-->

<div class="modal fade" id="warningProductModal" role="dialog">
    <div class="modal-dialog">
        <!-- Content of modal -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sett varslingsgrense</h4>
            </div>
            <form action="?request=setWarningLimit" method="post" id="warningProduct">
                <br>
                <table class="table">
                    <thead>
                        <tr>
                            <th id="bordernone">Lagernavn</th>
                            <th id="bordernone">Epost Varsling</th>
                            <th id="bordernone">Lager Varsling</th>
                        </tr>
                    </thead>
                    <tbody id="warningProductContainer">
                        
                            <!-- Content of warningProductContainer handlebars -->
                           
                    </tbody>          
      
                    
                </table>

            <div class="modal-footer">
                <input class="btn btn-success" type="submit" value="Lagre" form="warningProduct">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
            </div>
            </form>
        </div>
    </div>
</div>



</div>

<!-- Help modal -->

<div class="modal fade" id="showHelpModal" role="dialog">
        <div class="modal-dialog" style="width: 70%">
            <!-- Content of modal -->
            <div class="modal-content row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Hjelp</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h3>
                            Bruk denne funksjonen for å søke etter produkter.<br>
                            Du kan også velge kategori for sortering av produkter
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                        <img src="image/SøkProdukt.PNG" alt="Søk etter produkt">
                    </div>
                
                
                    <div class="col-md-12">
                        <h3>
                            Her oppretter du nye produkter.
                        </h3>
                        
                    </div>
                <div class="col-md-12">
                    <img src="image/OpprettProdukt.PNG" alt="Opprett produkt knapp">
                    </div>
                <div class="col-md-12">
                    <h3>
                        Skriv inn info om produktet du vil opprette.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/OpprettProduktModal.PNG" alt="Opprett produkt modal">
                </div>
                    
                    <div class="col-md-12">
                    <h3>
                        Dette er en liste over alle produktene i systemet.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/Produkter1.PNG" alt="Liste over produkter">
                </div>
                    <div class="col-md-12">
                        <h3>
                        Dette er alternativer for produkt:<br>
                        </h3>
                        <label>
                            1. <img src="image/EndreBruker.PNG" alt="Endre produkt symbol">Endre produkt<br>
                            2. <img src="image/InformasjonBruker.PNG" alt="Vis informasjon om produkt symbol">Vis informasjon om produkt<br>
                            3. <img src="image/SlettBruker.PNG" alt="Slett produkt symbol">Slett produkt<br>
                            4. <img src="image/Varsling.PNG" alt="Varsling av grense symbol">Sett grense for antall produkt før epost varsling
                        
                    </label>
                </div>
                
                   
                    
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
            </div>
        </div>
        
</div>

<!-- TEMPLATES-->

<!-- Display location of product, and quantity-->
<script id="warningProductTemplate" type="text/x-handlebars-template">
    
    </td><input class="form-control" type="hidden" name="productID" value="{{productLocation.0.productID}}" >
{{#each productLocation}}
<tr>
    <td id="bordernone">{{storageName}}</td><input class="form-control" type="hidden" name="storageID[]" value="{{storageID}}" >
    <td id="bordernone"><input class="form-control" type="number" name="emailWarning[]" value="{{emailWarning}}" required="required" autocomplete="off"></td>
    <td id="bordernone"><input class="form-control" type="number" name="inventoryWarning[]" value="{{inventoryWarning}}" required="required" autocomplete="off">
  </tr>  

{{/each}}      
</script>

<!-- Handlebars for choose category -->
<script id="chooseCategoryTemplate" type="text/x-handlebars-template">
<option data-id="0" value="0">Velg Kategori</option>
{{#each category}}
<option data-id="{{categoryID}}" value="{{categoryID}}">{{categoryName}}</option>
{{/each}}
</script>

<!-- Handlebars for edit products -->
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
        <tr>
            <th>Støtter MacAdresse: </th>
                <td class="supportMacStatus" id="supportMacStatus"></td>
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
    <td class="quantityColor">{{quantity}}</td>
</tr>
{{/each}}      
</script>

<!-- Display what product you are deleting-->
<script id="deleteProductTemplate" type="text/x-handlebars-template">
    <h4>Du holder på å slette:</h4>
{{#each product}}
    <h4><b>{{productName}}</b></h4>
    <input type="hidden" form="deleteProduct" name="deleteProductID" value="{{productID}}">
    <h4>Alle produkter av denne typen vil bli slettet og fjernet fra lager som inneholder dette produktet,<br> er du sikker på at du vil fortsette?</h4>
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

    <button id="redigerknapp" data-id="{{productID}}" class="warning" data-toggle="tooltip" title="Epost varsling" >
    <span class="glyphicon glyphicon-bell" style="color: black"></span>
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


<script type="text/javascript" src="js/productAdm.js"></script>