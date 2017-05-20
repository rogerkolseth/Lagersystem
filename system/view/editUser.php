<?php require("view/header.php"); ?>

<?php
if (isset($GLOBALS["errorMessage"])){
$test = $GLOBALS["errorMessage"];
}
?>


<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 <div class="container"> 
                 <div id="message">
                <?php 
                if (isset($GLOBALS["errorMessage"])){
                     echo $test;
                    }
                ?>
            </div>  
    <div class="col-md-4 col-md-offset-1" >
        
        <h3 style="margin-bottom: 4%">Rediger bruker</h3>
        
        
        <form action="?request=editUserEngine" method="post" id="editUser">
        <table class="table" id="displayUserContainer">
            
        </table>
            <div id="editSaved" style="display: none">
                <p class="text-success">Endringene er lagret!</p>
            </div>
            <a href="javascript:history.back()" class="btn btn-danger pull-right">Tilbake</a>
            <input class="btn btn-success pull-right" style="margin-right: 3%" type="submit" value="Lagre" form="editUser" onclick="document.getElementById('editSaved').style.display = 'block'; javascript:history.go(0)"> 
                
                 
            
        </form>
      
    </div>
    
</div>    
</div>

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

                        <form action="?request=uploadImage" id="uploadImage" method="post" enctype="multipart/form-data">
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
                                    <select name="givenCategoryID" id="selectCategoryEdit" required="required" class="form-control" autocomplete="off">
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



<script id="displayUserTemplate" type="text/x-handlebars-template">
       {{#each user}}
    <input form="editUser" type="hidden" name="editUserID" value="{{userID}}">
    <tr>
    <th id="bordernone">Navn: </th>
    <td id="bordernone"><input class="form-control" form="editUser" type="text" required="required" name="editName" value="{{name}}"></td>
    </tr>
    
    <input form="editUser" type="hidden" name="editUsername" value="{{username}}">
        
        
    <tr>
    <th id="bordernone">Passord: </th>
    <td id="bordernone"><input class="form-control" form="editUser" type="password" required="required" name="editPassword" value="{{password}}"></td>
    </tr>
    <tr>
    <th id="bordernone">Epost:</th>
    <td id="bordernone"><input class="form-control" form="editUser" type="text" required="required" name="editEmail" value="{{email}}"></td>
    </tr>
    
    <input form="editUser" type="hidden" name="editUserLevel" value="{{userLevel}}">
    
    
    <tr>
    <th id="bordernone">Media: </th>

    <td id="bordernone">
    <select form="editUser" type="text" required="required" name="editMediaID" class="form-control" autocomplete="off">
        <option value="{{mediaID}}">{{mediaName}}</option>
    {{/each}}
        {{#each media}}        
        <option value="{{mediaID}}">{{mediaName}}</option>
                
        {{/each}}
            
    </select>
    {{#each user}}
    <a id="handhover" type="button" onclick="getCategoryInfo()" data-toggle="modal" data-target="#uploadImageModal">Last opp nytt bilde</a>
    </td>
    </tr>
    <tr>
    <th id="bordernone">Profilbilde:  </th>
    <td id="bordernone" class="text-center"><img class="img-thumbnail" src="image/{{mediaName}}" alt="Home"></td>
    </tr>
    
    {{/each}}
        
   
        
</script>
        
<script type="text/javascript" src="js/editUser.js"></script>