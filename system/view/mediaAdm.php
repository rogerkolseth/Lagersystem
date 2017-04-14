<?php require("view/header.php"); ?>

<?php
if (isset($GLOBALS["errorMessage"])){
$test = $GLOBALS["errorMessage"];
}
?>


<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    
    <div class="container"> 

        <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group"> 
            
              
        <form id="searchForMedia" class="form-inline" action="?page=getAllMediaInfo" method="post">    
            <div class="form-group col-md-12 row">
                
                    <input class="form-control" form="searchForMedia" type="text" name="givenMediaSearchWord" value="" placeholder="Søk etter media..">  
                    <input class="form-control btn btn-primary" form="searchForMedia" type="submit" value="Søk">
                    <select id="chooseCategoryContainer" class="form-control btn btn-primary">
                        
                    </select>
                    <button onclick="UpdateMediaTable()" class="btn btn-primary " type="button">Alle medier</button>
                
                <div class="pull-right">
                    <button class="btn btn-success" onclick="getCategoryInfo()" type="button" data-toggle="modal" data-target="#uploadImageModal">Last opp bilde</button>
                </div>
            </div>
        </form>
            <br><br>
            <div id="success"></div>
            <?php 
                if (isset($GLOBALS["errorMessage"])){ ?>
            <div class="alert alert-success">
                <?php
                     echo $test; ?>   
            </div>  <?php
                     }
                ?>
              <br><br>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><b>Mediaoversikt</b></h3>
                </div>       


                <div class="panel-body">
                    <div id="displayMediaContainer">

                        <!-- HER KOMMER INNHOLDET FRA HANDLEBARS  -->

                    </div>



                </div>
            </div>
        </div>     
    </div>




    <!-- UPLOAD IMAGE MODAL -->


    <div class="modal fade" id="uploadImageModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Last opp bilde</h4>
                </div>
                <div class="modal-body">
                    <div style="text-align: center">

                        <form action="?page=uploadImage" id="uploadImage" method="post" enctype="multipart/form-data">
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
                                    <select name="givenCategoryID" id="selectCategoryID" required="required" class="form-control" autocomplete="off">
                                    </select>
                                </td>
                            </tr>
                        </table>
                        
                    </div>
                </div>
                <div class="modal-footer">
                <input class="btn btn-success" form="uploadImage" type="submit" value="Upload Image" name="submit" href="?page=uploadImage">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
                </form>
            </div>
        </div>
    </div> 
    
    
    
    
    <!-- GET MEDIA INFORMATION MODAL -->

<div class="modal fade" id="showMediaInformationModal" role="dialog">
    <div class="modal-dialog">
        <!-- Innholdet til Modalen -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="mediaTitle"></h4>
            </div>
            <div class="modal-body">
                <div id="mediaInformationContainer">
                    
                <!-- Her kommer bilde Template -->
                
                </div>
                <br>
                <div id="mediaCategory">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
            </div>
        </div>
    </div>
</div> 
    
    
    <!-- EDIT MEDIA MODAL -->
    
<div class="modal fade" id="editMediaModal" role="dialog">
    <div class="modal-dialog">
        <!-- Innholdet til Modalen -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="mediaTitle">Rediger media</h4>
            </div>
            <div class="modal-body">
                <form action="?page=editMedia" method="post" id="editMedia">
                  
                <table class="table" id="editMediaContainer">
                    
                <!-- Her kommer bilde Template -->
                
               </table>
            </div>
            <div class="modal-footer">
                <input class="btn btn-success" type="submit" value="Lagre" form="editMedia">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
            </div>
            </form>
        </div>
    </div>
</div>    
    
    <!-- DELETE MEDIA -->
    
<div class="modal fade" id="deleteMediaModal" role="dialog">
    <div class="modal-dialog">
        <!-- Innholdet til Modalen -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Slett bilde?</h4>
            </div>
            <form action="?page=deleteMedia" method="post" id="deleteMedia">
            <div class="modal-body" id="deleteMediaContainer">
                  
                <!-- Innhold fra Handlebars Template -->
            </div>    
            <div class="modal-footer">
                <div id="errorDelete"></div>
                <input form="deleteMedia" class="btn btn-success" type="submit" value="Slett">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
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
    
<!-- Display what media you are deleting-->
<script id="deleteMediaTemplate" type="text/x-handlebars-template">
    <p> Er du sikker på at du vil slette:  <P>
    {{#each mediaInfo}}
    <b>{{mediaName}}</b>
    <input type="hidden" form="deleteMedia" name="deleteMediaID" value="{{mediaID}}">    
    <br><br>
    <img class="img-responsive" src="image/{{mediaName}}" alt="Home">            
    {{/each}} 
</script>       

<!-- Edit media -->
<script id="editMediaTemplate" type="text/x-handlebars-template">
{{#each mediaInfo}}    
    <input form="editMedia" type="hidden" name="editMediaID" value="{{mediaID}}">
    <tr>
    <th id="bordernone">Medianavn: </th> 
    <td id="bordernone"><input class="form-control" form="editMedia" required="required" type="text" name="editMediaName" value="{{mediaName}}" autocomplete="off"></td> 
    </tr>
    <tr>
    <th id="bordernone">Kategori: </th> 
    <td>
        <select form="editMedia" type="text" required="required" name="editCategoryID" class="form-control" autocomplete="off">
        <option value="{{categoryID}}">{{categoryName}}</option>
    {{/each}}    
        {{#each category}}            
        <option value="{{categoryID}}">{{categoryName}}</option>
        {{/each}}
    </select>                
    </td>            
    </tr>
 

</script>
    
<!-- Display all images -->
<script id="displayMediaTemplate" type="text/x-handlebars-template">
{{#each mediaInfo}}
<div class="col-md-3">

<div class="img-border">
<div class="caption">
{{mediaName}}
</div>
<a href="#">
<img class="img-thumbnail"  onclick=showMedia({{mediaID}}); id="{{mediaID}}" src="image/{{mediaName}}" alt="Home">
</a>
<div class="caption">

    <button data-id="{{mediaID}}" id="redigerknapp" class="edit" data-toggle="tooltip" title="Rediger bilde">

    <span class="glyphicon glyphicon-edit" style="color: green"></span>
    </button>
    
    <button data-id="{{mediaID}}" id="redigerknapp" class="delete" data-toggle="tooltip" title="Slett bilde">
   
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>

</div>
</div>
</div>
{{/each}}
    
</script>    


<script src="js/mediaAdm.js"></script>