<?php require("view/header.php"); ?>


<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

   <div class="container"> 

    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group"> 
        
        <form id="searchForReturns" class="form-inline" action="?page=getMyReturns" method="post">
            <div class="form-group col-md-12 row">
                <div class="">
                    <input class="form-control" form="searchForReturns" type="text" name="givenProductSearchWord" value="" placeholder="Søk etter returer.." autocomplete="off">  
                    <input class="form-control btn btn-primary" form="searchForReturns" type="submit" value="Søk">
                    
                    <button onclick="UpdateReturnsTable()" class="btn btn-primary " type="button">Alle Returer</button>
                    
                </div>
                
            </div> 
        </form>
        
        <?php if ($_SESSION["userLevel"] == "Administrator") { ?>

                <form id="showUserReturn" action="?page=showUserReturns" method="post">
                    <div class="dropdown form-group" id="UserSaleSearch">
                        <button class="btn btn-info dropdown-toggle" type="button"  data-toggle="dropdown">
                            Velg brukere
                        </button>
                        <div class="dropdown-menu">

                            <li class="dropdown-header"><h4>Velg Bruker:</h4></li>
                            <table class="table" id="chooseUserReturnContainer">

                            </table>
                        </div>
                    </div> 
                </form>


            <?php } ?>
        
        <br><br><br><br>
        
        
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><b>Mine Returer</b></h3>
            </div>
         <table class="table table-responsive"> 
            
            <tbody id="myReturnsContainer">

                <!-- HER KOMMER INNHOLDET FRA HANDLEBARS  -->

            </tbody>
        </table> 
            </div>
        
        </div>
   </div>
</div>  


<div class="modal fade" id="editReturnsModal" role="dialog">
    <div class="modal-dialog">
        <!-- Innholdet til Modalen -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Returinformasjon</h4>
            </div>
            <form action="?page=editMyReturn" method="post" id="editReturn"> 
            
            <div class="modal-body">
                <table class="table" id="editReturnContainer">
                    

                <!-- Innhold fra Handlebars Template -->
                    
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
    
   
<script id="myReturnsTemplate" type="text/x-handlebars-template">        
<tr>
    <th>KundeNr</th>        
    <th>Produkt</th>
    <th>Lager</th>     
    <th>Antall</th>
    <th>Kommentar</th>
    <th>Dato</th>   
    <th></th> 
</tr>        
{{#each myReturns}}  
<tr>
    <td>{{customerNr}}</td>        
    <td>{{productName}}</td>
    <td>{{storageName}}</td>
    <td>{{quantity}}</td>    
    <td>{{comment}}</td>    
    <td>{{date}}</td>  
    <td><button id="redigerknapp" data-id="{{returnID}}" class="editReturns" data-toggle="tooltip" title="Rediger retur">
    <span class="glyphicon glyphicon-edit" style="color: green"></span>
    </button> </td>    
<tr>
{{/each}}
</script>  


<script type="text/javascript" src="js/myReturns.js"></script>