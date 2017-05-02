<?php require("view/header.php");?>

<?php
if (isset($GLOBALS["transferRestriction"])){
$restriction = $GLOBALS["transferRestriction"];
}
?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <div class="container">
        
        <div class="row">
        <div class="pull-right">
            <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
            </label>
        </div>
    </div>
        
        <?php 
                if (isset($GLOBALS["transferRestriction"]) && $restriction == "1"){?>        
        
        
        <h2 class="text-center">Overføring</h2>
    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group">
        <div id="error"></div>
        <div id="success"></div>
        
    <form id="transferProducts" action="?page=transferProduct" method="post">    
        
        <div class="col-sm-3 col-md-4 row">
            <h4><b>Overfør Fra:</b></h4>
            <select name="fromStorageID" form="transferProducts" id="fromTransferRestrictionContainer" class="form-control">
                
                <!-- Her kommer Handlebars Template-->

            </select>
        </div>
        <div class="col-md-8">
            <h4><b>Overfør Til:</b></h4>
        </div>
        <div class="col-sm-3 col-md-4">  
            
            
            <select name="toStorageID" form="transferProducts" id="toTransferRestrictionContainer" class="form-control update">

                <!-- Her kommer Handlebars Template-->
                
            </select>
            
        </div>
        <div class="col-sm-1 col-md-2 row">
            <select id="chooseCategoryContainer" class="form-control">
                        
            </select>
        </div>  
        
        <br><br><br><br>

        <div id="transferProductContainer">
            
            
            <!-- Viser Product som er valgt i FRA lager -->


        </div>
            
            <br><br><br>
            
        <div>
            <table class="table table-responsive" id="transferQuantityContainer">

            <!-- Lar deg velge antall enheter -->
            
            </table>
            
            
            
            <button form="transferProducts" type="submit" class="btn btn-success displayNone" id="transferButton">Registrer Overføring</button>

        </div>    
    </form>
    
            
   
 

  
        



    </div>
        <?php } else { ?>
        <p> Du må ha tilgang til 2 eller fleire lager for å kunne overføre  </p>       
       <?php }?>
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
                        1. Velg hvilke lager du vil overføre fra og til.
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                <img src="image/OverføreVelgLager.PNG">
                    </div>
                
                
                    <div class="col-md-12">
                        <h3>
                            2. Velg hvilke produkt(er) du ønsker å overføre.<br>
                            Du kan også velge kategori for å lettere finne produkter.
                        </h3>
                        
                    </div>
                <div class="col-md-12">
                    <img src="image/VelgProduktOverfør.PNG">
                    </div>
                <div class="col-md-12">
                    <h3>
                        3. Velg antall produkter du vil overføre.
                    </h3>
                </div>
                <div class="col-md-12">
                <img src="image/OverføreMac.PNG">
                </div>
                    
                    <div class="col-md-12">
                    <h3>
                        Dette er alternativer for overføring:<br>
                    </h3>
                    <label>
                        <img src="image/SlettBruker.PNG">Fjern valgt linje<br>
                        
                    </label>
                </div>
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
            </div>
        </div>
        
</div>    




<script id="chooseCategoryTemplate" type="text/x-handlebars-template">
<option data-id="0" value="0">Velg Kategori</option>
{{#each category}}
<option data-id="{{categoryID}}" value="{{categoryID}}">{{categoryName}}</option>
{{/each}}
</script>

<script id="transferQuantityTemplate" type="text/x-handlebars-template">
    
{{#each prodInfo}}   
    <tr class="selectQuantity">
        <th>Produkt:   </th>
        <td>{{productName}}</td>
        <input name="transferProductID[]" id="{{productID}}" form="transferProducts" type="hidden" value="{{productID}}"/>
        <th>Antall:</th>
        <td><input id="{{productID}}" data-id="{{macAdresse}}" class="form-control negativeSupport" name="transferQuantity[]" form="transferProducts" required="required" type="number" min="1" value="" autocomplete="off"/></td> 
            <input name="regMacadresse[]" form="transferProducts" type="hidden" value="{{macAdresse}}"/>
        <th>Tilgjengelig:</th>
        <td>{{quantity}} stk</td>    
        
        <td>
            <button type="button" id="redigerknapp" class="remove" data-id="product{{productID}}" data-toggle="tooltip">
                <span class="glyphicon glyphicon-remove" style="color: red"></span>
            </button>
        </td>
    </tr>
    <tbody class="selectQuantity" id="product{{productID}}">
    </tbody>
{{/each}}
     
</script>

<script id="transferProductTemplate" type="text/x-handlebars-template">
<br>
{{#each storageProduct}}    
 <button data-id="{{productID}}" class="btn btn-primary product">{{productName}}</button>
{{/each}} 
</script>

<script id="transferRestrictionTemplate" type="text/x-handlebars-template">
<option data-id="0" value="0" class="transferStorage">Velg et lager</option>
{{#each transferRestriction}}     
<tr>
    <option data-id="{{storageID}}" value="{{storageID}}" class="transferStorage">{{storageName}}</option>
</tr>   
{{/each}} 
</script>    

<script type="text/javascript" src="js/transfer.js"></script>   