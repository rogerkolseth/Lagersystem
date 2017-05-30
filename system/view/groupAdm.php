<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <div class="container">
        <div class="row">
            <!-- Helppage button -->
            <div class="pull-right">
                <label data-target="#showHelpModal" title="Hjelp" data-toggle="modal"><img id="questionmark" src="image/questionmark.png"></span>
                </label>
            </div>
        </div>
        <!-- Group search -->
        <div class="col-sm-3 col-sm-offset-1 col-md-10 col-md-offset-1 form-group"> 


            <form id="searchForGroup" class="form-inline" action="?request=getGroupSearchResult" method="post">    
                <div class="form-group col-md-12 row">

                    <input class="form-control" form="searchForGroup" type="text" name="givenGroupSearchWord" value="" placeholder="Søk etter gruppe..">  
                    <input class="form-control btn btn-primary" form="searchForGroup" type="submit" value="Søk">
                    <!-- Refresh search button -->
                    <button onclick="UpdateGroupTable()" class="btn btn-primary " type="button">Alle grupper</button>

                    <!-- Create group -->
                    <div class="pull-right">
                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#createGroupModal">Opprett gruppe</button>
                    </div>
                </div>
            </form>
            <br><br>
            <div id="success"></div>
            <br><br>
            <!-- Display all groups -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><b>Gruppeoversikt</b></h3>
                </div>
                <table class="table table-responsive"> 

                    <tbody id="displayGroupContainer">

                        <!-- Content of displayGroupContainer handlebar  -->

                    </tbody>

                </table>

            </div>

        </div>
    </div>
    
    <!-- Create group modal -->

    <div class="modal fade" id="createGroupModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Opprett gruppe</h4>
                </div>
                <div class="modal-body">
                    <form action="?request=addGroupEngine" method="post" id="createGroup">
                        <div style="text-align: center">
                            <table class="table">                   
                                <tr>
                                    <th id="bordernone">Gruppenavn:</th>
                                    <td id="bordernone"><input class="form-control" type="text" required="required" name="givenGroupName" value=""></td>
                                </tr>

                            </table>
                        </div>
                </div>
                <div class="modal-footer">
                    <div id="errorCreate"></div>
                    
                    <!-- Create group button -->
                    <input class="btn btn-success" form="createGroup" type="submit" value="Opprett gruppe">


                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>

                </div>
                </form>
            </div>
        </div>
    </div> 

    <!-- Delete group Modal-->

    <div class="modal fade" id="deleteGroupModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Slett gruppe</h4>
                </div>
                <form action="?request=deleteGroupEngine" method="post" id="deleteGroup">
                    <div class="modal-body" id="deleteGroupContainer">

                        <!-- Content of deleteGroupContainer handlebar -->

                    </div>
                    <div class="modal-footer">
                        <div id="errorDelete"></div>
                        <!-- Delete group button -->
                        <input form="deleteGroup" class="btn btn-success" type="submit" value="Slett">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                    </div>
            </div>
            </form>
        </div>
    </div> 


    <!-- Edit group Modal -->


    <div class="modal fade" id="editGroupModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Rediger gruppe</h4>
                </div>
                <form action="?request=editGroupEngine" method="post" id="editGroup"> 

                    <div class="modal-body">
                        <table class="table" id="editGroupContainer">


                            <!-- Content of editGroupContainer handlebar -->

                        </table>
                    </div>

                    <div class="modal-footer">
                        <div id="errorEdit"></div>
                        <!-- Save edits to group button -->
                        <input class="btn btn-success" form="editGroup" type="submit" value="Lagre">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- GET GROUP INFORMATION MODAL -->

    <div class="modal fade" id="showGroupInformationModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Gruppe informasjon</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="col-md-12">
                    <table class="table">
                        <tbody id="groupInformationContainer">

                            <!-- Content of groupInformationContainer handlebar -->

                        </tbody>
                    </table>
                        </div>
                        <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title text-center"><b>Gruppemedlemmer</b></h2>
                        </div>
                        <table class="table">
                             
                            <tbody id="groupMemberContainer">
                                <!-- Content of groupMemberContainer handlebar -->
                                
                            </tbody>

                        </table>
                    </div>
                        </div>
                        <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title text-center"><b>Lagertilganger</b></h2>
                        </div>
                        <table class="table">
                              
                            <tbody id="storageGroupResContainer">
                                <!-- Content of storageGroupResContainer handlebar -->

                            </tbody>

                        </table>
                    </div>
                        </div>
                </div>
                </div>
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
        </div>
    </div> 


    <!-- Group restriction modal -->
    
    <div class="modal fade" id="groupRestrictionModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of modal -->
            <div class="modal-content">
                <form action="?request=addGroupRestriction" id="editGroupRestriction" method="post">
                    <div id="groupID"></div>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Velg lager tilgang(er)</h4>
                    </div>
                    <div class="modal-body">                 
                        <table class="table" id="storageRestrictionContainer">

                            <!-- Content of storageRestrictionContainer handlebar -->


                        </table>
                    </div>
                    <div class="modal-footer">
                        <!-- Save group restriction button -->
                        <button form="editGroupRestriction" class="btn btn-success" type="submit">Velg lagertilgang</button> 

                    </div>
                </form>  
            </div>
        </div>
    </div>      

    <!-- Group member modal -->
    
    <div class="modal fade" id="userMemberModal" role="dialog">
        <div class="modal-dialog">
            <!-- Content of the modal -->
            <div class="modal-content">
                <form action="?request=addGroupMember" id="addGroupMember" method="post">
                    <div id="groupUserID"></div>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Velg bruker(e) som skal inn i gruppen</h4>
                    </div>
                    <div class="modal-body">                 
                        <table class="table" id="userRestrictionContainer">

                            <!-- Content of the userRestrictionContainer handlebar -->


                        </table>
                    </div>
                    <div class="modal-footer">
                        
                        <!-- Add member to group button -->
                        <button form="addGroupMember" class="btn btn-success" type="submit">Legg til i gruppen</button> 

                    </div>
                </form>  
            </div>
        </div>
    </div>

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
                            Bruk denne funksjonen for å søke etter grupper.
                           
                        </h3>
                        
                    </div>
                    <div class="col-md-12">
                <img src="image/SøkGruppe.PNG" alt="Søk etter gruppe felt">
                    </div>
                
                
                    <div class="col-md-12">
                        <h3>
                            Her oppretter du nye grupper.
                        </h3>
                        
                    </div>
                <div class="col-md-12">
                    <img src="image/OpprettGruppe.PNG" alt="Opprett gruppe knapp">
                    </div>
                <div class="col-md-12">
                    <h3>
                        Skriv inn info om gruppen du vil opprette.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/OpprettGruppeModal.PNG" alt="opprett gruppe modal">
                </div>
                    
                    <div class="col-md-12">
                    <h3>
                        Dette er en liste over alle gruppene i systemet.
                    </h3>
                </div>
                <div class="col-md-12">
                    <img src="image/GruppeOversikt.PNG" alt="Gruppeoversikt">
                </div>
                    <div class="col-md-12">
                        <h3>
                        Dette er alternativer for produkt:<br>
                        </h3>
                        <label>
                            1. <img src="image/EndreBruker.PNG" alt="Endre bruker symbol">Endre gruppe<br>
                            2. <img src="image/InformasjonBruker.PNG" alt="Vis brukerinformasjon symbol">Vis informasjon om gruppe<br>
                            3. <img src="image/SlettBruker.PNG" alt="Slett bruker symbol">Slett gruppe<br>
                            4. <img src="image/LagertilgangBruker.PNG" alt="Velg lagertilgang symbol">Velg lagertilgang for gruppe<br>
                            5. <img src="image/GruppeMedlem.PNG" alt="Legg til bruker symbol">Velg brukere som skal være me i gruppen
                        
                    </label>
                </div>
                
                   
                    
                
                <div class="modal-footer col-md-12">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
            </div>
            </div>
        </div>
        
</div>
<script type="text/javascript" src="js/groupAdm.js"></script>

<!-- Storage group restriction handlebar -->
<script id="storageGroupResTemplate" type="text/x-handlebars-template">
{{#each StorageRestriction}}
<tr>
    <td id="bordernone">
    <button id="redigerknapp" data-id="{{resID}}" class="deleteStorageRestriction" data-toggle="tooltip" title="Fjern lagertilgang">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>
    </td>
<td id="bordernone">{{storageName}}</td>
</tr>    
    
{{/each}} 
</script>

<!-- Handlebar for group members -->
<script id="groupMemberTemplate" type="text/x-handlebars-template">
{{#each member}}
<tr>
    <td id="bordernone">
    <button id="redigerknapp" data-id="{{memberID}}" class="deleteMember" data-toggle="tooltip" title="Fjern medlem">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button>
    </td>
<td id="bordernone">{{username}}</td>
</tr>    
    
{{/each}} 
</script>

<!-- Handlebars for user restriction -->

<script id="userRestrictionTemplate" type="text/x-handlebars-template">
    {{#each users}}
    <tr> 
    <td id="bordernone">{{name}}</td> 

    <td id="bordernone"><input form="addGroupMember" class="selectUserRestriction" id="{{userID}}" value="{{userID}}"  name="userRestrictions[]" type="checkbox"></td>
    </tr>
    {{/each}}
</script>  

<!-- Handlebars for storage restrictions  -->

<script id="storageRestrictionTemplate" type="text/x-handlebars-template">
    {{#each storageInfo}}
    <tr> 
    <td id="bordernone">{{storageName}}</td> 

    <td id="bordernone"><input form="editGroupRestriction" class="selectStorageRestriction" id="{{storageID}}" value="{{storageID}}"  name="storageRestrictions[]" type="checkbox"></td>
    </tr>
    {{/each}}
</script>  

<!-- Handlebars for display productInformation-->
<script id="groupInformationTemplate" type="text/x-handlebars-template">
    {{#each groupByID}}
    <tr>  
    <th id="bordernone" class="col-md-6">GruppeID: </th>
    <td id="bordernone">{{groupID}}</td> 
    </tr>
    <tr>
    <th class="col-md-6">Gruppenavn: </th>
    <td>{{groupName}}</td>
    </tr>
    {{/each}}                                                  
</script>

<!-- Handlebars for display edit category-->                    
<script id="editGroupTemplate" type="text/x-handlebars-template">
    {{#each groupByID}}    
    <input form="editGroup" type="hidden" name="editGroupID" value="{{groupID}}">
    <tr>
    <th id="bordernone">Gruppenavn: </th> 
    <td id="bordernone"><input class="form-control" form="editGroup" required="required" type="text" name="editGroupName" value="{{groupName}}" autocomplete="off"></td> 
    </tr>
    {{/each}}            
</script>  

<!-- Handlebars for delete category template -->

<script id="deleteGroupTemplate" type="text/x-handlebars-template">
    <h4>Du holder på å slette gruppen:</h4>
    {{#each groupByID}}           
    <h4><b>{{groupName}}</b></h4>
    <input form="deleteGroup" type="hidden" name="deleteGroupID" value="{{groupID}}"><br>
    <h4>Er du sikker på at du vil fortsette?</h4>
    {{/each}}    
</script>  

<!-- Handlebars for display all category template -->
<script id="displayGroupTemplate" type="text/x-handlebars-template">
    {{#each group}} 
    <tr>
    <td class="text-center col-md-2">  

    <!-- Knapp som aktiverer Model for kategoriredigering  --> 

    <button id="redigerknapp" data-id="{{groupID}}" class="edit" data-toggle="tooltip" title="Rediger gruppe">
    <span class="glyphicon glyphicon-edit" style="color: green"></span>
    </button>

    <button id="redigerknapp" data-id="{{groupID}}" class="information" data-toggle="tooltip" title="Vis informasjon">
    <span class="glyphicon glyphicon-menu-hamburger" style="color: #003366" ></span>
    </button>

    <!-- Knapp som aktiverer Model for sletting av kategori  --> 

    <button id="redigerknapp" data-id="{{groupID}}" class="delete" data-toggle="tooltip" title="Slett gruppe">
    <span class="glyphicon glyphicon-remove" style="color: red"></span>
    </button> 

    <label id="{{groupID}}" class="groupRestriction" data-id="{{groupID}}" data-toggle="modal"data-toggle="tooltip" title="Gi lagertilgang" data-target="#groupRestrictionModal"><img id="keyIcon" src="image/key-icon2.png"></img>
    </label> 

    <button id="redigerknapp" data-id="{{groupID}}" class="addUser" data-toggle="modal"data-toggle="tooltip" data-target="#userMemberModal" title="Legg til bruker">
    <span class="glyphicon glyphicon-user" style="color: black"></span>
    </button> 
    </td>

    <!-- Printer ut grupper inn i tabellen -->

    <th>Gruppenavn: </th>
    <td>{{groupName}}</td>

    {{/each}}
    </tr>        
</script>