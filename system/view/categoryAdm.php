<?php require("view/header.php"); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">


    <div class="container"> 

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


<!-- display all users template -->
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

    <th>Kategorianvn: </th>
    <td>{{categoryName}}</td>


    </tr>
    {{/each}}



</script>


<!-- Opprett kateogri  -->
<script>
    $(function POSTstorageInfo() {

        $('#createCategory').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function () {
                    $("#createCategory")[0].reset();
                    $('#createCategoryModal').modal('hide');
                    UpdateCategoryTable();
                }
            });
            return false;
        });
    });

</script>

<script>
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?page=getCategorySearchResult',
            dataType: 'json',
            success: function (data) {
                categoryTableTemplate(data);
            }
        });
    });
</script>

<!-- Update category information -->
<script>
    function UpdateCategoryTable() {
        $(function () {
            $.ajax({
                type: 'GET',
                url: '?page=getCategorySearchResult',
                dataType: 'json',
                success: function (data) {
                    categoryTableTemplate(data);
                }
            });
        });
    }
</script>

<!-- Search for category -->

<script>
    $(function POSTsearchForStorage() {

        $('#searchForCategory').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    $("#searchForCategory")[0].reset();
                    categoryTableTemplate(data);
                    
                }
            });
            return false;
        });
    });

</script>

<!-- DISPLAY USER TEMPLATE -->
<script>
    function categoryTableTemplate(data) {

        var rawTemplate = document.getElementById("displayCategoryTemplate").innerHTML;
        var compiledTemplate = Handlebars.compile(rawTemplate);
        var categoryTableGeneratedHTML = compiledTemplate(data);

        var categoryContainer = document.getElementById("displayCategoryContainer");
        categoryContainer.innerHTML = categoryTableGeneratedHTML;
    }
</script>
