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


        <!-- Shortcuts for much used funtion for administrators -->

    <?php if ($_SESSION["userLevel"] == "Administrator") { ?>
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

                                        <button class="btn btn-success btn-md" onclick="getCategoryInfo();" type="button" data-toggle="modal" data-target="#uploadImageModal"><span class="glyphicon glyphicon-picture"></span> <br/>Last opp bilde</button>
                                        <button class="btn btn-success btn-md" type="button" data-toggle="modal" data-target="#stocktakingModal"><span class="glyphicon glyphicon-flag"></span> <br/>Varetelling</button>
                                        <button class="btn btn-success btn-md" type="button" onclick="getStorageProduct();" data-toggle="modal" data-target="#stockDeliveryModal"><span class="glyphicon glyphicon-th-list"></span> <br/>Varelevering</button>

                                    </div>
                                    <div class="pull-right">
                                        <a href="?request=editUser" class="btn btn-warning btn-md" role="button"><span class="glyphicon glyphicon-user"></span> <br/>Rediger Profil</a>
                                        <a href="../" class="btn btn-danger btn-md" role="button"><span class="glyphicon glyphicon-log-out"></span> <br/>Logg ut</a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    <?php } ?>


        <!-- Administrator help modal -->
    <div class="container">
        <?php if ($_SESSION["userLevel"] == "Administrator") { ?> 
            <div class="row">
                <div class="pull-right">
                    <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
                    </label>
                </div>
            </div>

        <?php } ?>
        <!-- User help modal -->
        <?php if ($_SESSION["userLevel"] == "User") { ?> 
            <div class="row">
                <div class="pull-right">
                    <label data-target="#showHelpModalUser" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
                    </label>
                </div>
            </div>

        <!-- Storage inventory administrator-->
        <?php } ?>
        <div class="col-md-12">
            <?php if ($_SESSION["userLevel"] == "Administrator") { ?>
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
                                <!-- Content from lowInvContainer handlebars -->
                            </tbody>
                        </table>
                    </div>

                </div>
            
            <!-- Last sale user -->
            <?php } ?>
            <?php if ($_SESSION["userLevel"] == "User") { ?>
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
                                    <th>Produkt</th>
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
            <?php } ?>
            
            <!-- Your last sale -->
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
        
        <!-- Last bit of logg for administrators -->
        <?php if ($_SESSION["userLevel"] == "Administrator") { ?>
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
        <?php } ?>



        <!-- Help modal administrator -->



<div class="modal fade" id="showHelpModal" role="dialog">

        <div class="modal-dialog" style="width: 70%">
            <!-- Content of modal -->
            <div class="modal-content row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Hjelp</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 text-center">
                        <h3>
                            Her er forskjellige snarveier til ofte brukte funksjoner.
                        </h3>
                        <img style="width: 90%" src="image/Snarvei.PNG" alt="Snarvei knapper">
                    </div>
                    
                    <div class="col-md-6 text-center">
                        <h3>
                            Her kan du se hvilke produkter det er lite av og hvilke lager de ligger på.
                        </h3>
                        <img id="helpimage" src="image/SnartTomLagerbeholdning.PNG" alt="Snart tom lagerbeholdning">
                    </div>
                    <div class="col-md-6 text-center">
                        <h3>
                            Her kan du se dine siste salg.
                        </h3>
                        <img id="helpimage" src="image/DineSisteSalgAdmin.PNG" alt="Dine siste salg">
                    </div>
                    <div class="col-md-12 text-center">
                        <h3>
                            Her kan du se det siste som har blitt logget.
                        </h3>
                        <img id="" src="image/SisteHendelser.PNG" alt="Siste hendelser">
                    </div>
                    
                    <div class="col-md-6 text-center">
                        <h3>Her kan du se informasjon om produkter i lager du har tilgang til.
                            Om du har tilgang til flere lager vil det her være mulig å velge hvilke lager du vil se.
                        </h3>
                        <img id="helpimage" src="image/Lagerbeholdning.PNG" alt="Lagerbeholdning">
                    </div>
                    <div class="col-md-6 text-center">
                        <h3>Her kan du se en grafisk fremstilling av lagerbeholdningen.
                        </h3>
                        <img id="helpimage" src="image/LagerbeholdningGraf.PNG" alt="Graf av lagerbeholdning">


                    </div>
                    
                        <div class="modal-footer col-md-12">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    
        <!-- Help modal user -->


        <div class="modal fade" id="showHelpModalUser" role="dialog">
            <div class="modal-dialog" style="width: 70%">
                <!-- Conetent of the modal -->
                <div class="modal-content row">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Hjelp</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-6 text-center">
                            <label>Her kan du se de siste salgene gjort av alle brukerene i systemet.</label>
                            <img id="helpimage" src="image/SisteSalg.PNG" alt="Siste salg">
                        </div>
                        <div class="col-md-6 text-center">
                            <label>Her kan du se de siste salgene gjort av deg.</label>
                            <img id="helpimage" src="image/DineSisteSalg.PNG" alt="Dine siste salg">
                        </div>
                        <div class="col-md-6 text-center">
                            <label>Her kan du se informasjon om produkter i lager du har tilgang til.<br>
                                Om du har tilgang til flere lager vil det her være mulig å velge hvilke lager du vil se.
                            </label>
                            <img id="helpimage" src="image/Lagerbeholdning.PNG" alt="Lagerbeholdning">
                        </div>
                        <div class="col-md-6 text-center">
                            <label>Her kan du se en grafisk fremstilling av lagerbeholdningen.
                            </label>
                            <img id="helpimage" src="image/LagerbeholdningGraf.PNG" alt="lagerbeholdnings graf">
                        </div>


                    </div>
                    <div class="modal-footer col-md-12">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                    </div>

                </div>
            </div>
        </div> 


        <!-- StorageInventory -->

        <div class="col-md-12">
            <div class="col-md-6">

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title text-center"><b>Lagerbeholdning</b></h2>
                        <br>
                        <div id="chooseStorage">
                            <select name="fromStorageID" id="chooseStorageContainer" class="form-control">

                                <!-- Content from choosStorageContainer handlebar-->

                            </select>
                        </div>
                        <div id="singleStorageContainer">
                            <!-- Content from singleStorageContainer handlebars -->
                        </div>
                    </div>



                    <table class="table table-bordered fontSizeTableContainer">
                        <thead>
                            <tr>
                                <th>Produkt</th>
                                <th>Antall</th>
                            </tr>
                        </thead>

                        <tbody id="chosenStorageContainer">

                            <!-- Content from chosenStorageContainer handlebar -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Graph of storage inventory -->
            <div class="col-md-6">

                <canvas id="myChart" height="290"></canvas>


            </div>
        </div>
    </div>

                    

    <!-- Create user modal -->


    <div class="modal fade" id="createUserModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Opprett bruker</h4>
                </div>
                <form action="?request=addUserEngine" method="post" id="createUser">
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
                        
                        <!-- Create user button -->
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
            <!-- Content of Modal -->
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

                                </table>
                                </div>
                                </div>
                                <div class="modal-footer">
                                    <!-- Create product button -->
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
                    <!-- Content of Modal -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Opprett bruker</h4>
                        </div>
                        <div class="modal-body">
                            <form action="?request=addStorageEngine" method="post" id="createStorage">
                                <div style="text-align: center">
                                    <table class="table">                   
                                        <tr>
                                            <th id="bordernone">Lagernavn:</th>
                                            <td id="bordernone"><input class="form-control" type="text" required="required" name="givenStorageName" value=""></td>
                                        </tr>
                                        <tr>
                                            <th id="bordernone">Lager skal kunne gå i minus:</th>
                                            <td id="bordernone"><input  type="checkbox" name="givenNegativeSupport" value="1"></td>
                                        </tr>

                                    </table>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <!-- Create storage button -->
                            <input class="btn btn-success" form="createStorage" type="submit" value="Opprett Lager" href="?request=storageAdm">


                            <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                        </div>
                        </form>
                    </div>
                </div>
            </div> 

            <!-- UPLOAD IMAGE MODAL -->


            <div class="modal fade" id="uploadImageModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Content of Modal -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Last opp bilde</h4>
                        </div>
                        <div class="modal-body">
                            <div style="text-align: center">

                                <form action="?request=uploadImageShortcut" id="uploadImage" method="post" enctype="multipart/form-data">
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
                            <input class="btn btn-success" form="uploadImage" type="submit" value="Upload Image" name="submit" href="?request=uploadImage">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>  


            <!-- CREATE CATEGORY MODAL -->


            <div class="modal fade" id="createCategoryModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Content of Modal -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Opprett kateogri</h4>
                        </div>
                        <div class="modal-body">
                            <form action="?request=addCategoryEngine" method="post" id="createCategory">
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
                            <!-- Create category button -->
                            <input class="btn btn-success" form="createCategory" type="submit" value="Opprett Kategori">


                            <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                        </div>
                        </form>
                    </div>
                </div>
            </div>     

            


            <!-- STOCKTAKING MODAL -->

            <div class="modal fade" id="stocktakingModal" role="dialog">
                <div class="modal-dialog" style="width: 70%">
                    <!-- Content of Modal -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Varetelling</h4>
                        </div>
                        <form action="?request=stocktacking" method="post" id="stocktaking">

                            <div class="modal-body row" >
                                <div class="col-md-6">
                                    <label id="stocktakLabel">Velg lager: </label>

                                    <select name="onStorageID" id="chooseStorageStocktakContainer" class="form-control stocktaking marginBStorage">

                                        <!-- Content of choosStorageStocktakContainer handlebars-->

                                    </select>  

                                    <table class="table product" id="stocktakingContainer">
                                        <!-- Content of stocktakingContainer Handlebar -->
                                    </table>


                                    <table class="table" id="stocktakingResultContainer">
                                        <!-- Content of stocktakingResultContainer handlebars -->
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <!-- Graph of stocktaking result -->
                                    <canvas id="stocktakingResultChart"></canvas>
                                </div>


                            </div>

                            <div class="modal-footer">
                                <!-- Export to CSV document -->
                                <a href="#" id="saveToCSV" class="btn btn-success">Eksporter til csv</a>
                                <input form="stocktaking" class="btn btn-success" id="saveStocktaking" type="submit" value="Neste">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                            </div>
                        </form>    
                    </div>
                </div>
            </div>   

            <!-- Productdelivering -->
            
            <div class="modal fade" id="stockDeliveryModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Content of Modal -->
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
                                    <!-- Content of stockDeliveryContainer handlbars -->
                                </div>
                                
                                <br><br>
                                <div>
                                    <table class="table table-responsive" id="deliveryQuantityContainer">

                                        <!-- Content of deliveryQuantityContainer handlebars-->

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

            
            <!-- Choose category handlebar -->
            <script id="chooseCategoryTemplate" type="text/x-handlebars-template">
            <option data-id="0" value="0">Velg Kategori</option>
            {{#each category}}
            <option data-id="{{categoryID}}" value="{{categoryID}}">{{categoryName}}</option>
            {{/each}}
            </script>

            <!-- Delivery quantity handlebar -->
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

            <!-- Handlebar of display stocktacing product-->
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


          
            <!-- Handlebars of display stocktacing product-->
            <script id="stocktakingTemplate" type="text/x-handlebars-template">
                <h2>{{storageProduct.0.storageName}}</h2><br>  
                <input form="stocktaking" name="givenStorageID" type="hidden" value="{{storageProduct.0.storageID}}">
                <input form="stocktaking" name="getResult" type="hidden" value="getResult"> 
                {{#each storageProduct}}

                <tr>
                <th id="bordernone">{{productName}}:</th>    
                <input form="stocktaking" name="givenProductArray[]" type="hidden" value="{{productID}}">
                <input form="stocktaking" name="oldQuantityArray[]" type="hidden" value="{{quantity}}"> 
                <input form="stocktaking" name="givenProductNameArray[]" type="hidden" value="{{productName}}">            
                <td id="bordernone"><input class="form-control" type="int" required="required" name="givenQuantityArray[]" value="" autocomplete="off"></td>
                <th id="bordernone">Registrert verdi</th>
                <td id="bordernone">{{quantity}}</td>
                </tr>


                {{/each}} 

            </script>

            
            <!-- Handlebar of stock delivery -->
            <script id="stockDeliveryTemplate" type="text/x-handlebars-template">
                <br>  
                {{#each productInfo}} 
                <button data-id="{{productID}}" class="btn btn-primary product">{{productName}}</button>
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

            <script id="singleStorageTemplate" type="text/x-handlebars-template">

                {{#each transferRestriction}}    
                <h2 class="panel-title text-center"><b>{{storageName}}</b></h2>  
                {{/each}}       
            </script>



            <!-- Display storages in drop down meny Template -->
            <script id="chooseStorageStocktakTemplate" type="text/x-handlebars-template">
                <option data-id="0" value="0" class="withdrawStorage">Velg et lager</option>
                {{#each transferRestriction}}    
                <tr>
                <option data-id="{{storageID}}" value="{{storageID}}" class="stocktake">{{storageName}}</option>
                </tr>   
                {{/each}}

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

            <!-- Handlebar of chosen storage -->
            <script id="chosenStorageTemplate" type="text/x-handlebars-template">

                {{#each storageProduct}}
                <tr class="inventoryColor">
                <td class="inventoryColor">{{productName}}</td>
                <td class="inventoryColor">{{quantity}}</td>
                </tr>
                {{/each}} 
            </script>



            <!-- Handlebar of the log -->
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
                <th>Gruppenavn</th>    
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
                <td>{{typeName}}</td>
                <td>{{desc}}</td>
                <td>{{storageName}}{{deletedStorage}}</td>
                <td>{{toStorage}}</td>
                <td>{{fromStorage}}</td>
                <td>{{quantity}}</td>
                <td>{{oldQuantity}}</td>
                <td>{{newQuantity}}</td>
                <td>{{differential}}</td>  
                <td>{{groupName}}{{deletedGroup}}</td>     
                <td>{{username}}</td>
                <td>{{onUsername}}{{deletedUser}}</td>
                <td>{{productName}}{{deletedProduct}}</td>
                <td>{{customerNr}}</td>
                <td>{{date}}</td>
                </tr>
                </tbody>
                {{/each}}


            </script>


            <!-- Handlebar for low inventory -->
            <script id="lowInvTemplate" type="text/x-handlebars-template">


                {{#each lowInv}}
                <tr>
                <td class="quantityColor">{{productName}}</td>
                <td class="quantityColor">{{quantity}}</td>
                <td class="quantityColor" >{{storageName}}</td>
                <tr>

                {{/each}}


            </script>




<!-- Handlebar for last sale -->
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

        <!-- Handlebars for all last sales -->
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


            <script>
                // STOCKTAKING OF STORAGE -- >
                // stocktaking modal -- >

                $(function chooseStorageStocktakContainer() {

                    $('#chooseStorageStocktakContainer').on('change', function () {
                        var givenStorageID = $(this).find("option:selected").data('id');

                        if (givenStorageID > 0) {

                            $.ajax({
                                type: 'POST',
                                url: '?request=getStorageProduct',
                                data: {givenStorageID: givenStorageID},
                                dataType: 'json',
                                success: function (data) {
                                    stocktakingTemplate(data);
                                }
                            });
                        } else {
                            $('.product').empty();
                        }
                        return false;
                    });
                });



                // POST results from stocktaking, and updating the table-- >

                $(function POSTstocktakingResult() {

                    $('#stocktaking').submit(function () {
                        var url = $(this).attr('action');
                        var data = $(this).serialize();
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            dataType: 'json',
                            success: function (data) {
                                if (document.getElementById("saveStocktaking").value === "Lagre") {
                                    var $displayUsers = $('#stocktakingResultContainer');
                                    $displayUsers.empty();
                                    document.getElementById("saveStocktaking").value = "Neste";
                                    $('#stocktakingModal').modal('hide');
                                    $('#stocktakLabel').show();
                                    $('#chooseStorageStocktakContainer').show();
                                } else {
                                    $('#stocktakLabel').hide();
                                    $('#chooseStorageStocktakContainer').hide();
                                    var $displayUsers = $('#stocktakingContainer');
                                    $displayUsers.empty();
                                    $('a#saveToCSV').show();
                                    document.getElementById("saveStocktaking").value = "Lagre";
                                    stocktakingResultTemplate(data);
                                    rowColor();
                                    stocktakingResultChart(data);
                                }

                            }

                        });
                        return false;
                    });
                });
            </script>
            
            <!-- Destroy graph if allready exist -->
            <script>
                $(document).ready(function ()
                {
                    $('#stocktakingModal').on('hidden.bs.modal', function (e)
                    {
                        if (resultBar)
                        {
                            resultBar.destroy();
                        }
                        $('#stocktakLabel').show();
                        $('#chooseStorageStocktakContainer').show();
                        $('#stocktakingResultContainer').empty();
                        $('#stocktakingContainer').empty();
                        $('#stocktakingResultChart').empty();
                        document.getElementById("saveStocktaking").value = "Neste";
                        $('a#saveToCSV').hide();
                        $('#chooseStorageStocktakContainer').prop('selectedIndex', 0);
                    });
                });
            </script>
            <script>
                                    
                 // Display storage template -->
$( function getUsedStorageCat() {
    var givenStorageID = '2';
    $.ajax({
            type: 'POST',
            url: '?request=getCatWithProd',
            data: {givenStorageID: givenStorageID},
            dataType: 'json',
            success: function (data) {
                chooseCategory(data);
            }
        });
    return false;
});
            </script>
            
<script>
function chooseCategory(data) {
    var rawTemplate = document.getElementById("chooseCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var productTableGeneratedHTML = compiledTemplate(data);
    var productContainer = document.getElementById("chooseCategoryContainer");
    productContainer.innerHTML = productTableGeneratedHTML;
}
</script>
<script>
$(function updateResultFromCategory() {

    $('#chooseCategoryContainer').on('change', function () {
        givenCategoryID = $(this).find("option:selected").data('id');

        $.ajax({
            type: 'POST',
            url: '?request=getProductFromCategory',
            data: {givenCategoryID: givenCategoryID},
            dataType: 'json',
            success: function (data) {
                stockDeliveryTemplate(data);
            }
        });
        return false;
    });
});
      </script>      
            <script type="text/javascript" src="js/home.js"></script>   

