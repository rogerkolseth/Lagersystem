<?php require("view/header.php"); ?>

<?php
if (isset($GLOBALS["saleRestriction"])){
$restriction = $GLOBALS["saleRestriction"];
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
                if (isset($GLOBALS["saleRestriction"]) && $restriction == "1"){?>    
    
    <h2 class="text-center">Registrer uttak</h2>

    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group ">
        <div id="error"></div>
        <div id="success"></div>
        <form id="withdrawProducts" action="?request=withdrawProduct" method="post">
            <div class="col-md-12 row">
                <h4 class="pull-left"><b>Uttak fra:</b></h4>
            </div>
        <div class="col-sm-3 col-md-4 row">
            
            <div id="chooseStorage">
            <select name="fromStorageID" form="withdrawProducts" id="withdrawrRestrictionContainer" class="form-control">

                <!-- Conent of withdrawrRestrictionContainer handlebars-->

            </select>
            </div>
            <div id="singleStorageContainer">
                       <!-- Content of singleStorageContainer handlebars -->     
            </div>
        </div>
            
        <div class="col-sm-1 col-md-2">
            <select id="chooseCategoryContainer" class="form-control displayNone">
                        
                <!-- Content of chooseCategoryContainer handlebars -->
            </select>
        </div>    

        <br><br><br><br>
        
        <div id="withdrawProductContainer">
            

            <!-- Container of withdrawProductContainer handlebar -->


        </div>        

        <br><br><br>

        <div>
            <table class="table table-responsive" id="commentContainer" hidden>

                <tr>
                    <th id="bordernone" class="col-md-1">Kundenr:   </th> 
                    <td id="bordernone"><input class="form-control" name="customerNumber" required="required" form="withdrawProducts" type="number" max="999999999" value=""
                    oninvalid="this.setCustomValidity('Ikke godkjent kundenummer')"
                    oninput="setCustomValidity('')"/></td> 
                </tr>  
                <tr>
                    <th id="bordernone">Kommentar:  </th>
                    <td id="bordernone"><input class="form-control" name="withdrawComment" form="withdrawProducts" type="text" value=""/></td>
                </tr>

            </table>
        </div>

        <br>
        
        <div>
            <table class="table table-responsive" id="withdrawQuantityContainer">

                <!-- Content of withdrawQuantityContainer handlebar -->

            </table>
            
            <button form="withdrawProducts" type="submit" class="btn btn-success displayNone" id="withdrawButton">Registrer Uttak</button>
            <p id="errorMessage"></p>
        </div>
        </form>




    </div>  
    
    <!-- Message when user dont have permission for more than one storage -->
    <?php } else { ?>
        <p> Du må ha tilgang til 1 eller fleire lager for å kunne registere uttak.  </p>       
       <?php }?>
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
                            1. Velg hvilke lager du vil ta ut fra.
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                        <img src="image/VelgLager.PNG" alt="Velg et lager">
                    </div>
                </div>
                
                    <div class="col-md-12">
                        <h3>
                            2. Velg hvilke produkt(er) du ønsker å ta ut fra lageret.<br>
                            Du kan også velge kategori for å lettere finne produkter.
                        </h3>
                        
                    </div>
                <div class="col-md-12">
                    <img src="image/VelgKategori.PNG" alt="Velg kategori">
                    </div>
                <div class="col-md-12">
                    <h3>
                        3. Skriv inn kundenummeret og legg til en kommentar.<br>
                        Du skal også velge antall produkter du vil ta ut.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/RegistrerOverførMac.PNG" alt="Registrer antall of info">
                </div>
                
                <div class="col-md-12">
                    <h3>
                        Dette er alternativer for salg:<br>
                    </h3>
                    <label>
                        <img src="image/SlettBruker.PNG" alt="Slett valgt linje symbol">Fjern valgt linje<br>
                        
                    </label>
                </div>
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
        </div>
        </div>
            
        
    
</div> 

<!-- Handlebars for choose category -->
<script id="chooseCategoryTemplate" type="text/x-handlebars-template">
<option data-id="0" value="0">Velg Kategori</option>
{{#each category}}
<option data-id="{{categoryID}}" value="{{categoryID}}">{{categoryName}}</option>
{{/each}}
</script>

<!-- Handlebars for withdraw quantity -->
<script id="withdrawQuantityTemplate" type="text/x-handlebars-template">
{{#each prodInfo}} 
    <tr class="selectQuantity">
        <th>Produkt:   </th>
        <td>{{productName}}</td>
        <input name="withdrawProductID[]" id="{{productID}}" form="withdrawProducts" type="hidden" value="{{productID}}"/>
        <th>Antall:</th>
        <td><input id="{{productID}}" data-id="{{macAdresse}}" class="form-control negativeSupport" name="withdrawQuantity[]" form="withdrawProducts" required="required" type="number" min="1" value="" autocomplete="off"/></td> 
            <input name="regMacadresse[]" form="withdrawProducts" type="hidden" value="{{macAdresse}}"/>

        <th>Tilgjengelig:</th>
        <td>{{quantity}} stk</td>    
         
        <td>
            <button type="button" id="redigerknapp" data-id="product{{productID}}" class="remove" data-toggle="tooltip">
                <span class="glyphicon glyphicon-remove" style="color: red"></span>
            </button>
        </td>
    </tr>
    
    <tbody class="selectQuantity" id="product{{productID}}">
    </tbody>
   
{{/each}}
     
</script>

<!-- Display storages in drop down meny Template -->
<script id="withdrawRestrictionTemplate" type="text/x-handlebars-template">
<option data-id="0" value="0" class="withdrawStorage">Velg et lager</option>
{{#each transferRestriction}}    
<tr>
    <option data-id="{{storageID}}" value="{{storageID}}" class="withdrawStorage">{{storageName}}</option>
</tr>   
{{/each}}
        
</script>  

<!-- Handlebar for withdraw products -->
<script id="withdrawProductTemplate" type="text/x-handlebars-template">
    <br>  
    {{#each storageProduct}} 
    <button data-id="{{productID}}" class="btn btn-primary product">{{productName}}</button>
    {{/each}} 
</script>

<script>

</script>
<script type="text/javascript" src="js/sale.js"></script>