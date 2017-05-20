//Opprett kateogri 

$('#dropdown').show();
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




$(function () {
    $.ajax({
        type: 'GET',
        url: '?request=getCategorySearchResult',
        dataType: 'json',
        success: function (data) {
            categoryTableTemplate(data);
        }
    });
});


//Update category information 

function UpdateCategoryTable() {
    $(function () {
        $.ajax({
            type: 'GET',
            url: '?request=getCategorySearchResult',
            dataType: 'json',
            success: function (data) {
                categoryTableTemplate(data);
            }
        });
    });
}


// Search for category 


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



// DISPLAY CATEGORY TEMPLATE 

function categoryTableTemplate(data) {

    var rawTemplate = document.getElementById("displayCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var categoryTableGeneratedHTML = compiledTemplate(data);

    var categoryContainer = document.getElementById("displayCategoryContainer");
    categoryContainer.innerHTML = categoryTableGeneratedHTML;
}



$(function POSTdeleteUserModal() {

    $('#displayCategoryContainer').delegate('.delete', 'click', function () {
        var givenCategoryID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?request=getCategoryByID',
            data: {givenCategoryID: givenCategoryID},
            dataType: 'json',
            success: function (data) {
                deleteCategoryTemplate(data);
                $('#deleteCategoryModal').modal('show');
            }
        });
        return false;

    });
});


// DELETE CATEGORY TEMPLATE        

function deleteCategoryTemplate(data) {
    var rawTemplate = document.getElementById("deleteCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var deleteTableGeneratedHTML = compiledTemplate(data);

    var deleteContainer = document.getElementById("deleteCategoryContainer");
    deleteContainer.innerHTML = deleteTableGeneratedHTML;
}



$(function deleteCategoryByID() {

    $('#deleteCategory').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageDelete();
            },
            success: function (data) {
                UpdateCategoryTable();
                successMessageDelete();
                $('#deleteCategoryModal').modal('hide');
            }
        });
        return false;
    });
});




function successMessageDelete() {
    $('<div class="alert alert-success"><strong>Slettet!</strong> Kategori er slettet. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function errorMessageDelete() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Kan ikke slettes da kategorien er i bruk </div>').appendTo('#errorDelete')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}




//  Get the selected storage, and opens editStorage modal-->

$(function POSTeditCategoryModal() {

    $('#displayCategoryContainer').delegate('.edit', 'click', function () {
        var givenCategoryID = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '?request=getCategoryByID',
            data: {givenCategoryID: givenCategoryID},
            dataType: 'json',
            success: function (data) {
                editCategoryTemplate(data);
                $('#editCategoryModal').modal('show');
            }
        });
        return false;

    });
});


// Display edit storage Template

function editCategoryTemplate(data) {
    var rawTemplate = document.getElementById("editCategoryTemplate").innerHTML;
    var compiledTemplate = Handlebars.compile(rawTemplate);
    var editStorageGeneratedHTML = compiledTemplate(data);

    var storageContainer = document.getElementById("editCategoryContainer");
    storageContainer.innerHTML = editStorageGeneratedHTML;
}


// POST results from editing, and updating the table

$(function POSTeditStorageInfo() {

    $('#editCategory').submit(function () {
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            error: function () {
                errorMessageEdit();
            },
            success: function () {
                $('#editCategoryModal').modal('hide');
                successMessageEdit();
                UpdateCategoryTable();
            }
        });
        return false;
    });
});




function successMessageEdit() {
    $('<div class="alert alert-success"><strong>Redigert!</strong> Kategori er redigert. </div>').appendTo('#success')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}



function errorMessageEdit() {
    $('<div class="alert alert-danger"><strong>Error!</strong> Opptatt navn </div>').appendTo('#errorEdit')
            .delay(2000).fadeOut(500, function () {
        $(this).remove();
    });
    ;
}

