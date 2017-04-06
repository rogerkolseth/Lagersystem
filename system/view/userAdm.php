<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <div class="container">


    <!-- DIV som holder på all informasjon til venstre på skjermen  -->


    <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group">

        <!-- SØK ETTER BRUKER  -->
        <form class="form-inline" id="searchForUser" action="?page=getUserInfo" method="post">
            <div class="form-group col-md-12 row">
                
                    <input class="form-control" form="searchForUser" type="text" name="givenUserSearchWord" value="" placeholder="Søk etter bruker..">  
                    <input class="form-control btn btn-primary" form="searchForUser" type="submit" value="Søk">
                                             
                <button onclick="UpdateUsersTable()" class="btn btn-primary" type="button">Alle brukere</button>
             
            <div class="pull-right row">
                <button class="btn btn-success " onclick="getMediaInfo();" type="button" data-toggle="modal" data-target="#createUserModal">Opprett bruker</button>
                <button  id="setRestriction" onclick="getStorageInfo()" data-toggle="modal" data-target="#userRestrictionModal" class="btn btn-warning" type="button">Velg Lager</button>
            </div>
            </div>
            
            
        </form>
        <br><br>
        <div id="success"></div>

            <!-- OPPRETT BRUKER  -->

            <div class="modal fade" id="createUserModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Innholdet til Modalen -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Opprett bruker</h4>
                        </div>
                        <form action="?page=addUserEngine" method="post" id="createUser">
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
                                            <select name="givenMediaID" id="selectMediaID" required="required" class="form-control" autocomplete="off">
                                            </select>
                                        </td>
                                    </tr>

                                    </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div id="error"></div>
                            <input class="btn btn-success" form="createUser" type="submit" value="Opprett bruker">


                            <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                        </div>
                        </form>

                    </div>
                </div>
            </div> 
    

    <br>

    

    <!-- DISPLAY USER CONTAINER    -->       
    <br>
     <form action="?page=addRestriction" id="editRestriction" method="post">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title text-center"><b>Brukeroversikt</b>
                <div class="pull-right" style="margin-right: 3.3%">
                    <b><span class="glyphicon glyphicon-wrench"></span></b>
                </div>
            </h3>
        </div>
        <table class="table table-responsive">
            
            <tbody id="displayUserContainer">

            <!-- HER KOMMER INNHOLDET FRA HANDLEBARS  -->

            </tbody>    
        </table>
    </div>
         
        <!-- Set restrictions -->

    
    <div class="modal fade" id="userRestrictionModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Velg lager tilgang(er)</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered" id="storageRestrictionContainer">

                        <!-- Handlebars information -->


                    </table>
                </div>
                <div class="modal-footer">

                    <button form="editRestriction" class="btn btn-success" type="submit">Velg lagertilgang</button> 

                </div>
                 
            </div>
        </div>
    </div>      
         
      
     </form>    
         
         
         
    </div>  
</div>









    <!-- DELETE USER MODAL -->

    <div class="modal fade" id="deleteUserModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Bruker informasjon</h4>
                </div>
                <form action="?page=deleteUserEngine" method="post" id="deleteUser">
                <div class="modal-body" id="deleteUserContainer">

                    <!-- Innhold fra Handlebars Template-->

                </div>
                <div class="modal-footer">
                    <input form="deleteUser" class="btn btn-success" type="submit" value="Slett">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
            </form>
        </div>
    </div>  




    <!-- SHOW USER INFORMATION MODAL-->     

    <div class="modal fade" id="showUserInformationModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Bruker informasjon</h4>
                </div>
                <div class="modal-body">
                    <div id="userInformationContainer">
                        
                        <!-- Innhold fra Handlebars Template-->
                    </div>
                    <div>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Lagertilgang</th>
                            </tr>
                        </thead>
                        <tbody id="userRestrictionContainer"> 
                           
                                    <!-- Innhold fra Handlebars Template-->

                        </tbody>
                    </table>
                
                </div>
                </div>
                <div class="modal-footer">
                    <div id="successRes"></div>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
        </div>
    </div> 


    <!-- SHOW EDIT USER MODAL -->


    <div class="modal fade" id="editUserModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Bruker informasjon</h4>
                </div>
                <form action="?page=editUserEngine" method="post" id="editUser">
                <div class="modal-body text-center">
                    <table class="table" id="editUserContainer">

                    <!-- Innhold fra Handlebars Template--> 
                    </table>
                </div>
                <div class="modal-footer">
                    <div id="errorEdit"></div>
                    <input class="btn btn-success" type="submit" value="Lagre" form="editUser">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
                </form>    
            </div>
        </div>
    </div>             




</div>


<!-- HANDLEBARS TEMPLATES-->

<script id="storageRestrictionTemplate" type="text/x-handlebars-template">
{{#each storageInfo}}
    <tr> 
        <td>{{storageName}}</td> 

        <td><input form="editRestriction" class="selectStorageRestriction" id="{{storageID}}" value="{{storageID}}"  name="storageRestrictions[]" type="checkbox"></td>
    </tr>
{{/each}}
</script>     

<!-- edit user template-->
<script id="editUserTemplate" type="text/x-handlebars-template">

    {{#each user}}
    <input form="editUser" type="hidden" name="editUserID" value="{{userID}}">
    <tr>
    <th id="bordernone">Navn: </th>
    <td id="bordernone"><input class="form-control" form="editUser" type="text" required="required" name="editName" value="{{name}}"></td>
    </tr>
    <tr>
    <th>Brukernavn: </th>
    <td><input class="form-control" form="editUser" type="text" required="required" name="editUsername" value="{{username}}"></td>
    </tr>
    <tr>
    <th>Passord: </th>
    <td><input class="form-control" form="editUser" type="password" required="required" name="editPassword" value="{{password}}"></td>
    </tr>
    <tr>
    <th>Epost:</th>
    <td><input class="form-control" form="editUser" type="text" required="required" name="editEmail" value="{{email}}"></td>
    </tr>
    <tr>
    <th>Brukernivå: </th>
    
    <td>
    <select form="editUser" type="text" required="required" name="editUserLevel" class="form-control" autocomplete="off">
        <option>{{userLevel}}</option>
        <option value="User">User</option>
        <option value="Administrator">Administrator</option>
    </select>
    </td>
    </tr>
    
    <tr>
    <th>Media: </th>

    <td>
    <select form="editUser" type="text" required="required" name="editMediaID" class="form-control" autocomplete="off">
        <option value="{{mediaID}}">{{mediaName}}</option>
    {{/each}}
        {{#each media}}            
        <option value="{{mediaID}}">{{mediaName}}</option>
        {{/each}}
    </select>
    </td>
    </tr>
    
    
        
    
</script>   


<!-- show user restriction template-->
<script id="userRestrictionTemplate" type="text/x-handlebars-template">
{{#each restriction}}
<tr>
    <td id="bordernone">
    <button id="redigerknapp" data-id="{{storageID}}" class="deleteRestriction" data-toggle="tooltip" title="Fjern lagertilgang">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>
    </td>
<td id="bordernone">{{storageName}}</td>
</tr>    
    
{{/each}}      
</script>

<!-- Show user information template -->
<script id="userInformationTemplate" type="text/x-handlebars-template">
    {{#each user}}
    <div class="col-md-6">
    <table class="table">
        <tr>
            <th id="bordernone">Navn</th>
            <td id="bordernone">{{name}}</td>            
        </tr>
        <tr>
            <th>UserID:</th> 
            <td>{{userID}}</td>
        </tr>
        <tr>
            <th>Brukernavn:</th>
            <td>{{username}}</td>
        </tr>
        <tr>
            <th>Brukernivå:</th>
            <td>{{userLevel}}</td>
        </tr>
        <tr>
            <th>E-post:</th>
            <td>{{email}}</td>
        </tr>
        <tr>
            <th>Sist innlogget: </th>
            <td>{{lastLogin}}</td>
        </tr>
        
    </table>
    </div>
    <div class="col-md-6">
        <td><img class="img-responsive" src="image/{{mediaName}}" alt="Home"></td>
    </div>
    
    {{/each}}     
</script>    


<!-- delete user template -->

<script id="deleteUserTemplate" type="text/x-handlebars-template">
    <p> Er du sikker på at du vil slette:  <P>
    {{#each user}}           
    {{name}}  
    <input form="deleteUser" type="hidden" name="deleteUserID" value="{{userID}}"><br>
    {{/each}}    
</script>    

<!-- display all users template -->
<script id="displayUserTemplate" type="text/x-handlebars-template">

    {{#each users}} 
    <tr>
    <td class="text-center col-md-2">  

     
    <!-- Knapp som aktiverer Model for brukerredigering  --> 

    <button id="redigerknapp" data-id="{{userID}}" class="edit" data-toggle="tooltip" title="Rediger bruker">
    <span class="glyphicon glyphicon-edit" style="color: green"></span>
    </button>
  

    <!-- Knapp som aktiverer Model for å vise brukerinformasjon  --> 

    <button id="redigerknapp" data-id="{{userID}}" class="information" data-toggle="tooltip" title="Vis informasjon" >
    <span class="glyphicon glyphicon-menu-hamburger" style="color: #003366" ></span>
    </button>


    <!-- Knapp som aktiverer Model for sletting av bruker  --> 

     
   
    <button id="redigerknapp" data-id="{{userID}}" class="delete" data-toggle="tooltip" title="Slett bruker">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button> 

    </td>
 
    <!-- Printer ut navn og brukernavn inn i tabellen -->

    <th>Navn: </th>
    <td>{{name}}</td>
    <th>Brukernavn: </th>
    <td>{{username}}</td>


    <!-- Legger inn chackbox for fler valg (ved lagertilganggiving -->

  
    
    <td> <input form="editRestriction" class="selectRestriction" id="{{userID}}" value="{{userID}}"  name="userRestrictions[]" type="checkbox"></td>

  

    {{/each}}
    </tr>


</script>



<!-- CREATE USER -->
<script>
    $(function POSTuserInfo() {

        $('#createUser').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                error: function() {         
                    errorMessage();
                },
                success: function () {
                    $("#createUser")[0].reset();
                    $('#createUserModal').modal('hide');
                    $('#errorMessage').remove();
                    UpdateUsersTable();
                    successMessageCreate();
                }
            });
            return false;
        });
    });

</script>

<script>
function errorMessage() {    
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt brukernavn </div>').appendTo('#error')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script>

<script>
function successMessageCreate() {    
    $('<div class="alert alert-success"><strong>Opprettet!</strong> Bruker er opprettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script> 


<!-- SEARCH FOR USERS -->
<script>
    $(function POSTsearchForUser() {

        $('#searchForUser').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    $("#searchForUser")[0].reset();
                    usersTableTemplate(data);
                }
            });
            return false;
        });
    });

</script>



<!-- UPDATE USER INFOMARTION -->
<script>
    function UpdateUsersTable() {
        $(function () {
            $.ajax({
                type: 'GET',
                url: '?page=getUserInfo',
                dataType: 'json',
                success: function (data) {
                    usersTableTemplate(data);
                }
            });
        });
    }
</script>



<!-- GET USER INFOROMATION -->

<script>
    $('#dropdown').show();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getUserInfo',
            dataType: 'json',
            success: function (data) {
                usersTableTemplate(data);
            }
        });
    });
</script>


<!-- DISPLAY USER TEMPLATE -->
<script>
    function usersTableTemplate(data) {

        var rawTemplate = document.getElementById("displayUserTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var UserTableGeneratedHTML = compiledTemplate(data);

        var userContainer = document.getElementById("displayUserContainer");
        userContainer.innerHTML = UserTableGeneratedHTML;
    }
</script>




<!--    SHOW USER INFORMATION      -->

<script>
   
    $(function POSTuserInformationModal() {

        $('#displayUserContainer').delegate('.information', 'click', function () {

            var givenUserID = $(this).attr('data-id');
            POSTuserRestriction(givenUserID);
            $.ajax({
                type: 'POST',
                url: '?page=getUserByID',
                data: {givenUserID: givenUserID},
                dataType: 'json',
                success: function (data) {
                    $('#showUserInformationModal').modal('show');
                    userInformationTemplate(data);

                }
            });
            return false;

        });
    });
</script>

<script>
    var givenUserID;
    function POSTuserRestriction(data) {
        givenUserID = data;
        $(function () {
            $.ajax({
                type: 'POST',
                url: '?page=getUserRestriction',
                data: {givenUserID: givenUserID},
                dataType: 'json',
                success: function (data) {
                    userRestrictionTemplate(data);
                }
            });
        });
    }
</script>

<script>
    $(function deleteUserRestriction() {
        $('#userRestrictionContainer').delegate('.deleteRestriction', 'click', function () {

            var givenStorageID = $(this).attr('data-id');
            
            $.ajax({
                type: 'POST',
                url: '?page=deleteSingleRes',
                data: {givenUserID: givenUserID, givenStorageID: givenStorageID},
                dataType: 'json',
                success: function () {
                    successMessageRes();
                    POSTuserRestriction(givenUserID);

                }
            });
            return false;

        });
    });               
</script>  

<script>
function successMessageRes() {    
    $('<div class="alert alert-success"><strong>Slettet!</strong> Brukertilgang er slettet. </div>').appendTo('#successRes')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script> 


<script>
    function userRestrictionTemplate(data) {
        var rawTemplate = document.getElementById("userRestrictionTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var UserRestrictionGeneratedHTML = compiledTemplate(data);

        var userContainer = document.getElementById("userRestrictionContainer");
        userContainer.innerHTML = UserRestrictionGeneratedHTML;
    }
</script>

<script>
    function userInformationTemplate(data) {
        var rawTemplate = document.getElementById("userInformationTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var UserInformationGeneratedHTML = compiledTemplate(data);

        var userContainer = document.getElementById("userInformationContainer");
        userContainer.innerHTML = UserInformationGeneratedHTML;
    }
</script>




<!--    DELETE USER     -->


<!-- DELETE USER MODAL -->
<script>
    $(function POSTdeleteUserModal() {

        $('#displayUserContainer').delegate('.delete', 'click', function () {
            var givenUserID = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: '?page=getUserByID',
                data: {givenUserID: givenUserID},
                dataType: 'json',
                success: function (data) {
                    deleteUserTemplate(data);
                    $('#deleteUserModal').modal('show');
                }
            });
            return false;

        });
    });
</script>   

<!-- DELETE USER TEMPLATE-->         
<script>
    function deleteUserTemplate(data) {
        var rawTemplate = document.getElementById("deleteUserTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var UserTableGeneratedHTML = compiledTemplate(data);

        var userContainer = document.getElementById("deleteUserContainer");
        userContainer.innerHTML = UserTableGeneratedHTML;
    }
</script>

<script>
    $(function deleteUserByID() {

        $('#deleteUser').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    successMessageDelete();
                    UpdateUsersTable();
                    $('#deleteUserModal').modal('hide');

                }
            });
            return false;
        });
    });

</script>

<script>
function successMessageDelete() {    
    $('<div class="alert alert-success"><strong>Slettet!</strong> Bruker er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script> 


<!-- EDIT USER -->



<script>
    $(function POSTeditUserModal() {

        $('#displayUserContainer').delegate('.edit', 'click', function () {
            var givenUserID = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: '?page=getUserByID',
                data: {givenUserID: givenUserID},
                dataType: 'json',
                success: function (data) {
                    editUserTemplate(data);
                    $('#editUserModal').modal('show');
                }
            });
            return false;

        });
    });
</script> 


<!-- EDIT USER TEMPLATE-->         
<script>
    function editUserTemplate(data) {
        var rawTemplate = document.getElementById("editUserTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var editUserGeneratedHTML = compiledTemplate(data);

        var userContainer = document.getElementById("editUserContainer");
        userContainer.innerHTML = editUserGeneratedHTML;
    }
</script>

<script>
    $(function POSTeditUserInfo() {

        $('#editUser').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                error: function(){
                    errorMessageEdit();
                },
                success: function () {
                    $('#editUserModal').modal('hide');
                    successMessageEdit();
                    UpdateUsersTable();
                }
            });
            return false;
        });
    });

</script>

<script>
function errorMessageEdit() {    
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt brukernavn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script>


<script>
function successMessageEdit() {    
    $('<div class="alert alert-success"><strong>Redigert!</strong> Bruker er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script>    


<!-- SET RESTRICTION -->

<!-- Make button visible when clicked-->
<script>
    $('#setRestriction').hide();
    $('#displayUserContainer').delegate('.selectRestriction', 'click', function () {
        if ($(".selectRestriction").is(":checked") === true) {
            $('#setRestriction').show();
        } else {
            $('#setRestriction').hide();
        }
    });
</script>

<!-- Get storage information-->
<script>
    function getStorageInfo() {
        $(function () {
            $.ajax({
                type: 'GET',
                url: '?page=getAllStorageInfo',
                dataType: 'json',
                success: function (data) {
                    storageRestrictionTemplate(data);
                }
            });
        });
    }
</script>    

<!-- Genereate userRestriciton template and display it in contaioner-->
<script>
    function storageRestrictionTemplate(data) {
        var rawTemplate = document.getElementById("storageRestrictionTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var userRestrictionGeneratedHTML = compiledTemplate(data);

        var userContainer = document.getElementById("storageRestrictionContainer");
        userContainer.innerHTML = userRestrictionGeneratedHTML;
    }
</script>

<!-- Post new restriction-->
<script>
    $(function POSTrestrictionInfo() {
        $('#editRestriction').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function () {
                    $('#userRestrictionModal').modal('hide');
                    successMessageAddRes();
                    UpdateUsersTable();
                }
            });
            return false;
        });
    });

</script>

<script>
function successMessageAddRes() {    
    $('<div class="alert alert-success"><strong>Lagret!</strong> Brukertilgang er lagret. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function() {
            $(this).remove();
           });;
}    
</script>  



<script>
 function getMediaInfo() {
    var $displayMediaInformation = $('#selectMediaID');
    $displayMediaInformation.empty();
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getAllMediaInfo',
            dataType: 'json',
            success: function (data) {
                
                $.each(data.mediaInfo, function(i, item) {

                
                $displayMediaInformation.append('<option value="'+item.mediaID+'">'+item.mediaName+'</option>');
                    
                });                
                
                
            }
        });
    });
 }
</script>