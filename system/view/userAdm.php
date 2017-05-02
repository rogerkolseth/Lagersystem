<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <div class="container">
    <div class="row">
        <div class="pull-right">
            <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
            </label>
        </div>
    </div>

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
                <button  id="setRestriction" onclick="getStorageInfo()" data-toggle="modal" data-target="#userRestrictionModal" class="btn btn-warning displayNone" type="button">Velg Lager</button>
                <button  id="setGroupRestriction" onclick="getGroupInfo()" data-toggle="modal" data-target="#userGroupRestrictionModal" class="btn btn-warning displayNone" type="button">Velg Gruppe</button>
 
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
                    <table class="table" id="storageRestrictionContainer">

                        <!-- Handlebars information -->


                    </table>
                </div>
                <div class="modal-footer">

                    <button form="editRestriction" class="btn btn-success" type="submit">Velg lagertilgang</button> 

                </div>
                 
            </div>
        </div>
    </div>   
        
        <div class="modal fade" id="userGroupRestrictionModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Velg gruppe tilgang(er)</h4>
                </div>
                <div class="modal-body">
                    <table class="table" id="groupRestrictionContainer">

                        <!-- Handlebars information -->


                    </table>
                </div>
                <div class="modal-footer">

                    <button form="editRestriction" class="btn btn-success" type="submit">Velg gruppetilgang</button> 

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
                    <h4 class="modal-title">Slett bruker</h4>
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
                    <div class="col-md-12">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Ny tabell til roger</th>
                                </tr>
                            </thead>
                        </table>
                        </div>
                </div>
                </div>
                <div class="modal-footer col-md-12">
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
                        Bruk denne funksjonen for å søke etter brukere.
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                <img src="image/SøkBruker.PNG">
                    </div>
                
                
                    <div class="col-md-12">
                        <h3>
                            Her oppretter du nye brukere.
                        </h3>
                        
                    </div>
                <div class="col-md-12">
                    <img src="image/OpprettBruker.PNG">
                    </div>
                <div class="col-md-12">
                    <h3>
                        Skriv inn info om brukeren du vil opprette.
                    </h3>
                </div>
                <div class="col-md-12">
                <img src="image/OpprettBrukerModal.PNG">
                </div>
                    <div class="col-md-12">
                    <h3>
                        Dette er en liste over alle brukerene i systemet.
                    </h3>
                </div>
                <div class="col-md-12">
                <img src="image/BrukerOversikt.PNG">
                </div>
                    <div class="col-md-12">
                        <h3>
                        Dette er alternativer for brukere:<br>
                        </h3>
                        <label>
                        1. <img src="image/EndreBruker.PNG">Endre bruker<br>
                        2. <img src="image/InformasjonBruker.PNG">Vis informasjon om bruker<br>
                        3. <img src="image/SlettBruker.PNG">Slett bruker<br>
                        4. <img src="image/LagertilgangBruker.PNG">Gi lagertilanger til bruker<br>
                        5. <img src="image/GruppeTilgang.PNG">Gi lagertilanger til bruker
                    </label>
                </div>
                
                    <div class="col-md-12">
                    <h3>
                        Checkboxen brukes om du ønsker å gi lager- eller gruppetilganger til flere brukere samtidig.
                    </h3>
                </div>
                <div class="col-md-12">
                <img src="image/CheckboxBruker.PNG">
                </div>
                    
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
            </div>
        </div>
        
</div>


<!-- HANDLEBARS TEMPLATES-->

<script id="groupRestrictionTemplate" type="text/x-handlebars-template">
{{#each group}}
    <tr> 
        <td id="bordernone">{{groupName}}</td> 

        <td id="bordernone"><input form="editRestriction" class="selectGroupRestriction" id="{{groupID}}" value="{{groupID}}"  name="groupRestrictions[]" type="checkbox"></td>
    </tr>
{{/each}}
</script>   

<script id="storageRestrictionTemplate" type="text/x-handlebars-template">
{{#each storageInfo}}
    <tr> 
        <td id="bordernone">{{storageName}}</td> 

        <td id="bordernone"><input form="editRestriction" class="selectStorageRestriction" id="{{storageID}}" value="{{storageID}}"  name="storageRestrictions[]" type="checkbox"></td>
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
    <h4> Du holder på å slette brukeren: </h4>
    {{#each user}}           
    {{name}}  
    <input form="deleteUser" type="hidden" name="deleteUserID" value="{{userID}}">
        
    {{/each}}
        <h4>Er du sikker på at du vil fortsette? </h4>   
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
    
    <!-- Knapp som aktiverer Model for rettigheter av bruker  --> 

    <label for="setRes{{userID}}" id="{{userID}}" onclick="getStorageInfo()" data-toggle="modal"data-toggle="tooltip" title="Gi lagertilgang" data-target="#userRestrictionModal">    <img id="keyIcon" src="image/key-icon2.png"></img>
    </label> 

    <label for="setRes{{userID}}" id="{{userID}}" style="margin-left:8px" onclick="getGroupInfo()" data-toggle="modal"data-toggle="tooltip" title="Gi gruppetilgang" data-target="#userGroupRestrictionModal">    <img id="keyIcon" src="image/user-group.png"></img>
    </label>

    </td>
 
 
    <!-- Printer ut navn og brukernavn inn i tabellen -->

    <th>Navn: </th>
    <td>{{name}}</td>
    <th>Brukernavn: </th>
    <td>{{username}}</td>


    <!-- Legger inn checkbox for fler valg (ved lagertilganggiving -->

  
    
    <td> <input form="editRestriction" class="selectRestriction" id="setRes{{userID}}" value="{{userID}}" data-toggle="tooltip" title="Gi tilgang" name="userRestrictions[]" type="checkbox"></td>

  
</tr>
    {{/each}}
    


</script>



<script type="text/javascript" src="js/userAdm.js"></script> 