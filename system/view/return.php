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
        <td><input name="returnQuantity[]" form="returnProducts" required="required" type="number" min="1" max="1000" value="" autocomplete="off"/></td>  
        
        <td>
            <button id="redigerknapp" class="remove" data-toggle="tooltip">
                <span class="glyphicon glyphicon-remove" style="color: red"></span>
            </button>
        </td>    
        
    </tr>
{{/each}}  
</script>

<script id="returnProductTemplate" type="text/x-handlebars-template">
    <br>  
    {{#each productInfo}} 
    <button data-id="{{productID}}" class="btn btn-primary product">{{productName}}</button>
    {{/each}} 
</script>


<script type="text/javascript" src="js/return.js"></script>