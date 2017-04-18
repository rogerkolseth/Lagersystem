<?php require("view/header.php");?>


<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <div class="container">
        <h2 class="text-center">Overføring</h2>
    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group">
        <div id="error"></div>
        <div id="success"></div>
        
    <form id="transferProducts" action="?page=transferProduct" method="post">    
        
        <div class="col-sm-3 col-md-4 row">
            <label>Overfør Fra:</label>
            <select name="fromStorageID" form="transferProducts" id="fromTransferRestrictionContainer" class="form-control">
                
                <!-- Her kommer Handlebars Template-->

            </select>
        </div>
        <div class="col-md-8">
        <label>Overfør Til:</label>
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
            
            
            
            <button form="transferProducts" type="submit" class="btn btn-success" id="transferButton">Overfør</button>

        </div>    
    </form>
    
            
   
 

  
        



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
        <td><input class="form-control negativeSupport" name="transferQuantity[]" form="transferProducts" required="required" type="number" min="1" value="" autocomplete="off"/></td> 
        <th>Tilgjengelig:</th>
        <td>{{quantity}} stk</td>    
        
        <td>
            <button id="redigerknapp" class="remove" data-toggle="tooltip">
                <span class="glyphicon glyphicon-remove" style="color: red"></span>
            </button>
        </td>

    </tr>
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

<script src="js/transfer.js"></script>   