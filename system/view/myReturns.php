<?php require("view/header.php"); ?>


<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<!-- Help modal button for administrator -->
   <div class="container">
       <?php if ($_SESSION["userLevel"] == "Administrator") { ?>
        <div class="row">
        <div class="pull-right">
            <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png" alt="Spørsmålstegn knapp"></span>
            </label>
        </div>
    </div>
        <?php } ?>
       <!-- Help modal button for users -->
       <?php if ($_SESSION["userLevel"] == "User") { ?>
        <div class="row">
        <div class="pull-right">
            <label data-target="#showHelpModalUser" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png" alt="Spørsmålstegn knapp"></span>
            </label>
        </div>
    </div>
        <?php } ?>

    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group"> 
        <!-- Search for returns -->
        <form id="searchForReturns" class="form-inline" action="?request=getMyReturns" method="post">
            <div class="form-group col-md-6 row">
                <div class="">
                    <input class="form-control" form="searchForReturns" type="text" name="givenProductSearchWord" value="" placeholder="Søk etter returer.." autocomplete="off">  
                    <input class="form-control btn btn-primary" form="searchForReturns" type="submit" value="Søk">
                    
                    <button onclick="UpdateReturnsTable()" class="btn btn-primary " type="button">Alle Returer</button>
                    
                </div>
                
            </div> 
        </form>
        
        
        <!-- Choose user to see returns -->
        <?php if ($_SESSION["userLevel"] == "Administrator") { ?>
        <div class="col-md-2 pull-right">
                <form id="showUserReturn" action="?request=showUserReturns" method="post">
                    <div class="dropdown form-group" id="UserSaleSearch">
                        <button class="btn btn-info dropdown-toggle" type="button"  data-toggle="dropdown">
                            Velg brukere
                        </button>
                        <div class="dropdown-menu">

                            <li class="dropdown-header"><h4>Velg Bruker:</h4></li>
                            <table class="table" id="chooseUserReturnContainer">
                                <!-- Content of chooseUserReturnContainer handlebars -->
                            </table>
                        </div>
                    </div> 
                </form>

        </div>
            <?php } ?>
        
        <br><br><br>
        
        <!-- My returns list -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><b>Mine Returer</b></h3>
            </div>
         <table class="table table-responsive"> 
            
            <tbody id="myReturnsContainer">

                <!-- Content of myReturnsContainer handlebars -->

            </tbody>
        </table> 
            </div>
        
        </div>
   </div>
</div>  

<!-- Edit Returns modal -->
<div class="modal fade" id="editReturnsModal" role="dialog">
    <div class="modal-dialog">
        <!-- Content of the modal -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Returinformasjon</h4>
            </div>
            <form action="?request=editMyReturn" method="post" id="editReturn"> 
            
            <div class="modal-body">
                <table class="table" id="editReturnContainer">
                    

                <!-- Content of editReturnContainer handlebars -->
                    
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


    <!-- Mac returns modal -->
     <div class="modal fade" id="macReturnsModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Macadresse oversikt</h4>
                </div>

                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Følgende Macadresse ble registrert inn:</th>
                                </tr>
                            </thead>
                            <tbody id="macReturnsContainer">
                                <!-- Content of macReturnsContainer -->
                                
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                    </div>
            </div>
        </div>
    </div>

<!-- Help modal admin-->

<div class="modal fade" id="showHelpModal" role="dialog">
        <div class="modal-dialog" style="width: 70%">
            <!-- Innholdet til Modalen -->
            <div class="modal-content row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Hjelp</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h3>
                            Bruk denne funksjonen for å søke deg frem til returer du ønsker å se.
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                        <img src="image/SøkRetur.PNG" alt="Søk etter returer">
                    </div>
                    <div class="col-md-12">
                        <h3>
                            Her kan du velge en eller flere brukere for å se deres returer.
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                        <img src="image/VelgBrukerSalg.PNG" alt="Velg brukers returer">
                    </div>
                    <div class="col-md-12">
                        <h3>
                            Velg hvilke brukere du vil se returer fra.
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                        <img src="image/DropdownSalg.PNG" alt="Liste med brukere">
                    </div>
                    <div class="col-md-12">
                        <h3>
                            I denne boksen ser du resultatene fra valgene dine.<br> Hvis du ikke har søkt på noe eller valgt noen bruker vil den vise dine returer.
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                        <img src="image/RegReturer.PNG" alt="Liste over returer">
                    </div>
                    <div class="col-md-12">
                    <h3>
                        Dette er alternativer for returer:<br>
                    </h3>
                    <label>
                        <img src="image/EndreBruker.PNG" alt="Endre retur symbol">Endre retur<br>
                        
                    </label>
                </div>
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
        </div>
        </div>
</div>


<!-- Help modal bruker-->

<div class="modal fade" id="showHelpModalUser" role="dialog">
        <div class="modal-dialog" style="width: 70%">
            <!-- Innholdet til Modalen -->
            <div class="modal-content row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Hjelp</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h3>
                            Bruk denne funksjonen for å søke deg frem til returer du ønsker å se.
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                        <img src="image/SøkRetur.PNG" alt="Søk etter returer">
                    </div>
                    
                    
                    <div class="col-md-12">
                        <h3>
                            I denne boksen ser du resultatene fra søket ditt.<br> Hvis du ikke har søkt på noe vil den vise alle returene dine.
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                        <img src="image/RegReturer.PNG" alt="Liste over returer">
                    </div>
                    <div class="col-md-12">
                    <h3>
                        Dette er alternativer for returer:<br>
                    </h3>
                    <label>
                        <img src="image/EndreBruker.PNG" alt="Endre retur symbol">Endre retur<br>
                        
                    </label>
                </div>
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
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
    
   <!-- Handlebars for returns list -->
<script id="myReturnsTemplate" type="text/x-handlebars-template">        
<tr>
    <th>KundeNr</th>        
    <th>Produkt</th>
    <th>Lager</th>     
    <th>Antall</th>
    <th>Kommentar</th>
    <th>Brukernavn</th>
    <th>Dato</th>   
    <th></th> 
</tr>        
{{#each myReturns}}  
<tr>
    <td>{{customerNr}}</td>        
    <td>{{productName}}{{deletedProduct}}</td>
    <td>{{storageName}}{{deletedStorage}}</td>
    <td>{{quantity}}</td>    
    <td>{{comment}}</td>  
    <td>{{username}}</td>     
    <td>{{date}}</td>  
    <td><button id="redigerknapp" data-id="{{returnID}}" class="editReturns" data-toggle="tooltip" title="Rediger retur">
    <span class="glyphicon glyphicon-edit" style="color: green"></span>
    </button> 
     {{#if_eq this.macAdresse "1"}}
     <button id="redigerknapp" data-id="{{returnID}}" class="showMac" data-toggle="tooltip" title="Rediger salg">
        <span class="glyphicon glyphicon-th-list" style="color: #003366"></span>
     </button>
     {{/if_eq}}</td>      
<tr>
{{/each}}
</script>  


<script type="text/javascript" src="js/myReturns.js"></script>