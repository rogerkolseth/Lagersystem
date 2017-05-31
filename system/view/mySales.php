<?php require("view/header.php"); ?>

<!-- adjusting page content position-->
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <!-- creates a container around page content-->
    <div class="container">

        <!-- Show help modal button administrator -->

        <!-- if users userlevel is Administrator, show help modal for admin page-->

        <?php if ($_SESSION["userLevel"] == "Administrator") { ?>
            <div class="row">
                <div class="pull-right">
                    <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
                    </label>
                </div>
            </div>
        <?php } ?>

        <!-- Show help modal button for users -->


        <!-- If users userlevel is user, show help modal for user-->

        <?php if ($_SESSION["userLevel"] == "User") { ?>

            <div class="row">
                <div class="pull-right">
                    <label data-target="#showHelpModalUser" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
                    </label>
                </div>
            </div>
        <?php } ?>

        <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group">
            <!-- Search for sales -->
            <form id="searchForSale" class="form-inline" action="?request=getMySales" method="post">
                <div class="form-group col-md-6 row">
                    <div class="">
                        <input class="form-control" form="searchForSale" type="text" name="givenProductSearchWord" value="" placeholder="Søk etter salg.." autocomplete="off">  
                        <input class="form-control btn btn-primary" form="searchForSale" type="submit" value="Søk">
                        <!-- Refresh search -->
                        <button onclick="UpdateSalesTable()" class="btn btn-primary " type="button">Alle salg</button>

                    </div>

                </div> 
            </form>
            
            <!-- Choose user to see sales -->
            <?php if ($_SESSION["userLevel"] == "Administrator") { ?>

            <div class="col-md-2 pull-right">
                <form id="showUserSale" action="?request=showUserSale" method="post">
                    <div class="dropdown form-group" id="UserSaleSearch">
                        <button class="btn btn-info dropdown-toggle" type="button"  data-toggle="dropdown">
                            Velg brukere
                        </button>
                        <div class="dropdown-menu">

                            <li class="dropdown-header"><h4>Velg Bruker:</h4></li>
                            <table class="table" id="chooseUserSaleContainer">
                                <!-- Content of chooseUserSaleContainer handlebars -->
                            </table>
                        </div>
                    </div> 
                </form>
            </div>



            <?php } ?>

            <br><br><br>


            <!-- List of my sales -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><b>Dine Salg</b></h3> 
                </div>
                <table class="table table-responsive"> 

                    <tbody id="mySalesContainer">

                        <!-- Content of mySalesContainer handlebars  -->

                    </tbody>
                </table> 
            </div>
        </div>    
    </div>    

    <!-- Edit sales modal -->
    <div class="modal fade" id="editSaleModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Salgsinformasjon</h4>
                </div>
                <form action="?request=editMySale" method="post" id="editSale"> 

                    <div class="modal-body">
                        <table class="table" id="editSaleContainer">


                            <!-- Content of editSaleContainer handlebars -->

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

    
    
    <!-- Mac sales modal -->
     <div class="modal fade" id="macSaleModal" role="dialog">

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
                                    <th>Følgende Macadresse ble registrert ut:</th>
                                </tr>
                            </thead>
                            <tbody id="macSaleContainer">
                                <!-- Content of macSaleContainer -->
                            </tbody>
                        </table>
                    </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Help modal admin -->

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
                            Bruk denne funksjonen for å søke deg frem til salg du ønsker å se.
                        </h3>

                    </div>
                    <div class="col-md-12">
                        <img src="image/SøkSalg.PNG" alt="Søk i salg">
                    </div>
                    <div class="col-md-12">
                        <h3>
                            Her kan du velge en eller flere brukere for å se deres salg.
                        </h3>

                    </div>
                    <div class="col-md-12">
                        <img src="image/VelgBrukerSalg.PNG" alt="Velg bruker">
                    </div>
                    <div class="col-md-12">
                        <h3>
                            Velg hvilke brukere du vil se salg fra.
                        </h3>

                    </div>
                    <div class="col-md-12">
                        <img src="image/DropdownSalg.PNG" alt="Dropdown med brukere">
                    </div>
                    <div class="col-md-12">
                        <h3>
                            I denne boksen ser du resultatene fra valgene dine.<br> Hvis du ikke har søkt på noe eller valgt noen bruker vil den vise dine siste salg.
                        </h3>

                    </div>
                    <div class="col-md-12">
                        <img src="image/DineSalgSalg.PNG" alt="Liste over salg">
                    </div>
                    <div class="col-md-12">

                    <h3>
                        Dette er alternativer for salg:<br>
                    </h3>
                    <label>
                        <img src="image/EndreBruker.PNG" alt="Endre salg symbol">Endre salg<br>
                        
                    </label>
                </div>
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                </div>
            </div>
        </div>
    </div>

    <!-- Help modal bruker -->

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
                            Bruk denne funksjonen for å søke deg frem til salg du ønsker å se.
                        </h3>

                    </div>
                    <div class="col-md-12">
                        <img src="image/SøkSalg.PNG" alt="Søk etter salg">
                    </div>


                    <div class="col-md-12">
                        <h3>
                            I denne boksen ser du resultatene fra valgene dine.<br> Hvis du ikke har søkt på noe eller valgt noen bruker vil den vise dine siste salg.
                        </h3>

                    </div>
                    <div class="col-md-12">
                        <img src="image/DineSalgSalg.PNG" alt="Liste over salg">
                    </div>
                    <div class="col-md-12">

                    <h3>
                        Dette er alternativer for salg:<br>
                    </h3>
                    <label>
                        <img src="image/EndreBruker.PNG" alt="Endre salg symbol">Endre salg<br>
                        
                    </label>
                </div>
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                </div>
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

    <!-- List of my sales -->
    <script id="mySalesTemplate" type="text/x-handlebars-template">        
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
        {{#each mySales}}  
        <tr>
        <td>{{customerNr}}</td>        
        <td>{{productName}}{{deletedProduct}}</td>
        <td>{{storageName}}{{deletedStorage}}</td>
        <td>{{quantity}}</td>    
        <td>{{comment}}</td>  
        <td>{{username}}</td>     
        <td>{{date}}</td>   
        <td><button id="redigerknapp" data-id="{{salesID}}" class="editSales" data-toggle="tooltip" title="Rediger salg">
        <span class="glyphicon glyphicon-edit" style="color: green"></span>
        </button>
        {{#if_eq this.macAdresse "1"}}
        <button id="redigerknapp" data-id="{{salesID}}" class="showMac" data-toggle="tooltip" title="Rediger salg">
        <span class="glyphicon glyphicon-th-list" style="color: #003366"></span>
        </button>
        {{/if_eq}}</td>   
        <tr>
        {{/each}}
    </script>  

    <script type="text/javascript" src="js/mySales.js"></script>