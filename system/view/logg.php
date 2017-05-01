<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <div class="container">
<div class="row">
        <div class="pull-right">
            <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
            </label>
        </div>
    </div>
        <div class="col-md-6 row">
            

            <form id="searchForLog" class="form-inline" action="?page=getAllLoggInfo" method="post">

                
                
                    <input class="form-control" form="searchForLog" type="text" name="givenLogSearchWord" value="" placeholder="Søk etter hendelse.." autocomplete="off" style="margin-left: 4%">  
                    <input class="form-control btn btn-primary" form="searchForLog" type="submit" value="Søk">

                    <button onclick="updateLogTable()" class="btn btn-primary " type="button">All Logg</button>

            </form>
        
                    
        </div>
        <button  class="btn btn-primary" onclick="toggler();">Avansert søk</button>
        
        
         <a href="#" id="loggToCSV" class="btn btn-success">Eksporter til csv</a>   
            
        <div class="dropdown col-md-1 pull-right" id="loggCheckMenu">

                    <button class="btn btn-secondary dropdown-toggle" type="button" id="redigerknapp" data-toggle="dropdown" data-target="#loggCheckMenu">
                        <span class="glyphicon glyphicon-cog" style="color: #0f0f0f; font-size: 2em;"></span>
                    </button>

                    <ul class="dropdown-menu">
                        <li class="dropdown-header"><h4>Velg hvilke hendelser som blir logget</h4></li>
                        <form id="loggCheck" class="form-inline" action="?page=loggCheck" method="post">
                            <table class="table">
                                <input type='hidden' value='0' name='Redigering'>
                                <tr>
                                    <td id="bordernone" class="">Redigering</td>
                                    <td id="bordernone"><input id="edit" type="checkbox" name="Redigering" value="1"></td>
                                </tr>                                
                                <input type='hidden' value='0' name='Innlogging'>
                                <tr>
                                    <td id="bordernone">Innlogging</td>
                                    <td id="bordernone"><input id="login" type="checkbox" name="Innlogging" value="1"></td>
                                </tr>
                                <input type='hidden' value='0' name='Tilgang'>
                                <tr>
                                    <td id="bordernone">Tilgang</td>
                                    <td id="bordernone"><input id="restriction" type="checkbox" name="Tilgang" value="1"></td>
                                </tr>
                                <input type='hidden' value='0' name='Opprettelse'>
                                <tr>
                                    <td id="bordernone">Opprettelse</td>
                                    <td id="bordernone"><input id="creation" type="checkbox" name="Opprettelse" value="1"></td>
                                </tr>
                                <input type='hidden' value='0' name='Varelevering'>
                                <tr>
                                    <td id="bordernone">Varelevering</td>
                                    <td id="bordernone"><input id="stockdelivery" type="checkbox" name="Varelevering" value="1"></td>
                                </tr>
                                <input type='hidden' value='0' name='Uttak'>
                                <tr>
                                    <td id="bordernone">Uttak</td>
                                    <td id="bordernone"><input id="sale" type="checkbox" name="Uttak" value="1"></td>
                                </tr>
                                <input type='hidden' value='0' name='Retur'>
                                <tr>
                                    <td id="bordernone">Retur</td>
                                    <td id="bordernone"><input id="return" type="checkbox" name="Retur" value="1"></td>
                                </tr>
                                <input type='hidden' value='0' name='Overføring'>
                                <tr>
                                    <td id="bordernone">Overføring</td>
                                    <td id="bordernone"><input id="transfer" type="checkbox" name="Overføring" value="1"></td>
                                </tr>
                                <input type='hidden' value='0' name='Sletting'>
                                <tr>
                                    <td id="bordernone">Sletting</td>
                                    <td id="bordernone"><input id="deleting" type="checkbox" name="Sletting" value="1"></td>
                                </tr>
                                <input type='hidden' value='0' name='Varetelling'>
                                <tr>
                                    <td id="bordernone">Varetelling</td>
                                    <td id="bordernone"><input id="stocktaking" type="checkbox" name="Varetelling" value="1"></td>
                                </tr>
                            </table>
                            <input class="btn btn-success pull-right" type="submit" form="loggCheck"  value="Lagre" style="margin-right: 2%">
                        </form>
                    </ul>


                </div>
            
        
         <form id="advanceLoggSearch" class="form-group form-inline" action="?page=advanceLoggSearch" method="post">
                <div id="advanceSearch" class="advanceSearchToogle" style="margin-top: 2%">
                    
                        <div class="dropdown form-group"id="typeSearch">
                            <button class="btn btn-info dropdown-toggle" type="button"  data-toggle="dropdown">Velg Type</button>
                            
                            <div class="dropdown-menu">
                                <li class="dropdown-header"><h4>Velg type:</h4></li>
                                <table class="table" id="typeContainer">
                                
                                </table>
                            </div>
                        </div>
                    
                    
                    
                    <div class="dropdown form-group" id="storageSearch">
                        <button class="btn btn-info dropdown-toggle" type="button"  data-toggle="dropdown">
                            Velg Lager
                        </button>
                        <div class="dropdown-menu">
                            <li class="dropdown-header"><h4>Velg Lager:</h4></li>
                                <table class="table" id="storageContainer">
                        
                                </table>
                        </div>
                    </div>

                    <div class="dropdown form-group" id="toStorageSearch">
                        <button class="btn btn-info dropdown-toggle" type="button"  data-toggle="dropdown">
                            Velg Til Lager
                        </button>
                        <div class="dropdown-menu">
                            
                            <li class="dropdown-header"><h4>Velg Til Lager:</h4></li>
                                <table class="table" id="toStorageContainer">
                        
                                </table>
                        </div>
                    </div>

                    <div class="dropdown form-group" id="fromStorageSearch">
                        <button class="btn btn-info dropdown-toggle" type="button"  data-toggle="dropdown">
                            Velg Fra Lager
                        </button>
                        <div class="dropdown-menu">
                        
                            <li class="dropdown-header"><h4>Velg Fra Lager:</h4></li>
                                <table class="table" id="fromStorageContainer">
                        
                                </table>
                        </div>
                    </div>

                    <div class="dropdown form-group" id="usernameSearch">
                        <button class="btn btn-info dropdown-toggle" type="button"  data-toggle="dropdown">
                            Velg Bruker
                        </button>
                        <div class="dropdown-menu">
                       
                            
                            <li class="dropdown-header"><h4>Velg Bruker:</h4></li>
                                <table class="table" id="usernameContainer">
                        
                                </table>
                        </div>
                    </div>

                    <div class="dropdown form-group" id="onUserSearch">
                        <button class="btn btn-info dropdown-toggle" type="button"  data-toggle="dropdown">
                            Velg På Bruker
                        </button>
                        <div class="dropdown-menu">
              
                            <li class="dropdown-header"><h4>Velg På Bruker:</h4></li>
                                <table class="table" id="onUserContainer">
                        
                                </table>
                        </div>
                    </div> 

                    <div class="dropdown form-group" id="productSearch">
                        <button class="btn btn-info dropdown-toggle" type="button"  data-toggle="dropdown">
                            Velg Produkt
                        </button>
                        <div class="dropdown-menu">
                            <li class="dropdown-header"><h4>Velg Produkt:</h4></li>
                                <table class="table" id="productContainer">
                        
                                </table>
                        </div>
                    </div>     


                    <input class="form-control" type="text" name="date" value="" placeholder="Velg dato"/>

                    <input class="form-control btn btn-primary" type="submit" form="advanceLoggSearch"  value="Søk">
                    
                </div>

            </form>
            
            
            




        <br><br>



        <table class="table fontSizeTableContainer" id="loggTableContainer">
            <!-- Innhold fra Handlebars Template -->
        </table>

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
                    <div class="col-md-12">
                        <h3>
                            Bruk denne funksjonen for å søke i loggen.<br>
                            
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                <img src="image/SøkLogg.PNG">
                    </div>
                
                
                    <div class="col-md-12">
                        <h3>
                            Trykk her om du ønsker flere alternativer når du søker i loggen.
                        </h3>
                        
                    </div>
                <div class="col-md-12">
                    <img src="image/AvansertSøkLogg.PNG">
                    </div>
                <div class="col-md-12">
                    <h3>
                        Her kan du spesifisere søket ditt med mange forskjellige alternativer.
                    </h3>
                </div>
                <div class="col-md-12">
                <img src="image/AvansertSøkAlternativ.PNG">
                </div>
                    <div class="col-md-12">
                    <h3>
                        Trykk her for logginstillinger.
                    </h3>
                </div>
                <div class="col-md-12">
                <img src="image/Logging.PNG">
                </div>
                    <div class="col-md-12">
                    <h3>
                        Her kan du velge hva som skal bli logget i systemet.
                    </h3>
                </div>
                <div class="col-md-12">
                <img src="image/VelgLogging.PNG">
                </div>
                    <div class="col-md-12">
                    <h3>
                        Her vises loggen.
                    </h3>
                </div>
                <div class="col-md-12">
                <img src="image/Logg.PNG">
                </div>
                    
                
                   
                    
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
            </div>
        </div>
        
</div>


<script id="loggTableTemplate" type="text/x-handlebars-template">
    <thead>
    <tr>
    <th>Type</th>
    <th>Beskrivelse</th>
    <th>Lagernavn</th>
    <th>Til lager</th>
    <th>Fra lager</th>    
    <th>Antall</th>
    <th>Gammelt Antall</th>
    <th>Nytt Antall</th>
    <th>Differanse</th>
    <th>Brukernavn</th>
    <th>På bruker</th>
    <th>Produkt</th>    
    <th>KundeNr</th>
    <th>Dato</th>            
    </tr>
    </thead>
    <tbody id="tbodyid">
    {{#each allLoggInfo}}
    <tr>
    <td>{{typeName}}</td>
    <td>{{desc}}</td>
    <td>{{storageName}}{{deletedStorage}}</td>
    <td>{{toStorage}}</td>
    <td>{{fromStorage}}</td>
    <td>{{quantity}}</td>
    <td>{{oldQuantity}}</td>
    <td>{{newQuantity}}</td>
    <td>{{differential}}</td>  
    <td>{{username}}</td>
    <td>{{onUsername}}{{deletedUser}}</td>
    <td>{{productName}}{{deletedProduct}}</td>
    <td>{{customerNr}}</td>
    <td>{{date}}</td>
    </tr>
    </tbody>
    {{/each}}
</script>

<script type="text/javascript" src="js/logg.js"></script>

<script type="text/javascript">
    $(function () {

        $('input[name="date"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="date"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' / ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('input[name="date"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

    });
</script>   