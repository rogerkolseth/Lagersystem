<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <div class="container">
        <div class="row">
            <div class="pull-right">
                <!-- Show help button modal -->
                <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
                </label>
            </div>
        </div>
        <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group">     


            <!-- Search for storage -->

            <form class="form-inline" id="searchForStorage" action="?request=getAllStorageInfo" method="post">
                <div class="form-group col-md-12 row">

                    <input class="form-control" form="searchForStorage"type="text" name="givenStorageSearchWord" value="" placeholder="Søk etter Lager..">  
                    <input class="form-control btn btn-primary" form="searchForStorage" type="submit" value="Søk">
                    <!-- Refresh search -->
                    <button onclick="UpdateStorageTable()" class="btn btn-primary " type="button">Alle lagrer</button>
                    <div class="pull-right">
                        <button class="btn btn-info" type="button" onclick="getStorageProduct();" data-toggle="modal" data-target="#stockDeliveryModal">Varelevering</button>
                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#createStorageModal">Opprett Lager</button>
                    </div>
                </div> 
            </form>


            <br><br>
            <div id="success"></div>
            <br> 

            <!-- DISPLAY STORAGE CONTAINER -->
            <br>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><b>Lageroversikt</b></h3>
                </div>
                <table class="table table-responsive"> 

                    <tbody id="displayStorageContainer">

                        <!-- Content of displayStorageContainer handlebars  -->

                    </tbody>

                </table>

            </div>

        </div>
    </div>




    


    <!-- CREATE STORAGE MODAL -->


    <div class="modal fade" id="createStorageModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Opprett lager</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <table class="table">
                            <form action="?request=addStorageEngine" method="post" id="createStorage">

                                <tr>
                                    <th id="bordernone">Lagernavn:</th>
                                    <td id="bordernone"><input class="form-control" type="text" required="required" name="givenStorageName" value=""></td>
                                </tr>
                                <tr>
                                    <th id="bordernone">Lager skal kunne gå i minus:</th>
                                    <td id="bordernone"><input type="checkbox" name="givenNegativeSupport" value="1"></td>
                                </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="error"></div>
                    <input class="btn btn-success" form="createStorage" type="submit" value="Opprett Lager">

                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                </div>
                </form>
            </div>
        </div>
    </div> 






    <!-- EDIT STORAGE MODAL-->


    <div class="modal fade" id="editStorageModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lager informasjon</h4>
                </div>
                <form action="?request=editStorageEngine" method="post" id="editStorage"> 

                    <div class="modal-body">
                        <table class="table" id="editStorageContainer">


                            <!-- Content of editStorageContainer handlebars -->

                        </table>
                    </div>

                    <div class="modal-footer">
                        <div id="errorEdit"></div>
                        <input class="btn btn-success" form="editStorage" type="submit" value="Lagre" form="editStorage">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 




    <!-- GET STORAGE INFORMATION MODAL-->

    <div class="modal fade" id="showStorageInformationModal" role="dialog">
        <div class="modal-dialog test1233" id="test1231">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lager Informasjon</h4>
                </div>
                <div class="modal-body row">
                    <div class="col-md-7">
                        <div class="col-md-12">
                            <table class="table">
                                <tbody id="storageInformationContainer">

                                    <!-- Content of storageInformationContainer handlebars -->

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="panel-title text-center"><b>Grupper med tilgang</b></h2>
                                </div>
                                <table class="table">
                                     
                                    <tbody id="groupRestrictionContainer">
                                        
                                        <!-- Content of groupRestrictionContainer handlebars -->
                                    </tbody>   
                                </table>
                            </div>
                        
                    </div>
                        
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="panel-title text-center"><b>Produkt i lager</b></h2>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Produkt</th>
                                            <th>Antall</th> 
                                            <th>Mac</th>
                                        </tr>
                                    </thead>  
                                    <tbody id="storageProductContainer">
                                        <!-- Content of storageProductContainer handlebars -->

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    
                    
                    <div class="col-md-4">

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="panel-title text-center"><b>Brukere med tilgang</b></h2>
                                </div>
                                <table class="table">

                                    <tbody id="storageRestrictionContainer">




                                        <!-- Content of storageRestrictionContainer handlebars -->



                                    </tbody>    
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <!-- Graph of storage inventory -->
                        <canvas id="myChart"></canvas>
                        <div class="panel panel-default displayNone" id="macAdresser">
                            <div class="panel-heading">
                                    <h2 class="panel-title text-center"><b>MacAdresser:</b></h2>
                                </div>
                            <table class="table" id="macAdresser">
                                
                                <tbody id="showProductMacContainer">
                                    <!-- Content of showProductMacContainer handlebars -->
                                </tbody>
                            </table>
                        </div>
                        
                        
                    </div>
                    

                </div>
                <div class="modal-footer">
                    <div id="successRes"></div>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                </div>
            </div>
        </div>
    </div> 




    <!-- DELETE STORAGE MODAL -->


    <div class="modal fade" id="deleteStorageModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lager informasjon</h4>
                </div>
                <form action="?request=deleteStorageEngine" method="post" id="deleteStorage">
                    <div class="modal-body" id="deleteStorageContainer">

                        <!-- Content of deleteStorageContainer handlebars -->

                    </div>
                    <p id="errorMessage">

                    </p>    
                    <div class="modal-footer">
                        <div id="errorDelete"></div>
                        <input form="deleteStorage" class="btn btn-success" type="submit" value="Slett">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                    </div>
                </form>    
            </div>
        </div>
    </div>   



    <!-- STOCKTAKING MODAL -->

    <div class="modal fade" id="stocktakingModal" role="dialog">
        <div class="modal-dialog" style="width: 70%">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Varetelling</h4>
                </div>
                <form action="?request=stocktacking" method="post" id="stocktaking">

                    <div class="modal-body row" >
                        <div class="col-md-6">
                            <table class="table" id="stocktakingContainer">
                                <!-- Content of stocktakingContainer handlebars -->
                            </table>


                            <table class="table" id="stocktakingResultContainer">
                                <!-- Content of stocktakingResultContainer handlebars -->
                            </table>
                        </div>
                        <div class="col-md-6">
                            <!-- Graph of the stocktaking results -->
                            <canvas id="stocktakingResultChart"></canvas>
                        </div>


                    </div>

                    <div class="modal-footer">
                        <a href="#" id="saveToCSV" class="btn btn-success">Eksporter til csv</a>
                        <input form="stocktaking" class="btn btn-success" id="saveStocktaking" type="submit" value="Neste">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                    </div>
                </form>    
            </div>
        </div>
    </div>   
    
    <!-- Storage delivery -->

    <div class="modal fade" id="stockDeliveryModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Varelevering</h4>
                </div>
                <form action="?request=stockDelivery" method="post" id="stockDelivery">
                    <div class="modal-body">
                        <label>Velg produkt(er) som skal inn på Hovedlageret</label>
                        <div class="col-md-4 pull-right">
                            <select id="chooseCategoryContainer" class="form-control">
                                <!-- Content of chooseCategoryContainer handlebars -->
                            </select>
                        </div>
                        <div id="stockDeliveryContainer">
                            <!-- Content of stockDeliveryContainer handlebars -->
                        </div>
                        <br><br>
                        <div>
                            <table class="table table-responsive" id="deliveryQuantityContainer">

                                <!-- Content of deliveryQuantityContainer handlebars -->

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
                
                <div class="col-md-12" >
                    <h3>
                        Bruk denne funksjonen for å søke etter lager.
                    </h3>

                </div>
                <div class="col-md-12">
                    <img src="image/SøkLager.PNG" alt="Søk i lager">
                </div>
                


                <div class="col-md-12">
                    <h3>
                        Her oppretter du nye lager.
                    </h3>

                </div>
                <div class="col-md-12">
                    <img src="image/OpprettLager.PNG" alt="Knapp for oppretting av lager">
                </div>
                <div class="col-md-12">
                    <h3>
                        Skriv inn info om lageret du vil opprette.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/OpprettLagerModal.PNG" alt="Opprett lager modal">
                </div>
                
                <div class="col-md-12">
                    <h3>
                        Her kan du legge inn produkter på hovedlageret.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/Varelevering.PNG" alt="Varelevering">
                </div>
                
                <div class="col-md-12">
                    <h3>
                        Velg hvor mange og hvilke produkter du ønsker å ta inn på hovedlageret.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/VareleveringModal.PNG" alt="Varelevering modal">
                </div>
                <div class="col-md-12">
                    <h3>
                        Dette er en liste over alle lagerene i systemet.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/Lager_1.PNG" alt="Lager liste">
                </div>
                <div class="col-md-12">
                    <h3>
                        Dette er alternativer for lager:<br>
                    </h3>
                    <label>
                        1. <img src="image/EndreBruker.PNG" alt="Endre lager symbol">Endre lager<br>
                        2. <img src="image/InformasjonBruker.PNG" alt="Vis informasjon symbol">Vis informasjon om lager<br>
                        3. <img src="image/SlettBruker.PNG" alt="Slett lager symbol">Slett lager<br>
                        4. <img src="image/Vertelling_1.PNG" alt="Varetelling symbol">Varetelling for lageret
                    </label>
                </div>




                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- TEMPLATES -->

<!-- Handlebar for group restrictions -->
<script id="groupRestrictionTemplate" type="text/x-handlebars-template">
{{#each groupRestriction}}
<tr>
    <td id="bordernone">
    <button id="redigerknapp" data-id="{{resID}}" class="deleteGroupRestriction" data-toggle="tooltip" title="Fjern lagertilgang">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>
    </td>
<td id="bordernone">{{groupName}}</td>
</tr>    
    
{{/each}} 
</script>

<!-- Handlebar for showing mac adress products -->
<script id="showProductMacTemplate" type="text/x-handlebars-template">
    {{#each inventoryMac}}
    <tr>
    <td>{{macAdresse}}</td>
    <tr>
    {{/each}}
    
</script>

<!-- Handlebars for choosing categories -->
<script id="chooseCategoryTemplate" type="text/x-handlebars-template">
<option data-id="0" value="0">Velg Kategori</option>
{{#each category}}
<option data-id="{{categoryID}}" value="{{categoryID}}">{{categoryName}}</option>
{{/each}}
</script>
<!-- Handlebars for stock delivery -->
<script id="stockDeliveryTemplate" type="text/x-handlebars-template">
    <br>  
    {{#each productInfo}} 
    <button data-id="{{productID}}" class="btn btn-primary product">{{productName}}</button>
    {{/each}} 
</script>

<!-- Handlebars for delivery quantaty -->
<script id="deliveryQuantityTemplate" type="text/x-handlebars-template">
    {{#each product}} 
    <tr class="selectQuantity">
    <th>Produkt:   </th>
    <td>{{productName}}</td>
    <input name="deliveryProductID[]" id="{{productID}}" form="stockDelivery" type="hidden" value="{{productID}}"/>
    <th>Antall:</th>
    <td><input id="{{productID}}" data-id="{{macAdresse}}" class="form-control negativeSupport" name="deliveryQuantity[]" form="stockDelivery" required="required" type="number" min="1" max="1000" value="" autocomplete="off"/></td>  
        <input name="regMacadresse[]" form="stockDelivery" type="hidden" value="{{macAdresse}}"/>
    <td>
    <button type="button" id="redigerknapp" class="remove" data-id="product{{productID}}" data-toggle="tooltip" >
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>
    </td>    

    </tr>

    <tbody class="selectQuantity" id="product{{productID}}">
    </tbody>
    {{/each}}  
</script>

<!--Handlebars for display stocktacing product-->
<script id="stocktakingResultTemplate" type="text/x-handlebars-template">
    <thead>
    <tr>
    <th>Produkt</th>
    <th>Gammel verdi</th>
    <th>Ny verdi</th>
    <th>differanse</th>    
    </tr>
    </thead>
    <tbody id="tbodyid">
    {{#each differanceArray}}
    <tr>
    <td>{{productName}}</td>
    <td>{{oldQuantity}}</td>
    <td>{{newQuantity}}</td>
    <td class="stockResult">{{differance}}</td>    
    </tr>
    </tbody>
    <input form="stocktaking" name="givenProductArray[]" type="hidden" value="{{productID}}">
    <input form="stocktaking" name="givenQuantityArray[]" type="hidden" value="{{newQuantity}}"> 
    <input form="stocktaking" name="oldQuantityArray[]" type="hidden" value="{{oldQuantity}}"> 
    <input form="stocktaking" name="differanceArray[]" type="hidden" value="{{differance}}">
    {{/each}}

    <input form="stocktaking" name="givenStorageID" type="hidden" value="{{differanceArray.0.storageID}}">


</script>


<!--Handlebars for display stocktacing product-->
<script id="stocktakingTemplate" type="text/x-handlebars-template">
    <h2>{{storageProduct.0.storageName}}</h2><br>  
    <input form="stocktaking" name="givenStorageID" type="hidden" value="{{storageProduct.0.storageID}}">
    <input form="stocktaking" name="getResult" type="hidden" value="getResult"> 
    {{#each storageProduct}}

    <tr>
    <th id="bordernone">{{productName}}</th>    
    <input form="stocktaking" name="givenProductArray[]" type="hidden" value="{{productID}}">
    <input form="stocktaking" name="oldQuantityArray[]" type="hidden" value="{{quantity}}"> 
    <input form="stocktaking" name="givenProductNameArray[]" type="hidden" value="{{productName}}">            




    <td id="bordernone"><input class="form-control" type="int" required="required" name="givenQuantityArray[]" value="" autocomplete="off"></td>
    <th id="bordernone">Forventet antall</th>
    <td id="bordernone">{{quantity}}</td>

    </tr>


    {{/each}} 

</script>

<!-- Handlebars for display editStorage-->                    
<script id="editStorageTemplate" type="text/x-handlebars-template">
    {{#each storage}}    
    <input form="editStorage" type="hidden" name="editStorageID" value="{{storageID}}">
    <tr>
    <th id="bordernone">Lagernavn: </th> 
    <td id="bordernone"><input class="form-control" form="editStorage" required="required" type="text" name="editStorageName" value="{{storageName}}" autocomplete="off"></td> 
    </tr>
    <tr>
    <th id="bordernone">Lager skal kunne gå i minus: </th>
    <td id="bordernone"><input id="editNegativeSupport"  type="checkbox" form="editStorage" name="editNegativeSupport" value="1"></td>
    </tr>
    {{/each}}            
</script>  


<!-- Handlebars for display StorageInformation-->
<script id="storageInformationTemplate" type="text/x-handlebars-template">
    {{#each storage}}    
    <tr>  
    <th id="bordernone" class="col-md-6">LagerID: </th>
    <td id="bordernone">{{storageID}}</td> 
    </tr>
    <tr>
    <th class="col-md-6">Lagernavn: </th>
    <td>{{storageName}}</td>
    </tr>
    
    <th class="col-md-6">Lager kan gå i minus: </th>
    <td class="negativeSupportStatus" id="negativeSupportStatus"></td>
    </tr>
    {{/each}}                
</script>   

<!-- handlebars for display StorageRetricton-->
<script id="storageRestrictionTemplate" type="text/x-handlebars-template">
    {{#each storageRestriction}}
    <tr>
    <td >
    <button id="redigerknapp" data-id="{{userID}}" class="deleteUserRestriction" data-toggle="tooltip" title="Fjern lagertilgang">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>
    </td>
    <td >{{name}}</td>    
    </tr>        
    {{/each}}       
</script>

<!-- Handlebars for display StorageProduct-->
<script id="storageProductTemplate" type="text/x-handlebars-template">
    {{#each storageProduct}}
    <tr>
    <td >
    <button id="redigerknapp" data-id="{{productID}}" class="deleteStorageInventory" data-toggle="tooltip" title="Fjern produkt">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>
    </td>
    <th>{{productName}}</th>
    <td class="quantityColor"> {{quantity}}</td>
    <td >
    {{#if_eq this.macAdresse "1"}}
        <button id="redigerknapp" data-id="{{productID}}" class="showMac" data-toggle="tooltip" onClick="showMac();" title="Vis MacAdresse for produkter">
        <span class="glyphicon glyphicon-th-list" style="color: #003366"></span>
        </button>
        {{/if_eq}}
    </td>             
    </tr>
    {{/each}}    
</script>


<!-- Handlebars for display what storage you are deleting-->
<script id="deleteStorageTemplate" type="text/x-handlebars-template">
    <h4>Du holder på å slette:</h4>
    {{#each storage}}
    <h4><b>{{storageName}}</b></h4>
    <input type="hidden" form="deleteStorage" name="deleteStorageID" value="{{storageID}}"> 
                
    <h4>Alle produkter og tilganger til lageret vil bli slettet,<br> er du sikker på at du vil fortsette?</h4>
    {{/each}} 
</script>   



<!-- Handlebars for display all users template -->
<script id="displayStorageTemplate" type="text/x-handlebars-template">

    {{#each storageInfo}} 
    <tr>
    <td class="text-center col-md-2">  


    <!-- Knapp som aktiverer Model for lagerredigering  --> 

    <button id="redigerknapp" data-id="{{storageID}}" class="edit" data-toggle="tooltip" title="Rediger lager">
    <span class="glyphicon glyphicon-edit" style="color: green"></span>
    </button>


    <!-- Knapp som aktiverer Model for å vise lagerinformasjon  --> 

    <button id="redigerknapp" data-id="{{storageID}}" class="information" data-toggle="tooltip" title="Vis informasjon">
    <span class="glyphicon glyphicon-menu-hamburger" style="color: #003366" ></span>
    </button>


    <!-- Knapp som aktiverer Model for sletting av lager  --> 



    <button id="redigerknapp" data-id="{{storageID}}" class="delete" data-toggle="tooltip" title="Slett lager">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>

    <!-- Knapp som aktiverer Model for varetelling av lager  --> 

    <button id="redigerknapp" data-id="{{storageID}}" class="update" data-toggle="tooltip" title="Varetelling">
    <span class="glyphicon glyphicon-flag" style="color: #002E5F"></span>
    </button>
    </td>

    <!-- Printer ut lagernavn inn i tabellen -->

    <th>Lagernavn: </th>
    <td>{{storageName}}</td>

    {{/each}}
    </tr>


</script>



<script type="text/javascript" src="js/storageAdm.js"></script>   