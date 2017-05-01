<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">


    <div class="container"> 
<div class="row">
        <div class="pull-right">
            <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
            </label>
        </div>
    </div>
        <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group"> 


            <form id="searchForCategory" class="form-inline" action="?page=getCategorySearchResult" method="post">    
                <div class="form-group col-md-12 row">

                    <input class="form-control" form="searchForCategory" type="text" name="givenCategorySearchWord" value="" placeholder="Søk etter kategori..">  
                    <input class="form-control btn btn-primary" form="searchForCategory" type="submit" value="Søk">

                    <button onclick="UpdateCategoryTable()" class="btn btn-primary " type="button">Alle kategorier</button>

                    <div class="pull-right">
                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#createCategoryModal">Opprett kategori</button>
                    </div>
                </div>
            </form>
            <br><br>
            <div id="success"></div>
            <br><br>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><b>Kategorioversikt</b></h3>
                </div>
                <table class="table table-responsive"> 

                    <tbody id="displayCategoryContainer">

                        <!-- HER KOMMER INNHOLDET FRA HANDLEBARS  -->

                    </tbody>

                </table>

            </div>
        </div> 
    </div>

    <div class="modal fade" id="createCategoryModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Opprett kateogri</h4>
                </div>
                <div class="modal-body">
                    <form action="?page=addCategoryEngine" method="post" id="createCategory">
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

                    <input class="btn btn-success" form="createCategory" type="submit" value="Opprett Kategori">


                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                </div>
                </form>
            </div>
        </div>
    </div> 
</div>


    <!-- Delete category Modal-->

    <div class="modal fade" id="deleteCategoryModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Slett kategori</h4>
                </div>
                <form action="?page=deleteCategoryEngine" method="post" id="deleteCategory">
                <div class="modal-body" id="deleteCategoryContainer">

                    <!-- Innhold fra Handlebars Template-->

                </div>
                <div class="modal-footer">
                    <div id="errorDelete"></div>
                    <input form="deleteCategory" class="btn btn-success" type="submit" value="Slett">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
            </form>
        </div>
    </div>  

    <!-- Edit category Modal -->


<div class="modal fade" id="editCategoryModal" role="dialog">
    <div class="modal-dialog">
        <!-- Innholdet til Modalen -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rediger Kategori</h4>
            </div>
            <form action="?page=editCategoryEngine" method="post" id="editCategory"> 
            
            <div class="modal-body">
                <table class="table" id="editCategoryContainer">
                    

                <!-- Innhold fra Handlebars Template -->
                    
                </table>
            </div>
            
            <div class="modal-footer">
                <div id="errorEdit"></div>
                <input class="btn btn-success" form="editCategory" type="submit" value="Lagre">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
            </div>
            </form>
        </div>
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
                            Bruk denne funksjonen for å søke etter kategorier.<br>
                            
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                <img src="image/SøkKategori.PNG" alt="Søkefelt for katefori">
                    </div>
                
                
                    <div class="col-md-12">
                        <h3>
                            Her oppretter du nye kategorier.
                        </h3>
                        
                    </div>
                <div class="col-md-12">
                    <img src="image/OpprettKategori.PNG" alt="Opprett kategori knapp">
                    </div>
                <div class="col-md-12">
                    <h3>
                        Skriv inn info om kategorien du vil opprette.
                    </h3>
                </div>
                <div class="col-md-12">
                <img src="image/OpprettKategoriModal.PNG">
                </div>
                    <div class="col-md-12">
                    <h3>
                        Dette er en liste over alle kategoriene i systemet.
                    </h3>
                </div>
                <div class="col-md-12">
                <img src="image/Kategorier.PNG">
                </div>
                    <div class="col-md-12">
                        <h3>
                        Dette er alternativer for kategorier:<br>
                        </h3>
                        <label>
                        1. <img src="image/EndreBruker.PNG">Endre kategori<br>
                        
                        2. <img src="image/SlettBruker.PNG">Slett kategori
                        
                    </label>
                </div>
                
                   
                    
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
            </div>
        </div>
        
</div>
    <!-- Display edit category-->                    
<script id="editCategoryTemplate" type="text/x-handlebars-template">
    {{#each categoryByID}}    
    <input form="editCategory" type="hidden" name="editCategoryID" value="{{categoryID}}">
    <tr>
    <th id="bordernone">Kategorinavn: </th> 
    <td id="bordernone"><input class="form-control" form="editCategory" required="required" type="text" name="editCategoryName" value="{{categoryName}}" autocomplete="off"></td> 
    </tr>
    {{/each}}            
</script>  

<!-- delete category template -->

<script id="deleteCategoryTemplate" type="text/x-handlebars-template">
    <p> Er du sikker på at du vil slette:  <P>
    {{#each categoryByID}}           
    {{categoryName}}  
    <input form="deleteCategory" type="hidden" name="deleteCategoryID" value="{{categoryID}}"><br>
    {{/each}}    
</script>    

<!-- display all category template -->
<script id="displayCategoryTemplate" type="text/x-handlebars-template">

    {{#each category}} 
    <tr>
    <td class="text-center col-md-2">  


    <!-- Knapp som aktiverer Model for kategoriredigering  --> 

    <button id="redigerknapp" data-id="{{categoryID}}" class="edit" data-toggle="tooltip" title="Rediger kategori">
    <span class="glyphicon glyphicon-edit" style="color: green"></span>
    </button>
    

    <!-- Knapp som aktiverer Model for sletting av kategori  --> 



    <button id="redigerknapp" data-id="{{categoryID}}" class="delete" data-toggle="tooltip" title="Slett kategori">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button> 


    </td>


    <!-- Printer ut kategorinavn inn i tabellen -->

    <th>Kategorinavn: </th>
    <td>{{categoryName}}</td>


    
    {{/each}}
    </tr>        


</script>


<script type="text/javascript" src="js/categoryAdm.js"></script>