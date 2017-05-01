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


            <form id="searchForGroup" class="form-inline" action="?page=getGroupSearchResult" method="post">    
                <div class="form-group col-md-12 row">

                    <input class="form-control" form="searchForGroup" type="text" name="givenGroupSearchWord" value="" placeholder="Søk etter gruppe..">  
                    <input class="form-control btn btn-primary" form="searchForGroup" type="submit" value="Søk">

                    <button onclick="UpdateGroupTable()" class="btn btn-primary " type="button">Alle grupper</button>

                    <div class="pull-right">
                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#createGroupModal">Opprett gruppe</button>
                    </div>
                </div>
            </form>
            <br><br>
            <div id="success"></div>
            <br><br>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><b>Gruppeoversikt</b></h3>
                </div>
                <table class="table table-responsive"> 

                    <tbody id="displayGroupContainer">

                        <!-- HER KOMMER INNHOLDET FRA HANDLEBARS  -->

                    </tbody>

                </table>

            </div>

        </div>
    </div>
    
        <div class="modal fade" id="createGroupModal" role="dialog">
        <div class="modal-dialog">
            <!-- Innholdet til Modalen -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Opprett gruppe</h4>
                </div>
                <div class="modal-body">
                    <form action="?page=addGroupEngine" method="post" id="createGroup">
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
        <!-- Innholdet til Modalen -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Slett gruppe</h4>
            </div>
            <form action="?page=deleteGroupEngine" method="post" id="deleteGroup">
                <div class="modal-body" id="deleteGroupContainer">

                    <!-- Innhold fra Handlebars Template-->

                </div>
                <div class="modal-footer">
                    <div id="errorDelete"></div>
                    <input form="deleteGroup" class="btn btn-success" type="submit" value="Slett">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Avslutt</button>
                </div>
        </div>
        </form>
    </div>
</div> 