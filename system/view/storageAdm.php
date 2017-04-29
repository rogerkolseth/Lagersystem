<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <div class="container">
        <div class="row">
            <div class="pull-right">
                <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
                </label>
            </div>
        </div>
        <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group">     


            <!-- SØK ETTER LAGER -->

            <form class="form-inline" id="searchForStorage" action="?page=getAllStorageInfo" method="post">
                <div class="form-group col-md-12 row">

                    <input class="form-control" form="searchForStorage"type="text" name="givenStorageSearchWord" value="" placeholder="Søk etter Lager..">  
                    <input class="form-control btn btn-primary" form="searchForStorage" type="submit" value="Søk">

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

                        <!-- HER KOMMER INNHOLDET FRA HANDLEBARS  -->

                    </tbody>

                </table>

            </div>

        </div>
    </div>




    <!-- DIV som holder på all informasjon til høgre på skjermen  -->


    <!-- CREATE STORAGE MODAL -->


    <div class="modal fade" id="createStorageModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Opprett lager</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <table class="table">
                            <form action="?page=addStorageEngine" method="post" id="createStorage">

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
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lager informasjon</h4>
                </div>
                <form action="?page=editStorageEngine" method="post" id="editStorage"> 

                    <div class="modal-body">
                        <table class="table" id="editStorageContainer">


                            <!-- Innhold fra Handlebars Template -->

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
        <div class="modal-dialog" style="width: 70%">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lager Informasjon</h4>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-3 col-md-5">
                        <div class="col-md-12">
                            <table class="table">
                                <tbody id="storageInformationContainer">

                                    <!-- Her kommer handlebars Template -->

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="panel-title text-center"><b>Brukere med tilgang</b></h2>
                                </div>
                                <table class="table">

                                    <tbody id="storageRestrictionContainer">




                                        <!-- Her kommer handlebars Template -->



                                    </tbody>    
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                                        </tr>
                                    </thead>  
                                    <tbody id="storageProductContainer">

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-9 col-md-7">

                        <canvas id="myChart"></canvas>

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
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lager informasjon</h4>
                </div>
                <form action="?page=deleteStorageEngine" method="post" id="deleteStorage">
                    <div class="modal-body" id="deleteStorageContainer">

                        <!-- Innhold fra Handlebars Template -->

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
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lagertelling</h4>
                </div>
                <form action="?page=stocktacking" method="post" id="stocktaking">

                    <div class="modal-body row" >
                        <div class="col-md-6">
                            <table class="table" id="stocktakingContainer">
                                <!-- Innhold fra Handlebars Template -->
                            </table>


                            <table class="table" id="stocktakingResultContainer">
                                <!-- Innhold fra Handlebars Template -->
                            </table>
                        </div>
                        <div class="col-md-6">
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
                        <label>Velg produkt(er) som skal inn på Hovedlageret</label>
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




</div>

<!-- Help modal -->


<div class="modal fade" id="showHelpModal" role="dialog">
    <div class="modal-dialog" style="width: 70%">
        <!-- Innholdet til Modalen -->
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
                    <img src="image/SøkLager.PNG">
                </div>
                


                <div class="col-md-12">
                    <h3>
                        Her oppretter du nye lager.
                    </h3>

                </div>
                <div class="col-md-12">
                    <img src="image/OpprettLager.PNG">
                </div>
                <div class="col-md-12">
                    <h3>
                        Skriv inn info om lageret du vil opprette.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/OpprettLagerModal.PNG">
                </div>
                
                <div class="col-md-12">
                    <h3>
                        Her kan du legge inn produkter på hovedlageret.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/Varelevering.PNG">
                </div>
                
                <div class="col-md-12">
                    <h3>
                        Velg hvor mange og hvilke produkter du ønsker å ta inn på hovedlageret.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/VareleveringModal.PNG">
                </div>
                <div class="col-md-12">
                    <h3>
                        Dette er en liste over alle lagerene i systemet.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/Lager.PNG">
                </div>
                <div class="col-md-12">
                    <h3>
                        Dette er alternativer for lager:<br>
                    </h3>
                    <label>
                        1. <img src="image/EndreBruker.PNG">Endre lager<br>
                        2. <img src="image/InformasjonBruker.PNG">Vis informasjon om lager<br>
                        3. <img src="image/SlettBruker.PNG">Slett lager<br>
                        4. <img src="image/Varetelling.PNG">Varetelling for lageret
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

<script id="stockDeliveryTemplate" type="text/x-handlebars-template">
    <br>  
    {{#each productInfo}} 
    <button data-id="{{productID}}" class="btn btn-default product">{{productName}}</button>
    {{/each}} 
</script>

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

<!-- Display stocktacing product-->
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
    <input form="stocktaking" name="oldQuantityArray[]" type="hidden" value="{{newQuantity}}"> 
    <input form="stocktaking" name="differanceArray[]" type="hidden" value="{{differance}}">
    {{/each}}

    <input form="stocktaking" name="givenStorageID" type="hidden" value="{{differanceArray.0.storageID}}">


</script>


<!-- Display stocktacing product-->
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

<!-- Display editStorage-->                    
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


<!-- Display StorageInformation-->
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
    <tr>
    <th class="col-md-6">Grense for epostvarsling: </th>
    <td>{{warningLimit}}</td>
    </tr>
    <th class="col-md-6">Lager kan gå i minus: </th>
    <td class="negativeSupportStatus" id="negativeSupportStatus"></td>
    </tr>
    {{/each}}                
</script>   

<!-- Display StorageRetricton-->
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

<!-- Display StorageProduct-->
<script id="storageProductTemplate" type="text/x-handlebars-template">
    {{#each storageProduct}}
    <tr>
    <td >
    <button id="redigerknapp" data-id="{{productID}}" class="deleteStorageInventory" data-toggle="tooltip" title="Fjern lagertilgang">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>
    </td>
    <th>{{productName}}</th>
    <td class="quantityColor"> {{quantity}}</td>

    </tr>
    {{/each}}    
</script>


<!-- Display what storage you are deleting-->
<script id="deleteStorageTemplate" type="text/x-handlebars-template">
    <p> Er du sikker på at du vil slette  <P>
    {{#each storage}}
    {{storageName}}
    <input type="hidden" form="deleteStorage" name="deleteStorageID" value="{{storageID}}">    
    {{/each}} 
</script>   



<!-- display all users template -->
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
    <span class="glyphicon glyphicon-check" style="color: #002E5F"></span>
    </button>
    </td>

    <!-- Printer ut lagernavn inn i tabellen -->

    <th>Lagernavn: </th>
    <td>{{storageName}}</td>

    {{/each}}
    </tr>


</script>



<script type="text/javascript" src="js/storageAdm.js"></script>   