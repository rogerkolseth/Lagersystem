<?php require("view/header.php"); ?>

<?php
if (isset($GLOBALS["returnRestriction"])){
$restriction = $GLOBALS["returnRestriction"];
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
                if (isset($GLOBALS["returnRestriction"]) && $restriction == "1"){?>

        <h2 class="text-center">Registrer retur</h2>

    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group"> 
        <div id="success"></div>
        <div id="error"></div>
        <form id="returnProducts" action="?page=returnProduct" method="post">
            <div class="col-md-12 row">
                <label class="pull-left">Retur til:</label>
            </div>
            <div class="col-sm-3 col-md-4 row">
                <h4>Returlager</h4>
        </div>
        <div class="col-sm-1 col-md-2">
            <select id="chooseCategoryContainer" class="form-control">
                        
            </select>
        </div>
        
        
                <br><br><br><br>
        
        <div id="returnProductContainer">
            

            <!-- Viser Product som er valgt i FRA lager -->


        </div>  
                
        
        <br><br><br>

        <div>
            <table class="table table-responsive" id="commentContainer">

                <tr>
                    <th class="col-md-1" id="bordernone">Kundenr:   </th> 
                    <td id="bordernone"><input class="form-control" name="customerNumber" required="required" form="returnProducts" max="999999999" type="number" value=""
                    oninvalid="this.setCustomValidity('Ikke godkjent kundenummer')"
                    oninput="setCustomValidity('')"/></td> 
                </tr>  
                <tr>
                    <th class="col-md-1">kommentar:  </th>
                    <td><input class="form-control" name="returnComment" required="required" form="returnProducts" type="text" value=""/></td>
                </tr>

            </table>
        </div>   
        
        <div>
            <table class="table table-responsive" id="returnQuantityContainer">
            
                <!-- Lar deg velge antall enheter -->

            </table>

            
            <input form="returnProducts" type="hidden" id="date" name="date">
            
            <button form="returnProducts" type="submit" class="btn btn-success" id="returnButton">Registrer Retur</button>
            <p id="errorMessage"></p>
        </div> 
        </form>
        
        
        
        
    </div>
        <?php } else { ?>
        <p> Du har ikkje tilgang til Returlageret </p>       
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
                            1. Velg hvilke produkter du ønsker å ta retur på.<br> Du kan også velge kategori for å lettere finne et produkt.
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                    <img src="image/VelgProduktRetur.PNG">
                    </div>
                </div>
                
                    <div class="col-md-12">
                        <h3>
                            2. Skriv inn kundenummeret, en kommentar og velg hvor mange produkter du ønsker å ta retur på.<br>
                            
                        </h3>
                        
                    </div>
                <div class="col-md-12">
                    <img src="image/RegistrerRetur_1.PNG">
                    </div>
                <div class="col-md-12">
                    <h3>
                        Dette er alternativer for returer:<br>
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

<script id="chooseCategoryTemplate" type="text/x-handlebars-template">
<option data-id="0" value="0">Velg Kategori</option>
{{#each category}}
<option data-id="{{categoryID}}" value="{{categoryID}}">{{categoryName}}</option>
{{/each}}
</script>

<script id="returnQuantityTemplate" type="text/x-handlebars-template">
{{#each product}} 
    <tr class="selectQuantity">
        <th>Produkt:   </th> 
        <td>{{productName}}</td>
        <input name="returnProductID[]" id="{{productID}}" form="returnProducts" type="hidden" value="{{productID}}"/>
        <th>Antall:</th>
        <td><input name="returnQuantity[]" class="form-control negativeSupport" id="{{productID}}" data-id="{{macAdresse}}" form="returnProducts" required="required" type="number" min="1" max="1000" value="" autocomplete="off"/></td>  
            <input name="regMacadresse[]" form="returnProducts" type="hidden" value="{{macAdresse}}"/>
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

<script id="returnProductTemplate" type="text/x-handlebars-template">
    <br>  
    {{#each productInfo}} 
    <button data-id="{{productID}}" class="btn btn-primary product">{{productName}}</button>
    {{/each}} 
</script>


<script type="text/javascript" src="js/return.js"></script>