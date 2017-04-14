<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <div class="container">

        <div class="col-sm-3 col-sm-offset-1 col-md-11 col-md-offset-1 form-group">

            <form id="searchForLog" class="form-inline" action="?page=getAllLoggInfo" method="post">
                <div class="form-group">
                    <div class="col-md-12">
                        <input class="form-control" form="searchForLog" type="text" name="givenLogSearchWord" value="" placeholder="Søk etter hendelse.." autocomplete="off">  
                        <input class="form-control btn btn-primary" form="searchForLog" type="submit" value="Søk">

                        <button onclick="updateLogTable()" class="btn btn-primary " type="button">All Logg</button>
                    </div>

                </div> 
                <a href="#" id="loggToCSV" class="btn btn-success">Eksporter til csv</a>
            </form>

            <button class="btn btn-secondary dropdown-toggle" type="button" id="redigerknapp" data-toggle="dropdown" data-target="#loggCheckMenu" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-cog" style="color: grey"></span>
            </button>

            <div class="dropdown" id="loggCheckMenu">

                <div class="dropdown-menu" aria-labelledby="redigerknapp">
                    <h4>Velg hvilke hendelser som blir logget </h4>
                    <ul style="list-style-type:circle">
                        <form id="loggCheck" class="form-inline" action="?page=loggCheck" method="post">
                            <input type='hidden' value='0' name='Redigering'>
                            <li>Redigering <input id="edit" type="checkbox" name="Redigering" value="1"></li>

                            <input type='hidden' value='0' name='Innlogging'>
                            <li>Innlogging <input id="login" type="checkbox" name="Innlogging" value="1"></li>

                            <input type='hidden' value='0' name='Tilgang'>
                            <li>Tilgang <input id="restriction" type="checkbox" name="Tilgang" value="1"></li>

                            <input type='hidden' value='0' name='Opprettelse'>
                            <li>Opprettelse <input id="creation" type="checkbox" name="Opprettelse" value="1"></li>

                            <input type='hidden' value='0' name='Varelevering'>
                            <li>Varelevering <input id="stockdelivery" type="checkbox" name="Varelevering" value="1"></li>

                            <input type='hidden' value='0' name='Uttak'>
                            <li>Uttak <input id="sale" type="checkbox" name="Uttak" value="1"></li>

                            <input type='hidden' value='0' name='Retur'>
                            <li>Retur <input id="return" type="checkbox" name="Retur" value="1"></li>

                            <input type='hidden' value='0' name='Overføring'>
                            <li>Overføring <input id="transfer" type="checkbox" name="Overføring" value="1"></li>

                            <input type='hidden' value='0' name='Sletting'>
                            <li>Sletting <input id="deleting" type="checkbox" name="Sletting" value="1"></li>

                            <input type='hidden' value='0' name='Varetelling'>
                            <li>Varetelling <input id="stocktaking" type="checkbox" name="Varetelling" value="1"></li>

                            <input class="form-control btn btn-primary" type="submit" form="loggCheck"  value="Lagre">

                        </form>
                    </ul>
                </div>
            </div>


            <button  class="btn btn-primary" onclick="toggler();">Avansert søk</button>
            
            <form id="advanceLoggSearch" class="form-inline" action="?page=advanceLoggSearch" method="post">
                <div id="advanceSearch" class="advanceSearchToogle">
                <div class="dropdown" id="typeSearch">
                    <button class="btn btn-secondary dropdown-toggle" type="button"  data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                        Velg Type
                    </button>
                    <div class="dropdown-menu" aria-labelledby="redigerknapp">
                        <h4>Velg type:</h4>
                        <ul style="list-style-type:circle" id="typeContainer">

                        </ul>
                    </div>
                </div>

                <div class="dropdown" id="storageSearch">
                    <button class="btn btn-secondary dropdown-toggle" type="button"  data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                        Velg Lager
                    </button>
                    <div class="dropdown-menu" aria-labelledby="redigerknapp">
                        <h4>Velg Lager:</h4>
                        <ul style="list-style-type:circle" id="storageContainer">

                        </ul>
                    </div>
                </div>
                
                <div class="dropdown" id="toStorageSearch">
                    <button class="btn btn-secondary dropdown-toggle" type="button"  data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                        Velg Til Lager
                    </button>
                    <div class="dropdown-menu" aria-labelledby="redigerknapp">
                        <h4>Velg Til Lager:</h4>
                        <ul style="list-style-type:circle" id="toStorageContainer">

                        </ul>
                    </div>
                </div>
                
                <div class="dropdown" id="fromStorageSearch">
                    <button class="btn btn-secondary dropdown-toggle" type="button"  data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                        Velg Fra Lager
                    </button>
                    <div class="dropdown-menu" aria-labelledby="redigerknapp">
                        <h4>Velg Fra Lager:</h4>
                        <ul style="list-style-type:circle" id="fromStorageContainer">

                        </ul>
                    </div>
                </div>
                    
                <div class="dropdown" id="usernameSearch">
                    <button class="btn btn-secondary dropdown-toggle" type="button"  data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                        Velg bruker
                    </button>
                    <div class="dropdown-menu" aria-labelledby="redigerknapp">
                        <h4>Velg bruker:</h4>
                        <ul style="list-style-type:circle" id="usernameContainer">

                        </ul>
                    </div>
                </div>
                    
                <div class="dropdown" id="onUserSearch">
                    <button class="btn btn-secondary dropdown-toggle" type="button"  data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                        Velg På Bruker
                    </button>
                    <div class="dropdown-menu" aria-labelledby="redigerknapp">
                        <h4>Velg På Bruker:</h4>
                        <ul style="list-style-type:circle" id="onUserContainer">

                        </ul>
                    </div>
                </div> 
                    
                <div class="dropdown" id="dateSearch">
                    <button class="btn btn-secondary dropdown-toggle" type="button"  data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                        Velg Periode
                    </button>
                    <div class="dropdown-menu" aria-labelledby="redigerknapp">
                        <h4>Velg Periode:</h4>
                        <ul style="list-style-type:circle" id="dateContainer">

                        </ul>
                    </div>
                </div>    
                <input class="form-control btn btn-primary" type="submit" form="advanceLoggSearch"  value="Søk">

                </div>

            </form>

            
            
             
            <br><br>


        </div>
        <table class="table fontSizeTableContainer" id="loggTableContainer">
            <!-- Innhold fra Handlebars Template -->
        </table>

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

<script src="js/logg.js"></script>